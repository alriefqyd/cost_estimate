@inject('setting',App\Models\Setting::class)
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom01">Project No</label>
            <input class="form-control js-validate js-project_project_no" name="project_no"  type="text"
                   value="{{old('project_no')}}">
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Title</label>
            <input class="form-control js-validate js-project_project_title" name="project_title" type="text"
                   value="{{old('project_title')}}">
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <label class="form-label form-label-black">Sub Project Title</label>
            <input class="form-control js-project_sub_project_title" name="sub_project_title" type="text"
                   value="{{old('sub_project_title')}}" >
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Sponsor</label>
            <input class="form-control js-project_project_sponsor" name="project_sponsor" type="text"
                   value="{{old('project_sponsor')}}">
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Manager</label>
            <input class="form-control js-project_project_manager" name="project_manager" type="text"
                   value="{{old('project_manager')}}">
        </div>
        <div class="col-md-6">
            <label class="form-label form-label-black" for="validationCustom02">Project Engineer</label>
            <input class="form-control js-project_project_engineer" name="project_engineer" type="text"
                   value="{{old('project_engineer')}}">
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <div class="mb-3 m-form__group">
                <label class="form-label form-label-black">Design Engineer Civil</label>
                <div class="row js-parent-input">
                    <div class="col-md-12">
                        <select class="select2-ajax form-control js-design-engineer js-project_engineer_civil"
                                data-allowClear="true"
                                data-url="/getUserEmployee"
                                data-subject="{{$setting::DESIGN_ENGINEER_LIST['civil']}}"
                                name="design_engineer_civil" >
                            <option value="{{NULL}}">NR</option>
                        </select>
                    </div>
                </div>
            </div>
            <label class="form-label form-label-black" for="validationCustom02"></label>
        </div>
        <div class="col-md-6">
            <div class="mb-3 m-form__group">
                <label class="form-label form-label-black">Design Engineer Mechanical</label>
                <div class="row js-parent-input">
                    <div class="col-md-12">
                        <select class="select2 form-control js-design-engineer js-project_engineer_mechanical"
                                data-allowClear="true"
                                data-url="/getUserEmployee"
                                data-subject="{{$setting::DESIGN_ENGINEER_LIST['mechanical']}}"
                                data-minimumInputLength="3"
                                name="design_engineer_mechanical" >
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
            <div class="mb-3 m-form__group">
                <label class="form-label form-label-black">Design Engineer Electrical</label>
                <div class="row js-parent-input">
                    <div class="col-md-12">
                        <select class="select2 form-control js-design-engineer js-project_engineer_electrical"
                                data-allowClear="true"
                                data-url="/getUserEmployee"
                                data-subject="{{$setting::DESIGN_ENGINEER_LIST['electrical']}}"
                                data-minimumInputLength="3"
                                name="design_engineer_electrical" >
                            <option value="{{NULL}}">NR</option>
                        </select>
                    </div>
                </div>
            </div>
            <label class="form-label form-label-black" for="validationCustom02"></label>
        </div>
        <div class="col-md-6">
            <div class="mb-3 m-form__group">
                <label class="form-label form-label-black">Design Engineer Instrument</label>
                <div class="row js-parent-input">
                    <div class="col-md-12">
                        <select class="select2 form-control js-design-engineer js-project_engineer_instrument"
                                data-allowClear="true"
                                data-url="/getUserEmployee"
                                data-subject="{{$setting::DESIGN_ENGINEER_LIST['instrument']}}"
                                data-minimumInputLength="3"
                                name="design_engineer_instrument" >
                            <option value="{{NULL}}">NR</option>
                        </select>
                    </div>
                </div>
            </div>
            <label class="form-label form-label-black" for="validationCustom02"></label>
        </div>
    </div>


