<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Repositories\AuthenticatableRepository;
use App\Repositories\Interfaces\AuthenticatableRepositoryInterface;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{

    protected $authRepo;
    public function __construct(AuthenticatableRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }


    public function login(Request $request)
    {
        try {
            $validationResult = ValidationHelper::validateLoginRequest($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if (!$validationResult['success']) {
                return ApiResponseHelper::resData($validationResult['errors'], 'Validation Error', 422);
            }
            $admin = $this->authRepo->findByEmail($request->email);
            if (!$this->authRepo->validateCredentials($admin, $request->password)) {
                return ApiResponseHelper::resData(null, 'Invalid Credentials', 401);
            }
            $token = $admin->createToken('adminToken')->plainTextToken;
            return ApiResponseHelper::resData(['token' => $token], 'Admin Login Successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(null, $e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authRepo->logout($request->user());
            return ApiResponseHelper::resData(null, 'Logout Successfully', 200);
        } catch (Exception $e) {
            return ApiResponseHelper::resData(null, $e->getMessage());
        }
    }
}
