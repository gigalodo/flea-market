<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'content',
        'image', //trade画面用
        'is_trading', //trade画面かどうかのフラグ
        'is_read', //既読フラグ 既読時true
        'is_hold', //未送信フラグ 未送信時true
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
