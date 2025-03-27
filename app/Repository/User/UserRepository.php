<?php

namespace App\Repository\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Service\EnumColumn;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
class UserRepository{
    public function getAllUsers(){
        return User::get();
    }
    public function getUserById($user){
        return User::findOrFail($user);;
    }
    public function createUser(array $data){
        $user = new User($data);
        $user->password = bcrypt($data['password']);
        $user->save();
        return $user;
    }
    public function updateUser(array $data, User $user){
        try {
            if ($user->update($data)) {
                return true;
            }
            Log::warning('Failed to update user.', ['user_id' => $user->user_id]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error updating user:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    public function deleteUser(User $user){
        $username = $user->user_name;
        $user->delete();
        return response()->json([
            'message' => "User $username is Deleted"
        ], 200);
    }
    public function getUserbyEmail(string $email){
        return User::where('email', $email)->first();
    }

    public function getUserRole($userId){
       $stages = EnumColumn::getEnumStages("users","role");
       $currentUser = $this->getUserById($userId);
       $existingRoles = [$currentUser->role];
       $remainingRoles = array_diff($stages, $existingRoles);
       return $remainingRoles;
    }
    public function getUserStatus($userId){
        $status = EnumColumn::getEnumStages("users","status");
        $currentUser = $this->getUserById($userId);
        $existingStatus = [$currentUser->status];
        $remainingStatus = array_diff($status, $existingStatus);
        return $remainingStatus;
     }
}
