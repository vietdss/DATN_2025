<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Carbon\Carbon;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category_id',
        'location',
        'quantity',
        'expired_at',
        'status',
        'is_approved'
    ];
    protected $casts = [
        'expired_at' => 'datetime',
    ];
    public function images()
    {
        return $this->hasMany(Image::class);

    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'post_id');
    }
}