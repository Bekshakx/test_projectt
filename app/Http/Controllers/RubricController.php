<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRubricRequest;
use App\Http\Resources\RubricIndexResource;
use App\Services\RubricService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RubricController extends Controller
{
    public function __construct(protected RubricService $rubricService) {}

    public function store(StoreRubricRequest $request)
    {
        $this->rubricService->create( $request->validated());

        return response()->json(
            [
                'message' => 'Рубрика успешно создано'
            ], 
            Response::HTTP_CREATED
        );
    }

    public function index()
    {
        return RubricIndexResource::collection($this->rubricService->index());
    }
}
