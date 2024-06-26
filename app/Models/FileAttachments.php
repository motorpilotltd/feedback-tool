<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileAttachments extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFullPath()
    {
        return $this->section.'/'.$this->item_id.'/'.$this->file_name;
    }
}
