<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\GeneralResource;
use App\Services\NewsService;
use App\Utils\ApiResponse;
use App\Utils\AppMessages;
use App\Utils\Constants;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Exception;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function articles(Request $request) {

        try {
            $news = $this->newsService->articles($request)->paginate(Constants::PAGINATION_PER_PAGE);
            return ApiResponse::ofPaginatedData(ArticleResource::collection($news), ['message' => "Articles"]);
        } catch(QueryException $e){
            \Log::error("SQL Error", ['error' => $e]);
            return ApiResponse::ofClientError(AppMessages::DB_ERROR(), [AppMessages::DB_ERROR()]);
        }catch(Exception $e){
            \Log::error($e->getMessage());
            return ApiResponse::ofClientError(__('Failed to fetch news, try again later'), [$e->getMessage()]);
        }
    }

    public function sources()
    {
        try {
            return ApiResponse::ofData(GeneralResource::collection($this->newsService->sources()), __('sources'));
        }catch(Exception $e){
            \Log::error($e->getMessage());
            return ApiResponse::ofClientError(__('Failed to fetch sources'), [$e->getMessage()]);
        }
    }

    public function categories()
    {
        try {
            return ApiResponse::ofData(GeneralResource::collection($this->newsService->categories()), __('categories'));
        }catch(Exception $e){
            \Log::error($e->getMessage());
            return ApiResponse::ofClientError(__('Failed to fetch categories'), [$e->getMessage()]);
        }
    }
}
