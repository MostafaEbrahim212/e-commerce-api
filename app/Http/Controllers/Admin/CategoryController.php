<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Helpers\NotFoundHelper;
use App\Helpers\SlugHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use App\Traits\UploadsImages;
use Exception;
use Illuminate\Http\Request;
use Response;
use Storage;

class CategoryController extends Controller
{

    use UploadsImages;

    private $categoryRepository;
    public function __construct(CrudRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }



    public function index(SearchRequest $request)
    {
        try {
            $query = $this->categoryRepository->getAllQuery();

            if ($request->has('name')) {
                $query = $this->categoryRepository->search($query, $request->name);
            }

            $sort_by = $request->get('sort_by', 'created_at');
            $order = $request->get('order', 'asc');
            $query = $this->categoryRepository->sort_by($query, $sort_by, $order);

            if ($request->has('filter')) {
                $query = $this->categoryRepository->filter($query, $request->filter, $request->value);
            }

            if ($request->has('status')) {
                $query = $this->categoryRepository->status($query, $request->status);
            }

            $categories = $query->paginate(10);
            return ApiResponseHelper::resData([
                'total_in_page' => $categories->count(),
                'categories' => $categories,
            ], 'Categories fetched successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }

    }

    public function categorProducts(string $id)
    {
        try {
            $query = $this->categoryRepository->CategoryProducts($id);
            $products = $query->paginate(10);
            return ApiResponseHelper::resData([
                'total' => $products->count(),
                'products' => $products,
            ], 'Products fetched successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }
    public function store(Request $request)
    {
        try {

            $validationResult = ValidationHelper::validateLoginRequest($request, [
                'name' => 'required|string',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if (!$validationResult['success']) {
                return ApiResponseHelper::resData($validationResult['errors'], 'Validation errors', 422);
            }
            $slug = SlugHelper::generateUniqueSlug($request->name, Category::class);
            if ($request->hasFile('image')) {
                $image = $this->uploadImage($request->file('image'), 'categories');
            }
            $category = $this->categoryRepository->create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $image,
                'slug' => $slug,
            ]);
            return ApiResponseHelper::resData(['category' => $category], 'Category created successfully', 201);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }


    public function show(string $id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            $notFound = NotFoundHelper::checkNotFound($category, 'Category not found', );
            if ($notFound) {
                return $notFound;
            }
            $category = new CategoryResource($category);
            return ApiResponseHelper::resData(['category' => $category], 'Category fetched successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return ApiResponseHelper::resData(null, 'Category not found', 404);
            }
            $validationResult = ValidationHelper::validateLoginRequest($request, [
                'name' => 'required|string',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if (!$validationResult['success']) {
                return ApiResponseHelper::resData($validationResult['errors'], 'Validation errors', 422);
            }
            $slug = SlugHelper::generateUniqueSlug($request->name, Category::class);
            if ($request->hasFile('image')) {
                $image = $this->uploadImage($request->file('image'), 'categories', $category->image);
            }
            $category = $this->categoryRepository->update($id, [
                'name' => $request->name,
                'description' => $request->description,
                'image' => $image,
                'slug' => $slug,
            ]);
            return ApiResponseHelper::resData(['category' => $category], 'Category updated successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }

    public function image($image)
    {
        $filePath = 'images/categories/' . $image;
        if (Storage::disk('public')->exists($filePath)) {
            return Response::make(Storage::disk('public')->get($filePath), 200, [
                'Content-Type' => Storage::disk('public')->mimeType($filePath)
            ]);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }
    public function destroy(string $id)
    {
        try {
            $category = $this->categoryRepository->delete($id);
            if (!$category) {
                return ApiResponseHelper::resData(null, 'Category not found', 404);
            }
            return ApiResponseHelper::resData(null, 'Category deleted successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }
}
