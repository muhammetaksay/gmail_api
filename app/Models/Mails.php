<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mails extends Model
{
    use HasFactory;

    protected $fillable = ['mail_id', 'personal_id', 'mail_content', 'status', 'snippet'];
}
