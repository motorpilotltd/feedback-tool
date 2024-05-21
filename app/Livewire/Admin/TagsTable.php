<?php

namespace App\Livewire\Admin;

use App\Traits\Livewire\WithProductSelection;
use App\Models\Tag;
use App\Models\TagGroup;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class TagsTable extends Component
{
    use WithProductSelection,
        Actions,
        WithPagination;

    public $showModal = false;
    public $tagGroup;
    public $tagsOptions = [];
    public $tagsSelected = [];
    public $tagGroupName;
    public $isManaged;
    public $adminOrUser;
    public $managed;
    public $unmanaged;

    public $searchTag = '';

    public function mount()
    {
        $this->managed = (string) config('const.TAGS_MANAGED');
        $this->unmanaged = (string) config('const.TAGS_UNMANAGED');
    }

    protected function rules()
    {
        return [
            'tagGroupName' => [
                'required',
                Rule::unique('tag_groups', 'name')
                ->where(fn ($query) => $query->where('product_id', $this->productId))
                ->when(
                    isset($this->tagGroup->id),
                    fn($query) => $query->ignore($this->tagGroup->id, 'id')
                )
            ],
            'isManaged' => 'required',
            'adminOrUser' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->tagGroupName = '';
        $this->isManaged = '';
        $this->adminOrUser = '';
        $this->tagsSelected = [];
        $this->tagsOptions = [];
    }

    public function tagGroupFormModal($tagGroupId = 0)
    {
        $this->resetForm(); // Reset form
        if ($this->tagGroup = TagGroup::find($tagGroupId)) {
            $tags = $this->tagGroup->tags;
            if ($tags->isNotEmpty()) {
                $tags->each(function ($tag) {
                    $this->addToTagsSelected($tag->name);
                });
            }
            $this->tagGroupName = $this->tagGroup->name;
            $this->isManaged = (string) $this->tagGroup->is_managed;
            $this->adminOrUser = $this->tagGroup->admin_or_user;
        }
        $this->showModal = true;
    }

    public function addToTagsSelected($tag)
    {
        if (!in_array($tag, $this->tagsOptions)) {
            $this->tagsOptions[] = $tag;
        }
        if (!in_array($tag, $this->tagsSelected)) {
            $this->tagsSelected[] = $tag;
        }
    }

    public function save()
    {
        $message = '';
        $this->validate();
        $tagGroup = $this->tagGroup;
        $data = [
            'name' => $this->tagGroupName,
            'is_managed' => $this->isManaged,
            'admin_or_user' => $this->adminOrUser
        ];
        if (empty($tagGroup)) {
            // save new
            $tagGroup = TagGroup::create([
                'added_by' => auth()->id(),
                'product_id' => $this->productId,
                ...$data
            ]);
            $message = __('text.successfullysaved');
        } else {
            // Updating Tag Group
            $tagGroup->update([...$data]);
            $message = __('text.successfullyupdated');
        }

        // Save new tag(s)
        if (!empty($this->tagsSelected) && isset($tagGroup->id)) {
            collect($this->tagsSelected)->each(function ($tag) use ($tagGroup) {
                Tag::firstOrCreate(['tag_group_id' => $tagGroup->id, 'name' => $tag], [
                    'tag_group_id' => $tagGroup->id,
                    'name' => $tag,
                    'added_by' => auth()->id()
                ]);
            });
        }

        // Delete removed tag(s) from the selected
        if (!empty($tagGroup->tags)) {
            $tagGroup->tags->each(function ($tag) {
                if (!in_array($tag->name, $this->tagsSelected)) {
                    $tag->delete();
                }
            });
        }

        $this->notification()->success(
            $description = $message,
        );

        $this->showModal = false;
    }

    public function deleteDialog(TagGroup $tagGroup, bool $confirm = false)
    {
        if (!$confirm) {
            $this->dialog()->confirm([
                'title'       => __('text.areyousure'),
                'description' => __('text.tags:delete:taggroup', ['taggroupname' => $tagGroup->name]),
                'icon'        => 'trash',
                'accept'      => [
                    'label'  => __('text.yes_confirm'),
                    'method' => 'deleteDialog',
                    'params' => [
                        $tagGroup,
                        true
                    ],
                ],
                'reject' => [
                    'label'  => __('text.no_cancel'),
                ],
            ]);
        } else {
            $tagGroup->delete();
            $this->notification()->success(
                $description = __('text.successfullydeleted')
            );
        }
    }

    public function getTagGroupsProperty()
    {
        return TagGroup::where('product_id', $this->productId)
            ->with(['user'])
            ->withCount('tags')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.tags-table', [
            'tagGroups' => $this->tagGroups
        ]);
    }
}
