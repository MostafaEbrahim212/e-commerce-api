<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Helpers\NotFoundHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Response;
use Exception;
use Storage;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(SearchRequest $request)
    {
        try {
            $categories = $this->categoryService->getCategories($request);
            $message = $categories->isEmpty() ? 'No categories found' : 'Categories fetched successfully';

            return ApiResponseHelper::resData([
                'total_in_page' => $categories->count(),
                'total' => $categories->total(),
                'categories' => CategoryResource::collection($categories),
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
                'has_next_page' => $categories->hasMorePages(),
                'has_previous_page' => $categories->previousPageUrl() !== null,
            ], $message, 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => 'An error occurred while fetching categories.'], $e->getMessage(), 500);
        }
    }

    public function categorProducts(string $id)
    {
        try {
            $products = $this->categoryService->getCategoryProducts($id);
            $message = $products->isEmpty() ? 'No products found' : 'Products fetched successfully';
            return ApiResponseHelper::resData([
                'total_in_page' => $products->count(),
                'total' => $products->total(),
                'products' => ProductResource::collection($products->items()),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'has_next_page' => $products->hasMorePages(),
                'has_previous_page' => $products->previousPageUrl() !== null,
            ], $message, 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $category = $this->categoryService->storeCategory($request);
            return ApiResponseHelper::resData(
                ['category' => new CategoryResource($category)],
                'Category created successfully',
                201
            );
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }

    public function show(string $id)
    {
        try {
            $category = $this->categoryService->getCategory($id);
            $notFound = NotFoundHelper::checkNotFound($category, 'Category not found');
            if ($notFound) {
                return $notFound;
            }
            return ApiResponseHelper::resData(
                ['category' => new CategoryResource($category)],
                'Category fetched successfully',
                200
            );
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $category = $this->categoryService->updateCategory($request, $id);
            return ApiResponseHelper::resData(
                ['category' => new CategoryResource($category)],
                'Category updated successfully',
                200
            );
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }

    public function image($image)
    {
        $fileContent = $this->categoryService->getImage($image);
        if ($fileContent) {
            return Response::make($fileContent, 200, [
                'Content-Type' => Storage::disk('public')->mimeType('images/categories/' . $image)
            ]);
        }

        return ApiResponseHelper::resError(null, 'Image not found', 404);
    }

    public function destroy(string $id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            return ApiResponseHelper::resData(null, 'Category deleted successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }
}
