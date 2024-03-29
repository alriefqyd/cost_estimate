@inject('setting',App\Models\Setting::class)
<div class="row mb-1">
    <div class="col-md-6">
        <label class="form-label form-label-black m-0" for="validationCustom01">Email</label>
        <input class="form-control js-validate js-user-email height-40" name="email"  type="email"
               value="{{isset($user?->profiles?->email) ? $user?->profiles?->email : old('email')}}">
    </div>
    <div class="col-md-6">
        <label class="form-label form-label-black m-0" for="validationCustom01">User Name</label>
        <input class="form-control js-validate js-user-name height-40" name="user_name"  type="text"
               autocomplete="off"
               value="{{isset($user?->user_name) ? $user?->user_name : old('user_name')}}">
    </div>
</div>
@if($isEdit)
<div class="row mb-1">
    <div class="col-md-6">
        <label class="form-label form-label-black m-0" for="validationCustom01">Full Name</label>
        <input class="form-control js-validate js-user-full-name height-40" name="full_name"  type="text"
               value="{{isset($user?->profiles?->full_name) ? $user?->profiles?->full_name : old('full_name')}}">
    </div>
</div>
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="checkbox checkbox-dark m-squar">
                <input id="js-update_password_check" name="updatePassword" type="checkbox">
                <label class="mt-0" for="js-update_password_check">Change Password</label>
            </div>
        </div>
    </div>

    <div class="row mb-1 js-update-password d-none">
        <div class="col-md-6">
            <label class="form-label form-label-black m-0" for="validationCustom01">Current Password</label>
            <div class="input-group">
                <input class="form-control js-validate js-password js-user-password height-40" name="old_password"  type="password"
                       autocomplete="off"
                       value="">
                <div class="input-group-text pt-2">
                    <i class="fa fa-eye cursor-pointer js-show-hide-password"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-black m-0" for="validationCustom01">New Password</label>
            <div class="input-group">
                <input class="form-control js-validate js-password js-user-password height-40" name="new_password"  type="password"
                       autocomplete="off"
                       value="">
                <div class="input-group-text pt-2">
                    <i class="fa fa-eye cursor-pointer js-show-hide-password"></i>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row mb-1">
        <div class="col-md-6">
            <label class="form-label form-label-black m-0" for="validationCustom01">Full Name</label>
            <input class="form-control js-validate js-user-full-name height-40" name="full_name"  type="text"
                   value="{{isset($user?->profiles?->full_name) ? $user?->profiles?->full_name : old('full_name')}}">
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-black m-0" for="validationCustom01">Password</label>
            <div class="input-group">
                <input class="form-control js-validate js-password js-user-password height-40" name="password"  type="password"
                       autocomplete="off"
                       value="">
                <div class="input-group-text pt-2">
                    <i class="fa fa-eye cursor-pointer js-show-hide-password"></i>
                </div>
            </div>
        </div>
    </div>
@endif


@if(isset($isUserHaveAccess) && $isUserHaveAccess == true)
<div class="row mb-1">
    <div class="col-md-6">
        <label class="form-label form-label-black m-0" for="validationCustom01">Position</label>
        <select class="select2 form-control js-select-user-position" name="position">
            <option value="" disabled selected>Select Position</option>
            @foreach($position as $key => $value)
                <option {{isset($user->profiles->position)
                && $user->profiles->position == $key ? 'selected="selected"' : ''}} value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 js-other-position-form {{isset($user) && $user->profiles?->position == 'others' ? '' : 'd-none'}}">
        <label class="form-label form-label-black m-0">Others Position</label>
        <input type="text" class="form-control height-40" name="other_position" value="{{isset($user) ? $user?->profiles?->other_position : old('other_position')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-12">
        <select class="dual-list form-control" multiple="multiple" name="role[]" title="roles[]">
            @foreach($roles as $role)
                <option {{isset($existingRole) && collect($existingRole)->contains($role->id) ? 'selected="selected"':'' }} value="{{$role->id}}">{{$role->name}}</option>
            @endforeach
        </select>
    </div>
</div>
@endif
<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-btn-save-wbs-setting btn-outline-success">Save</button>
    </div>
</div>
