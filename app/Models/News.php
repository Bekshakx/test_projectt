<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'header',
        'preview',
        'text',
    ];

    public function authors()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function rubrics()
    {
        return $this->belongsToMany(Rubric::class, 'rubric_news');
    }
}
