<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Helpers\ApiResponse;
use App\Resources\Category\CategoryResource;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
     public function __construct(protected CategoryService $service) {}

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $categories = $this->service->list($request->all(), $perPage);

        return ApiResponse::collection(CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->create($request->validated());

        return ApiResponse::resource(new CategoryResource($category), 'Category created successfully');
    }

    public function show(Category $category)
    {
        return ApiResponse::resource(new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updatedCategory = $this->service->update($category, $request->validated());

        return ApiResponse::resource(new CategoryResource($updatedCategory), 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);

        return ApiResponse::success([], 'Category deleted successfully');
    }
}
