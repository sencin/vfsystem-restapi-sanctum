<?php
 namespace App\Service;
 use App\Models\User;
 use Illuminate\Support\Facades\Hash;
 use App\Repository\User\UserRepository;
 use Illuminate\Validation\ValidationException;
 use Illuminate\Support\Facades\Auth;

 class AuthService{

    protected UserRepository $userRepository;
    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    private function validateUser(string $email, string $password){
        $account =  $this->userRepository->getUserbyEmail($email);
        switch($account->status){
            case "pending":
                throw ValidationException::withMessages([
                    'email' => 'The provided email is pending.',
                ]);
                break;
            case "rejected":
                throw ValidationException::withMessages([
                    'email' => 'The provided email is rejected.',
                ]);
                break;
            case "inactive":
                throw ValidationException::withMessages([
                    'email' => 'The provided email is inactive.',
                ]);
                break;
        }

        if (!Hash::check($password, $account->password)) {
            throw ValidationException::withMessages([
                'password' => 'Incorrect input credentials. Try Again',
            ]);
        }
        return $account;
    }

    public function validateAuthCredential(string $email, string $password){
      $account = $this->validateUser($email, $password);
      Auth::login($account);
    }

    public function validateSanctumCredential(string $email, string $password){
      $account = $this->validateUser($email, $password);
      $token = $account->createToken($account->email);
      return ['token' => $token->plainTextToken, 'user'=>$account];
    }
 }
