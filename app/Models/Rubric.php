<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubric extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'parent_id'
    ];

    public function childrenWith()
    {
        return $this->hasMany(Rubric::class, 'parent_id', 'id')->with(['childrenWith', 'news' ]);
    }

    public function news()
    {
        return $this->belongsToMany(News::class, 'rubric_news');
    }
}
