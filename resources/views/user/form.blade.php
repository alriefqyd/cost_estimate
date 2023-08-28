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
<div class="row mb-1">
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
    <div class="col-md-6">
        <label class="form-label form-label-black m-0" for="validationCustom01">Full Name</label>
        <input class="form-control js-validate js-user-full-name height-40" name="full_name"  type="text"
               value="{{isset($user?->profiles?->full_name) ? $user?->profiles?->full_name : old('full_name')}}">
    </div>
</div>

<div class="row mb-1">
    <div class="col-md-6">
        <label class="form-label form-label-black m-0" for="validationCustom01">Position</label>
        <select class="select2 form-control" name="position">
            <option value="" disabled selected>Select Position</option>
            @foreach($position as $key => $value)
                <option {{isset($user->profiles->position)
                && $user->profiles->position == $key ? 'selected="selected"' : ''}} value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
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

<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-btn-save-wbs-setting btn-outline-success">Save</button>
    </div>
</div>
