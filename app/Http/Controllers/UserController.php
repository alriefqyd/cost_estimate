<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use App\Services\UserServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->userService = new UserServices();
    }

    public function getUserEmployee(Request $request){
        return response()->json($this->userService->getUserEmployee($request));
    }

    public function index(){
        $users = User::with(['profiles'])->filter(request(['q']))->paginate(20)->withQueryString();
        $authorization = Gate::inspect('viewAny', User::class);
        if(!$authorization->allowed()){
            return view('not_authorized');
        }
        return view('user.index',[
            'users' => $users
        ]);
    }

    public function create(){
        $role = Role::with('users')->get();
        $position = PROFILE::POSITION;
        return view('user.create',[
            'roles' => $role,
            'position' => $position
        ]);
    }

    public function edit(User $user, Request $request){
        $role = Role::with(['users'])->get();
        $position = PROFILE::POSITION;
        $existingRole = [];

        foreach($user->roles as $item){
            array_push($existingRole, $item?->pivot?->role_id);
        }

        return view('user.edit',[
            'user' => $user,
            'roles' => $role,
            'existingRole' => $existingRole,
            'position' => $position
        ]);
    }

    public function store(Request $request){

        $this->validate($request,[
            'email' => 'required|unique:profiles|email',
            'user_name' => 'required|unique:users',
            'full_name' => 'required',
            'position' => 'required',
            'role' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $user = new User([
                'user_name' => $request->user_name,
                'password' => Hash::make($request->password)
            ]);
            $user->save();
            $profile = new Profile([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'position' => $request->position,
            ]);

            $user->profiles()->save($profile);
            $roles = $request->role;

            $pivotData = [];
            foreach ($roles as $roleId) {
                $pivotData[$roleId] = [
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            $user->roles()->attach($pivotData);
            DB::commit();
            return redirect('/user/');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('user/create')->withErrors($e->getMessage());
        }
    }

    public function update(User $user, Request $request){
        $this->validate($request, [
            Rule::unique('users')->ignore($user->user_name),
            Rule::unique('profiles')->ignore($user->profiles->email),
            'full_name' => 'required',
            'position' => 'required',
            'role' => 'required',
        ]);

        if(!auth()->user()->can('update',$user)){
            return view('not_authorized');
        }

        DB::beginTransaction();
        try{
            $user->user_name = $request->user_name;
            if(isset($request->password) && ! Hash::check($request->password, $user->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $profile = $user->profiles;
            $profile->full_name = $request->full_name;
            $profile->email = $request->email;
            $profile->position = $request->position;
            $profile->save();

            $roles = $request->role;

            $pivotData = [];
            foreach ($roles as $roleId) {
                $pivotData[$roleId] = [
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            $user->roles()->sync($pivotData);
            DB::commit();
            return redirect('/user/');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('/user/'. $user->id)->withErrors($e->getMessage());
        }

    }
}
