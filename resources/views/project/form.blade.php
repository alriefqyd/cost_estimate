@inject('setting',App\Models\Setting::class)
@inject('profile',App\Models\Profile::class)
<span data-tg-tour="This form is used to create a new cost estimate project and initiate the development of a cost estimate. The fields included in this form ensure that all necessary and mandatory information is collected to accurately assess.">
    <div class="row g-3 mb-3">
        <div class="col-md-6" data-tg-tour="You need to define area of project that need to develop cost estimate. Since the project area is mandatory you can't submit your project unless you fill it.">
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
        <div class="col-md-6" data-tg-tour="The Project No field is a mandatory field when creating a new cost estimate and It uniquely identifies each project. Make sure to input a valid and unique project number for each new cost estimate.">
            <label class="form-label form-label-black" for="validationCustom01">Project No <span class="text-danger f-w-550">*</span> </label>
            <input class="form-control js-validate js-project_project_no" name="project_no"  type="text"
                   autocomplete="off"
                   value="{{isset($project->project_no) ? $project->project_no : old('project_no')}}">
        </div>
        <div class="col-md-6" data-tg-tour="The Project Title field is another mandatory field when creating a new cost estimate. It provides a clear and concise name for the project">
            <label class="form-label form-label-black" for="validationCustom02">Project Title <span class="text-danger f-w-550">*</span></label>
            <input class="form-control js-validate js-project_project_title" name="project_title" type="text"
                   autocomplete="off"
                   value="{{isset($project->project_title) ? $project->project_title : old('project_title')}}">
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6" data-tg-tour="The Project Sub Title field provides additional context or specificity to the main project title. It can be used to highlight particular aspects or phases of the project, making it easier to differentiate between similar projects or stages within a single project">
            <label class="form-label form-label-black">Sub Project Title</label>
            <input class="form-control js-project_sub_project_title" name="sub_project_title" type="text"
                   autocomplete="off"
                   value="{{isset($project->project_no) ? $project->sub_project_title : old('sub_project_title')}}" >
        </div>
        <div class="col-md-6" data-tg-tour="The Project Sponsor field is a mandatory field when creating a new cost estimate. It identifies the department or area responsible for providing the financial resources and overall support for the project">
            <label class="form-label form-label-black" for="validationCustom02">Project Sponsor <span class="text-danger f-w-550">*</span></label>
            <input class="form-control js-project_project_sponsor" name="project_sponsor" type="text"
                   autocomplete="off"
                   value="{{isset($project->project_sponsor) ? $project->project_sponsor : old('project_sponsor')}}">
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6" data-tg-tour="The Project Manager field is a mandatory field when creating a new cost estimate. It identifies the person responsible for planning, executing, and closing the project. The project manager oversees the project's progress, manages the team, and ensures that the project objectives are met within the defined constraints">
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
        <div class="col-md-6" data-tg-tour="The Project Engineer field identifies the engineer responsible for the technical aspects of the project. This role involves designing, developing, and overseeing the implementation of the project's engineering components. Including this field ensures that there is a clear point of contact for technical queries and issues related to the project">
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
    <div class="row g-3 mb-2">
        <div class="col-md-6" data-tg-tour="The Design Engineer (Civil) field is included if required. This field identifies the civil engineer responsible for the design and structural aspects of the project. This role involves ensuring that the project complies with engineering standards and regulations, and it is essential for projects with significant civil engineering components.">
            <div class="mb-3 m-form__group">
                <label class="form-label form-label-black">Design Engineer Civil</label>
                <div class="row js-parent-input">
                    <div class="col-md-12">
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
                </div>
            </div>
            <label class="form-label form-label-black" for="validationCustom02"></label>
        </div>
        <div class="col-md-6" data-tg-tour="The Design Engineer (Mechanical) field is included if the project requires mechanical engineering expertise. This field identifies the mechanical engineer responsible for the design and functionality of mechanical systems within the project. It ensures that the project has the necessary expertise for any mechanical engineering needs.">
            <div class="mb-3 m-form__group">
                <label class="form-label form-label-black">Design Engineer Mechanical</label>
                <div class="row js-parent-input">
                    <div class="col-md-12">
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
                </div>
            </div>
            <label class="form-label form-label-black" for="validationCustom02"></label>
        </div>
    </div>
    <div class="row g-3 mb-5">
        <div class="col-md-6">
            <div class="mb-3 m-form__group" data-tg-tour="The Design Engineer (Electrical) field is included if the project requires electrical engineering expertise. This field identifies the electrical engineer responsible for designing and overseeing electrical systems and components of the project. It ensures that the project has the necessary expertise for electrical design and implementation.">
                <label class="form-label form-label-black">Design Engineer Electrical</label>
                <div class="row js-parent-input">
                    <div class="col-md-12">
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
                </div>
            </div>
            <label class="form-label form-label-black" for="validationCustom02"></label>
        </div>
        <div class="col-md-6">
            <div class="mb-3 m-form__group" data-tg-tour="The Design Engineer (Instrument) field is included if the project requires instrumentation engineering expertise. This field identifies the engineer responsible for designing and overseeing the instrumentation systems and controls within the project. It ensures that the project has the necessary expertise for any specialized instrumentation needs.">
                <label class="form-label form-label-black">Design Engineer Instrument</label>
                <div class="row js-parent-input">
                    <div class="col-md-12">
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
                </div>
            </div>
            <label class="form-label form-label-black" for="validationCustom02"></label>
        </div>
    </div>
</span>

