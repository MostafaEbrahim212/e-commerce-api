<?php

namespace App\Services;

use App\Helpers\ApiResponseHelper;
use App\Helpers\FilterHelper;
use App\Helpers\NotFoundHelper;
use App\Helpers\SlugHelper;
use App\Helpers\ValidationHelper;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use App\Traits\UploadsImages;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;

class ProductService
{
    public $productRepository;
    use UploadsImages;

    public function __construct(CrudRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index($query, $filters, $search, $sortBy, $order, $limit, $page)
    {
        try {
            foreach ($filters as $filter) {
                $query = FilterHelper::applyFilter($query, $filter);
            }

            if ($search) {
                $query = $this->productRepository->search($query, $search);
            }

            $query = $this->productRepository->sort($query, $sortBy, $order);

            $products = $query->paginate($limit, ['*'], 'page', $page);
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
            return ApiResponseHelper::resError(['errors' => 'An error occurred while fetching products.'], $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validationResult = ValidationHelper::validateLoginRequest($request, [
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'category_id' => 'required|exists:categories,id',
            ]);

            if (!$validationResult['success']) {
                return ApiResponseHelper::resError($validationResult['errors'], 'Validation errors', 422);
            }

            $image = $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'products') : null;

            $product = $this->productRepository->create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $image,
                'category_id' => $request->category_id,
            ]);

            return ApiResponseHelper::resData(['product' => $product], 'Product created successfully', 201);
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }

    public function show(string $id)
    {
        try {
            $product = $this->productRepository->find($id);
            $notFound = NotFoundHelper::checkNotFound($product, 'Product not found');
            if ($notFound) {
                return $notFound;
            }

            return ApiResponseHelper::resData(['product' => new ProductResource($product)], 'Product fetched successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                return ApiResponseHelper::resError(null, 'Product not found', 404);
            }

            $validationResult = ValidationHelper::validateLoginRequest($request, [
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'category_id' => 'required|exists:categories,id',
            ]);

            if (!$validationResult['success']) {
                return ApiResponseHelper::resError($validationResult['errors'], 'Validation errors', 422);
            }

            $image = $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'products', $product->image) : $product->image;

            $product = $this->productRepository->update($id, [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $image,
                'category_id' => $request->category_id,
            ]);

            return ApiResponseHelper::resData(['product' => $product], 'Product updated successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }

    public function image($image)
    {
        $filePath = 'images/products/' . $image;

        if (Storage::disk('public')->exists($filePath)) {
            return Response::make(Storage::disk('public')->get($filePath), 200, [
                'Content-Type' => Storage::disk('public')->mimeType($filePath)
            ]);
        }

        return ApiResponseHelper::resError(null, 'Image not found', 404);
    }

    public function destroy(string $id)
    {
        try {
            $product = $this->productRepository->find($id);
            $notFound = NotFoundHelper::checkNotFound($product, 'Product not found');
            if ($notFound) {
                return $notFound;
            }

            $this->productRepository->delete($id);
            return ApiResponseHelper::resData(null, 'Product deleted successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }
}
