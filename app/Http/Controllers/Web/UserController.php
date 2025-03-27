<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repository\User\UserRepository;
use App\Service\EnumColumn;
use App\Service\FileManagerService;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;
use phpDocumentor\Reflection\Types\Void_;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected UserRepository $userRepository;
    protected FileManagerService $fileManagerService;

    public function __construct(UserRepository $userRepository, FileManagerService $fileManagerService){
        $this->userRepository = $userRepository;
        $this->fileManagerService = $fileManagerService;
    }
    public function index(){
      $users = $this->userRepository->getAllUsers();
     // $roles = EnumColumn::getEnumStages("users","roles");
      return view('user.index',['users'=> $users]);
    }
    public function store(RegisterUserRequest $request)
    {
        // Validate user input
        $fields = $request->validated();

        if(isset($fields['id_card'])){
            $file = $this->fileManagerService->uploadFile($fields['id_card']);
            $fields['id_card'] = $file['unique'];
        }

          $user = $this->userRepository->createUser($fields);
          logger('response', $user->toArray());
          if ($user) {
            return response()->json(['message'=>'User Added','data' => $user], 200);
          } else {
            return response()->json(['error' => 'User creation failed'], 500);
          }
    }

    public function create(){
        return view('user.create');
    }
    public function edit(User $user){
        $roles = $this->userRepository->getUserRole($user->user_id);
        $status = $this->userRepository->getUserStatus($user->user_id);
        return view('user.edit', [
            'user'=> $user,
            'roles'=> $roles,
            'status'=> $status,
        ]);
    }
    public function show(User $user) {
        $roles = $this->userRepository->getUserRole($user->user_id);
        return view('user.show', ['user'=>$user, 'roles'=>$roles]);
    }
    public function update(UpdateUserRequest $request, User $user){
      Log::info('Submitted status:', ['status' => $request->status]);
      $validated = $request->validated();
      $user = $this->userRepository->updateUser($validated, $user);
      return response()->json(['message' => 'User Updated'], 200);
    }
    public function destroy(User $tower){

    }
    public function getPendingUsers() {
        $rawPendingUsers = User::where('status','pending')->get();
        return view('user.pendinguser', ['users' => $rawPendingUsers]);
    }

    public function getPendingUsersAPI(){
        $rawPendingUsers = User::where('status','pending')->get();
        return response()->json(['users' => $rawPendingUsers], 200);
    }

}
