<?php
namespace App\Services;

use App\Helpers\ApiResponseHelper;
use App\Helpers\FilterHelper;
use App\Helpers\NotFoundHelper;
use App\Helpers\SlugHelper;
use App\Helpers\ValidationHelper;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use App\Models\Category;
use App\Traits\UploadsImages;
use Illuminate\Http\Request;
use Storage;

class CategoryService
{
    public $categoryRepository;
    use UploadsImages;
    public function __construct(CrudRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories(Request $request)
    {
        $query = $this->categoryRepository->getAllQuery();
        $filters = $request->input('filter', []);
        foreach ($filters as $filter) {
            $query = FilterHelper::applyFilter($query, $filter);
        }
        if ($request->filled('search')) {
            $query = $this->categoryRepository->search($query, $request->input('search'));
        }
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $sort_by = $request->input('sort_by', 'created_at');
        $order = $request->input('order', 'asc');
        $query = $this->categoryRepository->sort($query, $sort_by, $order);

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function getCategory(string $id)
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return ApiResponseHelper::resError(null, 'Category not found', 404);
        }
        return $category;
    }


    public function getCategoryProducts(string $id)
    {
        $query = $this->categoryRepository->CategoryProducts($id);
        return $query->paginate(10);
    }

    public function storeCategory(Request $request)
    {
        $validationResult = ValidationHelper::validateLoginRequest($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if (!$validationResult['success']) {
            return ApiResponseHelper::resError($validationResult['errors'], 'Validation errors', 422);
        }

        $slug = SlugHelper::generateUniqueSlug($request->name, Category::class);
        $image = $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'categories') : null;

        return $this->categoryRepository->create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'slug' => $slug,
        ]);
    }

    public function updateCategory(Request $request, string $id)
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return ApiResponseHelper::resError(null, 'Category not found', 404);
        }

        $validationResult = ValidationHelper::validateLoginRequest($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if (!$validationResult['success']) {
            return ApiResponseHelper::resError($validationResult['errors'], 'Validation errors', 422);
        }

        $slug = SlugHelper::generateUniqueSlug($request->name, Category::class);
        $image = $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'categories', $category->image) :
            $category->image;

        return $this->categoryRepository->update($id, [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'slug' => $slug,
        ]);
    }

    public function deleteCategory(string $id)
    {
        $category = $this->categoryRepository->delete($id);
        if (!$category) {
            return ApiResponseHelper::resError(null, 'Category not found', 404);
        }
        return $category;
    }

    public function getImage($image)
    {
        $filePath = 'images/categories/' . $image;
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->get($filePath);
        }
        return null;
    }
}
