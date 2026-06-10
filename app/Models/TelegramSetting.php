<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramSetting extends Model
{
    protected $table = 'telegram_settings';

    protected $fillable = ['user_id', 'telegram_chat_id', 'is_active'];
}