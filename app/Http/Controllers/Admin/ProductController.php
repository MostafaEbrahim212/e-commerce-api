<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Helpers\NotFoundHelper;
use App\Helpers\SlugHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use App\Traits\UploadsImages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;

class ProductController extends Controller
{
    use UploadsImages;

    private $productRepository;
    public function __construct(CrudRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    public function index(SearchRequest $request)
    {
        try {
            $query = $this->productRepository->getAllQuery();

            if ($request->has('name')) {
                $query = $this->productRepository->search($query, $request->name);
            }

            $sort_by = $request->get('sort_by', 'created_at');
            $order = $request->get('order', 'asc');
            $query = $this->productRepository->sort_by($query, $sort_by, $order);

            if ($request->has('filter')) {
                $query = $this->productRepository->filter($query, $request->filter, $request->value);
            }

            if ($request->has('status')) {
                $query = $this->productRepository->status($query, $request->status);
            }

            // Execute the query with pagination if requested
            $products = $query->paginate(10);
            return ApiResponseHelper::resData([
                'total' => $products->count(),
                'products' => ProductResource::collection($products),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
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
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'category_id' => 'required|exists:categories,id',
            ]);
            if (!$validationResult['success']) {
                return ApiResponseHelper::resData($validationResult['errors'], 'Validation errors', 422);
            }
            if ($request->hasFile('image')) {
                $image = $this->uploadImage($request->file('image'), 'products');
            }
            $product = $this->productRepository->create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $image,
                'category_id' => $request->category_id,
            ]);
            $product = new ProductResource($product);
            return ApiResponseHelper::resData(['product' => $product], 'Product created successfully', 201);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }


    public function show(string $id)
    {
        try {
            $product = $this->productRepository->find($id);
            $product = new ProductResource($product);
            $notFound = NotFoundHelper::checkNotFound($product, 'Product not found', );
            if ($notFound) {
                return $notFound;
            }
            return ApiResponseHelper::resData(['product' => $product], 'Product fetched successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return ApiResponseHelper::resData(null, 'Product not found', 404);
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
                return ApiResponseHelper::resData($validationResult['errors'], 'Validation errors', 422);
            }

            if ($request->hasFile('image')) {
                $image = $this->uploadImage($request->file('image'), 'products', $product->image);
            }

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
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
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

        return response()->json(['error' => 'Image not found'], 404);
    }


    public function destroy(string $id)
    {
        try {
            $product = $this->productRepository->delete($id);
            if (!$product) {
                return ApiResponseHelper::resData(null, 'Product not found', 404);
            }
            return ApiResponseHelper::resData(null, 'Product deleted successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(['errors' => $e->getMessage()], 'An error occurred', 500);
        }
    }
}
