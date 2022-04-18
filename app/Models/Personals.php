<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personals extends Model
{
    use HasFactory;

    protected $fillable = ['mail', 'access_token'];
}
