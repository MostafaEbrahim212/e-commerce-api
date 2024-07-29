<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponseHelper;
use App\Helpers\TokenHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\AuthenticatableRepository;
use App\Repositories\Interfaces\AuthenticatableRepositoryInterface;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    protected $authRepo;

    public function __construct(AuthenticatableRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }
    public function register(Request $request)
    {
        try {
            $validationResult = ValidationHelper::validateLoginRequest($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);
            if (!$validationResult['success']) {
                return ApiResponseHelper::resData($validationResult['errors'], 'Validation Error', 422);
            }
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ];
            $user = $this->authRepo->create($data);
            $token = $this->authRepo->generateToken($user);
            return ApiResponseHelper::resData(['token' => $token], 'User Registered Successfully', 201);
        } catch (\Exception $e) {
            return ApiResponseHelper::resData(null, $e->getMessage());
        }
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
            $user = User::where('email', $request->email)->first();
            if (!$this->authRepo->validateCredentials($user, $request->password)) {
                return ApiResponseHelper::resData(null, 'Invalid Credentials', 401);
            }
            $token = $this->authRepo->generateToken($user);
            return ApiResponseHelper::resData(['token' => $token], 'User Login Successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseHelper::resData(null, $e->getMessage(), 500);
        }
    }


    public function logout()
    {
        try {
            $user = Auth::user();
            $this->authRepo->logout($user);
            return ApiResponseHelper::resData(null, 'Logout Successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseHelper::resData(null, $e->getMessage());
        }
    }

}
