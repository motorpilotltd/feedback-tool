<?php
namespace App\Traits\Livewire;

use Illuminate\Support\Str;

trait WithMultipleFileErrors
{
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

}
