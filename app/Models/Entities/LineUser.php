<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class LineUser extends Model
{
    protected $table = 'line_users';
    protected $fillable = [
        'user_id', // line user id
        'reply_token', // 傳訊息的token
        'message', // 訊息
    ];
}
