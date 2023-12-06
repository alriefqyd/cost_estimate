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
use Illuminate\Support\Facades\Session;
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
        if(auth()->user()->cannot('create',User::class)){
            return view('not_authorized');
        }
        $role = Role::with('users')->get();
        $position = PROFILE::POSITION;
        return view('user.create',[
            'roles' => $role,
            'position' => $position
        ]);
    }

    public function edit(User $user, Request $request){
        if(auth()->user()->cannot('update',User::class)){
            return view('not_authorized');
        }
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
        if(auth()->user()->cannot('create',User::class)){
            abort(403);
        }

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
                'other_position' => $request->other_position,
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

            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('/user/');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('user/create')->withErrors($e->getMessage());
        }
    }

    public function update(User $user, Request $request){
        if(auth()->user()->cannot('update',User::class)){
            abort(403);
        }
        $this->validate($request, [
            Rule::unique('users')->ignore($user->user_name),
            Rule::unique('profiles')->ignore($user->profiles?->email),
            'full_name' => 'required',
            'position' => 'required',
            'role' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $user->user_name = $request->user_name;
            if(isset($request->password) && ! Hash::check($request->password, $user->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $profile = $user->profiles;
            if(!$profile) {
                $profile = new Profile();
                $profile->user_id = $user->id;
            }

            $profile->full_name = $request->full_name;
            $profile->email = $request->email;
            $profile->position = $request->position;
            $profile->other_position = $request->other_position;
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

            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('/user/');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('/user/'. $user->id)->withErrors($e->getMessage());
        }
    }

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }
}
