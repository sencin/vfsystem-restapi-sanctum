<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
Use App\Http\Requests\User\RegisterUserRequest;
Use App\Http\Requests\User\Auth\LoginUserRequest;
use App\Repository\User\UserRepository;
use App\Service\AuthService;
use App\Service\CheckRequestHeaderService;
use App\Service\FileManagerService;
use Illuminate\Support\Facades\Log;
class UserAuth extends Controller{

    protected UserRepository $userRepository;
    protected AuthService $authService;
    protected FileManagerService $fileManagerService;

    public function __construct(UserRepository $userRepository, AuthService $authService, FileManagerService $fileManagerService){
        $this->userRepository = $userRepository;
        $this->authService = $authService;
        $this->fileManagerService = $fileManagerService;
    }
    public function register(RegisterUserRequest $request) {
        try {
           $fields = $request->validated();
           if(isset($fields['id_card'])){
             $file = $this->fileManagerService->uploadFile($fields['id_card']);
             $fields['id_card'] = $file['unique'];
           }
           $user = $this->userRepository->createUser($fields);
           return response()->json(['message' => 'Account Pending', 'email' => $user->email, 'first_name' => $user->first_name,'last_name' => $user->last_name], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function loginToken(LoginUserRequest $request){
        try {
            $fields = $request->validated();
            $credentials = $this->authService->validateSanctumCredential($fields['email'], $fields['password']);
            return response()->json(
                [ 'message' => 'User Authenticated',
                'token' => $credentials['token'],
                'user_id'=> $credentials['user']['user_id'],
                'first_name'=> $credentials['user']['first_name'],
                'last_name'=> $credentials['user']['last_name'],
                'role'=> $credentials['user']['role'],
                ],  200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 401);
        }
    }
    public function logout(Request $request){
        if ($request->header('Accept') === 'application/json'){
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'User logged out'], 200);
        }
    }
}
