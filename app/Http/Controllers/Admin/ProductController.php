<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(SearchRequest $request): JsonResponse
    {
        try {
            $filters = $request->input('filter', []);
            $search = $request->input('search');
            $sortBy = $request->input('sort_by', 'created_at');
            $order = $request->input('order', 'asc');
            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);

            return $this->productService->index(
                $this->productService->productRepository->getAllQuery(),
                $filters,
                $search,
                $sortBy,
                $order,
                $limit,
                $page
            );
        } catch (Exception $e) {
            return ApiResponseHelper::resError(['errors' => 'An error occurred while fetching products.'], $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        return $this->productService->store($request);
    }

    public function show(string $id)
    {
        return $this->productService->show($id);
    }

    public function update(Request $request, string $id)
    {
        return $this->productService->update($request, $id);
    }

    public function image($image)
    {
        return $this->productService->image($image);
    }

    public function destroy(string $id)
    {
        return $this->productService->destroy($id);
    }
}
