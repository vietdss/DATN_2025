<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['item_id', 'image_url', 'public_id'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}