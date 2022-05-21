<?php

namespace App\Services;

use App\Exceptions\AuthErrorException;
use App\Exceptions\ParametersErrorException;
use App\Models\News;
use App\Models\NewsRubric;
use App\Models\RubricNews;
use App\Models\User;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class NewsService
{
    // public function __construct(
    //     protected RubricService $rubricService
    // )
    // {        
    // }

    public function store($attributes)
    {
        $authUser = auth()->user();
        $attributes['author_id'] = $authUser->id;
        $news = News::create($attributes);

        foreach ($attributes['rubrics'] as $rubric_id){
            RubricNews::create(
                [
                    'news_id' => $news->id,
                    'rubric_id' =>  $rubric_id
                ]
            );
        }
        
        
        $project = [
            'body' => 'Новость - '.$attributes['header'].' успешно создано.',
            'thanks' => 'Спасибо за использование',
            'id' => 15
        ];
        Notification::send($authUser, new SendEmailNotification($project));
        return $news;
    }

    public function index($attributes)
    {
        $header = data_get($attributes, 'header');
        $preview = data_get($attributes, 'preview');
        $authorName = data_get($attributes, 'author_name');
        $rubricTitle = data_get($attributes, 'rubric_title');
        $perPage = data_get($attributes, 'per_page', 10);

        return News::with('authors:id,surname,name,patronymic,avatar,email')
            ->when($header, function($query) use($header) {
                return $query->where('header', 'like', '%'.$header.'%');
            })
            ->when($preview, function($query) use($preview) {
                return $query->where('preview', 'like', '%'.$preview.'%');
            })
            ->when($authorName, function($query) use($authorName) {
                return $query->whereHas('authors', function ($qb) use($authorName) {
                    return $qb->where('name', 'like', '%'.$authorName.'%');
                });
            })
            ->when($rubricTitle, function($query) use($rubricTitle)
            {
                return $query->whereHas('rubrics', function($qb) use($rubricTitle)
                        {
                            return $qb->where('title', 'like', '%'.$rubricTitle.'%');
                        }
                    );
            })
            ->paginate($perPage);
    }

    public function find($id)
    {
        return News::findOrFail($id);
    }

    public function update($attributes, $id)
    {
        $news = $this->find($id);

        if($news->author_id != Auth::user()->id)
        {
            throw new AuthErrorException('У вас нету доступа');
        }

        if (isset($attributes['rubric_id']))
        {
            $rubricId = $attributes['rubric_id'];
            $newsRubric = RubricNews
                ::where('news_id', $news->id)
                ->where('rubric_id', $rubricId)
                ->first();

            $newsRubric->update(
                [
                    'rubric_id' => $rubricId
                ]
            );
            unset($attributes['rubric_id']);
        }
        $news->update($attributes);
        return $news;
    }

    public function destroy($id)
    {
        $news = $this->find($id);
        return $news->delete();
    }

    public function getAllNewsByUser($user)
    {
        return News::where('author_id', $user->id)
            ->get();
    }

    public function getAllNewsByRubric($rubric)
    {
        return News::whereHas('rubrics', function ($query) use($rubric) {
            $query->where('rubric_id', $rubric->id);
        })
        ->get();
    }
}