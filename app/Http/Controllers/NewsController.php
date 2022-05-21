<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetNewsRequest;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\Rubric;
use App\Models\User;
use App\Services\NewsService;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    public function __construct(protected NewsService $newsService) {}
    
    public function index(GetNewsRequest $request)
    {
        return $this->newsService->index($request->validated());
    }

    public function store(StoreNewsRequest $request)
    {
        $this->newsService->store($request->validated());
        return response()->json(
            [
                'message' => 'новость успешно создано'
            ], 
            Response::HTTP_CREATED
        );   
    }

    
    public function show($id)
    {
        return response()->json(
            [
                'data' => $this->newsService->find($id)
            ], 
            Response::HTTP_OK
        );
    }

   
    public function update(UpdateNewsRequest $request, $id)
    {
        $this->newsService->update($request->validated(), $id);
        return response()->json(
            [
                'message' => 'успешно обновлено'
            ]
        );
    }

    
    public function destroy($id)
    {
        $this->newsService->destroy($id);
        return response()->json(
            [
                'message' => 'успешно удаленно'
            ], 
            Response::HTTP_OK
        );
    }

    public function getAllNewsByUser(User $user)
    {
        return response()->json(
            [
                'data' => $this->newsService->getAllNewsByUser($user)
            ], 
            Response::HTTP_OK
        );
    }

    public function getAllNewsByRubric(Rubric $rubric)
    {
        return response()->json(
            [
                'data' => $this->newsService->getAllNewsByRubric($rubric)
            ], 
            Response::HTTP_OK
        );
    }
}
