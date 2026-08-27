<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'product_name',
        'description',
        'arrangement',
        'is_highlight',
        'is_active',
        'tag',
    ];
    
    public function file_attachments()
    {
        return $this->morphMany('App\Models\FileAttachment', 'content');
    }
}
