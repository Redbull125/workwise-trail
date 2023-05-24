<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'text',
        'published',
        'expiration'
    ];
}
