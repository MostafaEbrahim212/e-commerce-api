<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Helpers\NotFoundHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Addresses;
class UsersController extends Controller
{
    public function index(Request $request)
    {
        try {
            $validationResult = ValidationHelper::validateLoginRequest($request, ['search' => 'nullable|string']);
            if (!$validationResult['success']) {
                return ApiResponseHelper::resData($validationResult['errors'], 'Validation error', 400);
            }
            $searchValue = $request->search;
            $usersQuery = User::search($searchValue);
            $users = $usersQuery->paginate(10);
            $users = UserResource::collection($users);
            $message = $users->isEmpty() ? 'No users found' : 'Users fetched successfully';
            return ApiResponseHelper::resData([
                'total' => $users->total(),
                'users' => $users,
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
            ], $message, 200);
        } catch (\Exception $e) {
            return ApiResponseHelper::resError($e->getMessage(), 'Error fetching users', 500);
        }
    }
    public function show($id)
    {
        try {
            $user = User::find($id);
            $notFound = NotFoundHelper::checkNotFound($user, 'User not found');
            if ($notFound) {
                return $notFound;
            }
            $user = new UserResource($user);
            return ApiResponseHelper::resData($user, 'User fetched successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseHelper::resError($e->getMessage(), 'Error fetching user', 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::find($id);
            $notFound = NotFoundHelper::checkNotFound($user, 'User not found');
            if ($notFound) {
                return $notFound;
            }
            $user->status = !$user->status;
            $message = $user->status ? 'activated' : 'deactivated';
            $user->save();
            return ApiResponseHelper::resData(null, 'User ' . $message . ' successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseHelper::resError($e->getMessage(), 'Error toggling user status', 500);
        }
    }
}
