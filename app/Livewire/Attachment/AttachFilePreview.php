<?php

namespace App\Livewire\Attachment;

use App\Traits\Livewire\WithDispatchNotify;
use App\Models\Comment;
use App\Models\FileAttachments;
use App\Models\Idea;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use WireUi\Traits\Actions;

class AttachFilePreview extends Component
{
    use Actions,
        WithDispatchNotify;

    public $attachments = [];
    public $hasdelete;
    public ?Idea $idea;
    public ?Comment $comment;
    public $model = null;
    public $modelType = null;
    public $hasAuthCheck = false;

    public function mount($model, $hasdelete = null, $hasAuthCheck = false)
    {
        $this->hasAuthCheck = $hasAuthCheck;
        $this->model = $model;
        $this->modelType = get_class($model);
        $this->setFilesPreview();
    }

    protected function getListeners()
    {
        return [
            "filepreview:{$this->modelType}:{$this->model->id}" => 'setFilesPreview'
        ];
    }

    public function setFilesPreview()
    {
        if (!empty($this->model)) {
            $this->attachments = $this->model->getMedia('attachments');
        }
    }

    public function deleteFile($fileIndex, bool $confirm = false)
    {
        if (!$confirm) {
            $this->dialog()->confirm([
                'title'       => __('text.areyousure'),
                'description' => __('text.deletethisattachmentconfirm'),
                'icon'        => 'trash',
                'accept'      => [
                    'label'  => __('text.yes_confirm'),
                    'method' => 'deleteFile',
                    'params' => [
                        $fileIndex,
                        true
                    ],
                ],
                'reject' => [
                    'label'  => __('text.no_cancel'),
                ],
            ]);
        } else {
            $file = $this->attachments[$fileIndex];
            if ($file->delete()) {
                $this->notification()->success(
                    $description = __('text.filedeletedsuccess')
                );

                // Update model's file preview instance
                $this->dispatch("filepreview:{$this->modelType}:{$this->model->id}");
            }
        }
    }

    public function render()
    {
        return view('livewire.attachment.attach-file-preview');
    }
}
