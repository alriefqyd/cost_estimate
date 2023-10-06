@inject('setting',App\Models\Setting::class)
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Code</label>
        <input class="form-control js-validate js-project_project_no height-40 js-confirm-form" name="code"  type="text"
               value="{{isset($man_power?->code) ? $man_power->code : old('code')}}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">Skill Level</label>
        <select class="select2 js-confirm-form"
                data-allowClear="true"
                name="skill_level" >
            @foreach($setting::SKILL_LEVEL as $key => $value)
                <option {{isset($man_power?->skill_level) && $man_power?->skill_level == $key ? 'selected' : ''}} value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Title</label>
        <input class="form-control js-validate js-project_project_no js-confirm-form height-40" name="title"  type="text"
               value="{{isset($man_power?->title) ? $man_power->title : old('title')}}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">Basic Rate Month</label>
        <input class="form-control js-validate height-40 js-currency-idr js-confirm-form js-basic-rate-monthly"
               data-safety-rate="{{$man_power_safety_rate}}"
               name="basic_rate_month" type="text"
               value="{{isset($man_power?->basic_rate_month) ? number_format($man_power->basic_rate_month, 2, ',', '.') : old('basic_rate_month')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Basic Rate Hourly</label>
        <input class="form-control js-validate height-40 js-currency js-basic-rate-hourly"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               name="basic_rate_hour"  type="text"
               value="{{isset($man_power?->basic_rate_hour) ? $man_power->basic_rate_hour : old('basic_rate_hour')}}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">General Allowance</label>
        <input class="form-control js-validate height-40 js-currency js-general-allowance"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               name="general_allowance"  type="text"
               value="{{isset($man_power?->general_allowance) ? $man_power->general_allowance : old('general_allowance')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">BPJS</label>
        <input class="form-control js-validate height-40 js-currency js-bpjs" name="bpjs"  type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->bpjs) ? $man_power->bpjs : old('bpjs')}}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">BPJS Kesehatan</label>
        <input class="form-control js-validate height-40 js-currency js-bpjs-kesehatan"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               name="bpjs_kesehatan" type="text"
               value="{{isset($man_power?->bpjs_kesehatan) ? $man_power->bpjs_kesehatan : old('bpjs_kesehatan')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">THR</label>
        <input class="form-control js-validate height-40 js-currency js-thr" name="thr" type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->thr) ? $man_power->thr : old('thr')}}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">Public Holiday</label>
        <input class="form-control js-validate height-40 js-currency js-public-holiday" name="public_holiday" type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->public_holiday) ? $man_power->public_holiday : old('public_holiday')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Leave</label>
        <input class="form-control js-validate height-40 js-currency js-leave"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               name="leave" type="text"
               value="{{isset($man_power?->leave) ? $man_power->leave : old('leave')}}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">Pesangon</label>
        <input class="form-control js-validate height-40 js-currency js-pesangon" name="pesangon" type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->pesangon) ? $man_power->pesangon : old('pesangon')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Asuransi</label>
        <input class="form-control js-validate height-40 js-currency js-asuransi" name="asuransi" type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->asuransi) ? $man_power->asuransi : old('asuransi')}}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">Safety</label>
        <input class="form-control js-validate height-40 js-currency js-safety" name="safety" type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->safety) ? $man_power->safety : old('safety')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Total Benefit Hourly</label>
        <input class="form-control js-validate height-40 js-currency js-total-benefit-hourly" name="total_benefit_hourly"  type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->total_benefit_hourly) ? $man_power->total_benefit_hourly : old('total_benefit_hourly')}}">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">Overall Rate Hourly</label>
        <input class="form-control js-validate height-40 js-currency js-overall-rate-hourly" name="overall_rate_hourly"  type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->overall_rate_hourly) ? $man_power->overall_rate_hourly : old('overall_rate_hourly')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Monthly</label>
        <input class="form-control js-validate height-40 js-currency js-factor-hourly" name="monthly" type="text"
               {{!auth()->user()->isManPowerReviewer() ? 'readonly' : ''}}
               value="{{isset($man_power?->monthly) ? $man_power->monthly : old('monthly')}}">
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-save-confirm-form js-btn-save-man-power btn-outline-success">Save</button>
    </div>
</div>
