<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
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
            return ApiResponseHelper::resData([
                'total' => $users->total(),
                'users' => $users,
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
            ], 'Users fetched successfully', 200);

        } catch (\Exception $e) {
            return ApiResponseHelper::resData($e->getMessage(), 'Error fetching users', 500);
        }
    }



    public function show($id)
    {
        try {
            $user = User::find($id);
            $user = new UserResource($user);
            return ApiResponseHelper::resData($user, 'User fetched successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseHelper::resData($e->getMessage(), 'Error fetching user', 500);
        }
    }
}
