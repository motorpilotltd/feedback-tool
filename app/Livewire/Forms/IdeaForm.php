<?php

namespace App\Livewire\Forms;

use App\DataTransferObject\IdeaDto;
use App\Models\Idea as IdeaModel;
use App\Models\Product;
use App\Models\Tag;
use App\Models\TagGroup;
use App\Models\User;
use App\Notifications\AccountCreated;
use App\Notifications\IdeaAdded;
use App\Services\Idea\IdeaService;
use App\Services\Idea\IdeaVoteService;
use App\Settings\GeneralSettings;
use App\Traits\Livewire\WithDispatchNotify;
use App\Traits\Livewire\WithMediaAttachments;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class IdeaForm extends Component
{
    use WithFileUploads, WithMediaAttachments, WithDispatchNotify, Actions;

    public $product;
    public $idea;
    public $categories;
    public $title;
    public $content;
    public $category;
    public $formTitle;
    public $addOrUpdate;
    public $allowedTypes;
    public $allowedSize;
    public $showModal = false;
    public $tagGroups;
    public $selectedTags;
    public $authorOption = 1;
    public $authorId;
    public $newUser = [];
    public $authUser;

    protected $listeners = ['setIdeaFormData'];

    public function mount(?Product $product, ?IdeaModel $idea)
    {
        $this->authUser = auth()->user();
        $this->product = $product;
        $this->idea = $idea;
        $this->selectedTags = [];
        $this->formTitle = !empty($idea)? __('text.ideaformtitle:update') : __('text.ideaformtitle:add');
        $this->categories = $product->categories;
        $this->addOrUpdate = empty($idea) ? 'add': 'update';

        $this->allowedSize = config('const.MAX_FILESIZE_UPLOAD');
        $this->allowedTypes = config('const.ALLOWED_FILETYPES');
        $this->title = session()->get('suggestIdeaTitle') ?? '';

        $this->newUser = [
            'name' => '',
            'email' => '',
        ];
        $this->authorId = $this->authUser->id;

        $this->setTagGroups();
        $this->setIdeaFormData($this->idea);
    }

    /**
     * Set Edit idea data and set the fields for form
     */
    public function setIdeaFormData(IdeaModel $idea)
    {
        $this->resetSelectedTags();
        if ($idea->exists) {
            $this->title = $idea->title;
            $this->content = $idea->content;
            $this->category = $idea->category_id;
            $ideaTags = $idea->tags->map->only(['id', 'tag_group_id']);
            foreach ($ideaTags as $tag) {
                $this->selectedTags['tg_' . $tag['tag_group_id']][] = $tag['id'];
            }
        }
    }

    public function setTagGroups()
    {
        $this->tagGroups = TagGroup::with('tags')->where('product_id', $this->product->id)->get();
    }

    public function addUserTag(string $tag, int $tagGroupId)
    {
        $tag = Tag::create([
            'name' => $tag,
            'added_by' => $this->authUser->id,
            'tag_group_id' => $tagGroupId
        ]);

        $this->setTagGroups();

        $this->selectedTags['tg_' . $tagGroupId][] = $tag->id;
    }

    public function resetSelectedTags()
    {
        // Initialize holder for selected tags on each Tag Group
        if ($this->tagGroups->isNotEmpty()) {
            foreach ($this->tagGroups as $tg) {
                $this->selectedTags['tg_' . $tg->id] = [];
            }
        }
    }

    protected function rules()
    {
        $category = $this->category;
        $categories = $this->categories->pluck('id');
        return [
            'title' => ['required', 'min:4', 'max:255'],
            'category' => ['required', 'integer', function ($attribute, $value, $fail) use ($category, $categories){
                if (!in_array($category, $categories->toArray())) {
                    $fail(__('error.invalidcategoryvalue'));
                }
            }],
            'content' => 'required:min:4',
            'attachments.*' => 'image|max:2024',
            'newUser.name' => 'required_if:authorOption,0|min:4|max:255',
            'newUser.email' => 'max:255|required_if:authorOption,0|email|unique:users,email,' . $this->authUser->id
        ];
    }

    protected function messages()
    {
        return [
            'newUser.name.required_if' => __('validation.required', ['attribute' => 'name']),
            'newUser.email.required_if' => __('validation.required', ['attribute' => 'email']),
            'newUser.email.unique' => __('validation.unique', ['attribute' => 'email'])
        ];
    }

    public function saveIdea(IdeaVoteService $ideaVoteService, IdeaService $ideaService)
    {
        if (!auth()->check()) {
            $this->sessionNotify('warning', __('text.mustlogin'));
            return redirect()->route('login');
        }
        if ($this->idea->exists && auth()->user()->cannot('update', $this->idea)) {
            $this->notification()->warning(
                $description = __('error.actionnotpermitted'),
            );
            $this->dispatch('saveidea-unauthorized');
            return;
        }

        // Form validation
        $data = $this->validate();
        // New user author option
        $newUser = $this->newUser;

        // Note: We probably don't want to send email/notification if product is in sandbox mode
        $diffAuthor = null;
        if (!empty($newUser['name']) && !empty($newUser['email'])) {
            $diffAuthor = User::create([
                'name' => $newUser['name'],
                'email' => $newUser['email'],
            ]);
            $this->authorId = $diffAuthor->id;
            // Send an email
            $diffAuthor->notify(New AccountCreated($diffAuthor));
        }

        // Create an idea
        $isNew = false;
        $storeAttachment = false;

        $data['status'] = $this->product->settings['enableAwaitingConsideration'] ? config('const.STATUS_NEW') : config('const.STATUS_CONSIDERED');
        $data['authorId'] = $this->authorId;
        $data['addedBy'] = $this->authUser->id;
        // Define the Idea DTO object
        $ideaDto = IdeaDto::fromArray($data);

        if (!$this->idea->exists) {
            $idea = $ideaService->store($ideaDto);
            // Initiate storing attachment
            $storeAttachment = true;

            // Vote user to its own idea
            $ideaVoteService->toggleVote($idea, auth()->user());

            // Send email/notification to user that an idea was added on their behalf
            if ($this->authorId !== $this->authUser->id) {
                $diffAuthor = $diffAuthor ?? User::find($this->authorId);
                $diffAuthor->notify(new IdeaAdded($idea, true));
            }

            // Notify product admins that idea was added to product
            if ($productAdmins = User::permission(config('const.PERMISSION_PRODUCTS_MANAGE') . '.' . $this->product->id)->get()) {
                $productAdmins->each(function ($user) use ($idea) {
                    if ($user->id !== auth()->id()) {
                        $user->notify(new IdeaAdded($idea));
                    }
                });
            }

            $isNew = true;
        } else {
            // Do not allow non-author to edit the idea
            $idea = $this->idea;
            if (auth()->user()->cannot('update', $this->idea)) {
                $this->notification()->warning(
                    $description = __('error.editideanotallowed'),
                );
            } else {
                $idea = $ideaService->update($this->idea, $ideaDto);
                $storeAttachment = true;
            }
        }

        // Sync idea Tags
        $ideaService->syncTags($idea, $this->selectedTags);

        // Store idea attachments
        if ($storeAttachment) {
            $this->storeIdeaAttachments($idea);
        }
        $this->sessionNotifySuccess($isNew ? __('text.createideasuccess') : __('text.ideaupdatesuccess'));

        return redirect()->route('idea.show', $idea);
    }

    public function render()
    {
        $this->displayMultipleFileErrors($this->attachments, 'attachments');
        return view('livewire.forms.idea-form', []);
    }
}
