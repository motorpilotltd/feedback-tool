<?php
namespace App\Traits\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Support\Str;

trait WithMediaAttachments
{
    public $attachments = [];

    public function displayMultipleFileErrors($files, $fieldName)
    {
        $errors = $this->getErrorBag();
        $errorBag = $errors->getMessages();
        if(count($errorBag) > 0){
            $errorMessages = [];
            foreach ($errorBag as $key => $val) {
                $_key = explode('.', $key);
                $fileName = '';
                if ($_key[0] != $fieldName) {
                    continue;
                }
                if (count($_key) > 1) {
                    $fileIndex = $_key[1];
                    if(!isset($files[$fileIndex])) {
                        continue;
                    }
                    $fileName = $files[$fileIndex]->getClientOriginalName();
                }
                foreach ($val as $err) {
                    $errorMessages[] = '&bull;'. Str::replace($key, '"' . $fileName . '"', $err);
                }
            }
            if (!empty($errorMessages)) {
                $errors->add($fieldName, implode('<br/>', $errorMessages));
            }
        }
    }

    public function storeIdeaAttachments(Idea $idea)
    {
        $this->storeAttachments($idea);
    }

    public function storeCommentAttachments(Comment $comment)
    {
        $this->storeAttachments($comment);
    }

    public function storeAttachments($model)
    {
        if (!empty($this->attachments)) {
            $modelType = get_class($model);
            collect($this->attachments)->each(fn($image) =>
                $model->addMedia($image->getRealPath())->toMediaCollection('attachments')
            );

            $this->attachments = [];

            // Refresh files from a model's instance
            $this->dispatch("filepreview:{$modelType}:{$model->id}");
        }
    }

}
