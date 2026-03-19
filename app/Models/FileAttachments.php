<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Unguarded]
class FileAttachments extends Model
{
    use HasFactory;

    public function getFullPath()
    {
        return $this->section.'/'.$this->item_id.'/'.$this->file_name;
    }
}
