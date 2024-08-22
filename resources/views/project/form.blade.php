@inject('setting',App\Models\Setting::class)
@inject('profile',App\Models\Profile::class)
    <h5 class="font-weight-bold">Project Details</h5>
    <hr>
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Area <span class="text-danger f-w-550">*</span></label>
            <select class="select2 col-sm-12 js-project-area"
                    name="project_area"
                    data-placeholder="Project Area">
                @foreach($departments as $department)
                    <option {{isset($project->project_area_id)
                        && $project->project_area_id == $department->id ? 'selected' : ''}}
                            value="{{$department->id}}">{{$department->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom01">Project No <span class="text-danger f-w-550">*</span> </label>
            <input class="form-control js-validate js-project_project_no" name="project_no"  type="text"
                   autocomplete="off"
                   value="{{isset($project->project_no) ? $project->project_no : old('project_no')}}">
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Title <span class="text-danger f-w-550">*</span></label>
            <input class="form-control js-validate js-project_project_title" name="project_title" type="text"
                   autocomplete="off"
                   value="{{isset($project->project_title) ? $project->project_title : old('project_title')}}">
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <label class="form-label form-label-black">Sub Project Title</label>
            <input class="form-control js-project_sub_project_title" name="sub_project_title" type="text"
                   autocomplete="off"
                   value="{{isset($project->project_no) ? $project->sub_project_title : old('sub_project_title')}}" >
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Sponsor <span class="text-danger f-w-550">*</span></label>
            <input class="form-control js-project_project_sponsor" name="project_sponsor" type="text"
                   autocomplete="off"
                   value="{{isset($project->project_sponsor) ? $project->project_sponsor : old('project_sponsor')}}">
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Manager <span class="text-danger f-w-550">*</span></label>
            <select class="select2 form-control js-design-engineer js-project_project_manager"
                    data-allowClear="true"
                    data-placeholder="Select Project Manager"
                    data-url="/getUserEmployee"
                    data-subject="project_manager"
                    data-minimumInputLength="3"
                    name="project_manager" >
                @if(isset($project->project_manager))
                    <option value="{{$project->project_manager}}">
                        {{$project->projectManager?->profiles?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Engineer <span class="text-danger f-w-550">*</span></label>
            <select class="select2 form-control js-design-engineer js-project_project_engineer"
                    data-allowClear="true"
                    data-url="/getUserEmployee"
                    data-subject="project_engineer"
                    data-placeholder="Select Project Engineer"
                    data-minimumInputLength="3"
                    name="project_engineer" >
                @if(isset($project->project_engineer))
                    <option value="{{$project->project_engineer}}">
                        {{$project->projectEngineer?->profiles?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
    </div>
    <h5 class="font-weight-bold mt-5">Design Engineers</h5>
    <hr>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <label class="form-label form-label-black">Design Engineer Civil</label>
            <select class="select2 form-control js-design-engineer js-project_engineer_civil"
                    data-allowClear="true"
                    data-url="/getUserEmployee"
                    data-placeholder="Select Design Engineer Civil"
                    data-subject="design_civil_engineer"
                    name="design_engineer_civil" >
                @if(isset($project->design_engineer_civil))
                    <option value="{{$project->design_engineer_civil}}">
                        {{$project->designEngineerCivil?->profiles?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
        <div class="col-md-6 js-reviewer-engineer {{isset($project->design_engineer_mechanical) ? '' : 'd-none'}}">
            <label class="form-label form-label-black">Review Engineer Civil</label>
            <select class="select2 form-control js-reviewer-engineer-select"
                    data-allowClear="true"
                    data-placeholder="Select Reviewer Engineer Civil"
                    data-subject="civil"
                    data-minimumInputLength="3"
                    name="reviewer_civil" >
                @if(isset($project->design_engineer_civil))
                    <option value="{{$project->design_engineer_civil}}">
                        {{$project->getProfileUser($project->civil_approver)?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6 mb-2">
            <label class="form-label form-label-black">Design Engineer Mechanical</label>
            <select class="select2 form-control js-design-engineer js-project_engineer_mechanical"
                    data-allowClear="true"
                    data-url="/getUserEmployee"
                    data-placeholder="Select Design Engineer Mechanical"
                    data-subject="design_mechanical_engineer"
                    data-minimumInputLength="3"
                    name="design_engineer_mechanical" >
                @if(isset($project->design_engineer_mechanical))
                    <option value="{{$project->design_engineer_mechanical}}">
                        {{$project->designEngineerMechanical?->profiles?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
        <div class="col-md-6 js-reviewer-engineer {{isset($project->design_engineer_mechanical) ? '' : 'd-none'}}">
            <label class="form-label form-label-black">Review Engineer Mechanical</label>
            <select class="select2 form-control js-reviewer-engineer-select"
                    data-allowClear="true"
                    data-placeholder="Select Reviewer Engineer Mechanical"
                    data-subject="mechanical"
                    data-minimumInputLength="3"
                    name="reviewer_mechanical" >
                @if(isset($project->design_engineer_mechanical))
                    <option value="{{$project->design_engineer_mechanical}}">
                        {{$project->getProfileUser($project->mechanical_approver)?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <label class="form-label form-label-black">Design Engineer Electrical</label>
            <select class="select2 form-control js-design-engineer js-project_engineer_electrical"
                    data-allowClear="true"
                    data-url="/getUserEmployee"
                    data-placeholder="Select Design Engineer Electrical"
                    data-subject="design_electrical_engineer"
                    data-minimumInputLength="3"
                    name="design_engineer_electrical" >
                @if(isset($project->design_engineer_electrical))
                    <option value="{{$project->design_engineer_electrical}}">
                        {{$project->designEngineerElectrical?->profiles?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
        <div class="col-md-6 js-reviewer-engineer {{isset($project->design_engineer_electrical) ? '' : 'd-none'}}">
            <label class="form-label form-label-black">Reviewer Engineer Electrical</label>
            <select class="select2 form-control js-reviewer-engineer-select"
                    data-allowClear="true"
                    data-placeholder="Select Reviewer Engineer Electrical"
                    data-subject="electrical"
                    data-minimumInputLength="3"
                    name="reviewer_electrical" >
                @if(isset($project->design_engineer_electrical))
                    <option value="{{$project->design_engineer_electrical}}">
                        {{$project->getProfileUser($project->electrical_approver)?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
    </div>
    <div class="row g-3 mb-5">
        <div class="col-md-6">
            <label class="form-label form-label-black">Design Engineer Instrument</label>
            <select class="select2 form-control js-design-engineer js-project_engineer_instrument"
                    data-allowClear="true"
                    data-url="/getUserEmployee"
                    data-placeholder="Select Design Engineer Instrument"
                    data-subject="design_instrument_engineer"
                    data-minimumInputLength="3"
                    name="design_engineer_instrument" >
                @if(isset($project->design_engineer_instrument))
                    <option value="{{$project->design_engineer_instrument}}">
                        {{$project->designEngineerInstrument?->profiles?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
        <div class="col-md-6 js-reviewer-engineer {{isset($project->design_engineer_instrument) ? '' : 'd-none'}}">
            <label class="form-label form-label-black">Reviewer Engineer Instrument</label>
            <select class="select2 form-control js-reviewer-engineer-select"
                    data-allowClear="true"
                    data-placeholder="Select Reviewer Engineer Instrument"
                    data-subject="instrument"
                    data-minimumInputLength="3"
                    name="reviewer_instrument">
                @if(isset($project->design_engineer_instrument))
                    <option value="{{$project->design_engineer_instrument}}">
                        {{$project->getProfileUser($project->instrument_approver)?->full_name}}
                    </option>
                @endif
                <option value="{{NULL}}">NR</option>
            </select>
        </div>
    </div>


