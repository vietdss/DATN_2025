<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'post_id',
        'giver_id',
        'receiver_id',
        'quantity', 
        'status',
        'is_read',
    ];

    // Nếu bạn dùng enum PHP 8.1+, có thể định nghĩa status kiểu enum

    // Quan hệ: Mỗi transaction liên kết tới một item/post
    public function post()
    {
        return $this->belongsTo(Item::class, 'post_id');
    }

    // Quan hệ với người cho (người đăng bài)
    public function giver()
    {
        return $this->belongsTo(User::class, 'giver_id');
    }

    // Quan hệ với người nhận (người yêu cầu)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
