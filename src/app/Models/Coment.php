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
        'is_trading', //trade画面か？
        'is_read', //既読フラグ
        'is_hold', //未送信済フラグ
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
