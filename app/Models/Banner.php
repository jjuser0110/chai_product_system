<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'box_wording',
        'title',
        'description',
        'button_text',
        'button_link',
        'arrangement',
        'is_active',
    ];
    

    public function file_attachments()
    {
        return $this->morphMany('App\Models\FileAttachment', 'content');
    }
}
