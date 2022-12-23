{{--
!!!!!!!!
add work element and discipline first
input work item based work element and discipline
like canon site behaviour

discipline is max two in condition cost-estimate just one disciplene (general and own discipline)
discipline possible just only one data

work element created based on discipline
work element is not mandatory

work item created based on work element (if exist) or discipline

top of page is create discipline and work element



--}}

{{-- estimate discipline form will be input by each engineer subject , so there will be query to select the data first, if not exist will be create new one
else will be select and update the data --}}
{{--<form class="f1" method="post">--}}
{{--    <div class="f1-steps">--}}
{{--        <div class="f1-progress">--}}
{{--            <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3"></div>--}}
{{--        </div>--}}
{{--        <div class="f1-step active">--}}
{{--            <div class="f1-step-icon"><i class="fa fa-user"></i></div>--}}
{{--            <p>Discipline</p>--}}
{{--        </div>--}}
{{--        <div class="f1-step">--}}
{{--            <div class="f1-step-icon"><i class="fa fa-key"></i></div>--}}
{{--            <p>Work Element</p>--}}
{{--        </div>--}}
{{--        <div class="f1-step">--}}
{{--            <div class="f1-step-icon"><i class="fa fa-twitter"></i></div>--}}
{{--            <p>Work Item</p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <fieldset>--}}
{{--        <div class="form-group">--}}
{{--            <div class="mb-2">--}}
{{--                <select class="js-example-placeholder-multiple col-sm-12" multiple="multiple">--}}
{{--                    <option value="opt1">General</option>--}}
{{--                    <option value="opt2">Mechanical</option>--}}
{{--                    <option value="opt3">Civil</option>--}}
{{--                    <option value="opt4">Electrical</option>--}}
{{--                    <option value="opt5">Instrument</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="f1-buttons">--}}
{{--            <button class="btn btn-primary btn-next" type="button">Next</button>--}}
{{--        </div>--}}
{{--    </fieldset>--}}
{{--    <fieldset>--}}
{{--        <div class="row mb-4">--}}
{{--            <div class="default-according" id="accordion">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header" id="headingOne">--}}
{{--                        <h5 class="mb-0">--}}
{{--                            <button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
{{--                                General--}}
{{--                            </button>--}}
{{--                        </h5>--}}
{{--                    </div>--}}
{{--                    <div class="collapse show" id="collapseOne" aria-labelledby="headingOne" data-bs-parent="#accordion" style="">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="table-responsive mb-2">--}}
{{--                                <table class="table table-striped">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th scope="col" class="col-10">Title</th>--}}
{{--                                        <th scope="col" class="col-2 text-center">Action</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center"><i class="fa fa-trash"></i> </td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center"><i class="fa fa-trash"></i> </td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center"><i class="fa fa-trash"></i> </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <button class="btn btn-outline-secondary btn-sm pl-2 mb-2 col-lg-3" type="button"><i class="fa fa-plus"></i> Add Work Element</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header" id="headingTwo">--}}
{{--                        <h5 class="mb-0">--}}
{{--                            <button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">--}}
{{--                                Mechanical--}}
{{--                            </button>--}}
{{--                        </h5>--}}
{{--                    </div>--}}
{{--                    <div class="collapse" id="collapseTwo" aria-labelledby="headingTwo" data-bs-parent="#accordion">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="table-responsive mb-2">--}}
{{--                                <table class="table table-striped">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th scope="col" class="col-10">Title</th>--}}
{{--                                        <th scope="col" class="col-2 text-center">Action</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center"><i class="fa fa-trash"></i> </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <button class="btn btn-outline-secondary btn-sm pl-2 mb-2 col-lg-3" type="button"><i class="fa fa-plus"></i> Add Work Element</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <ul class="uk-nestable" data-uk-nestable>--}}
{{--                <li class="uk-nestable-item">--}}
{{--                    <div class="uk-nestable-panel" id="0"> 1 </div>--}}
{{--                </li>--}}
{{--                <li class="uk-nestable-item">--}}
{{--                    <div class="uk-nestable-panel" id="1"> 2 </div>--}}
{{--                </li>--}}
{{--                <li class="uk-nestable-item">--}}
{{--                    <div class="uk-nestable-panel" id="2"> 3 </div>--}}
{{--                </li>--}}
{{--                <li class="uk-nestable-item">--}}
{{--                    <div class="uk-nestable-panel" id="3"> 4 </div>--}}
{{--                </li>--}}
{{--                <li class="uk-nestable-item">--}}
{{--                    <div class="uk-nestable-panel" id="4"> 5 </div>--}}
{{--                </li>--}}
{{--                <li class="uk-nestable-item">--}}
{{--                    <div class="uk-nestable-panel" id="5"> 6 </div>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--        <div class="f1-buttons">--}}
{{--            <button class="btn btn-primary btn-previous" type="button">Previous</button>--}}
{{--            <button class="btn btn-primary btn-next" type="button">Next</button>--}}
{{--        </div>--}}
{{--    </fieldset>--}}
{{--    <fieldset>--}}
{{--        <div class="row mb-4">--}}
{{--            <div class="default-according" id="accordion">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header" id="headingOne">--}}
{{--                        <h5 class="mb-0">--}}
{{--                            <button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">--}}
{{--                                Work Element 1--}}
{{--                            </button>--}}
{{--                        </h5>--}}
{{--                    </div>--}}
{{--                    <div class="collapse show" id="collapseOne" aria-labelledby="headingOne" data-bs-parent="#accordion" style="">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="table-responsive mb-2">--}}
{{--                                <table class="table table-striped">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th scope="col" class="col-8">Title</th>--}}
{{--                                        <th scope="col" class="col-2">Volume</th>--}}
{{--                                        <th scope="col" class="col-2 text-center">Action</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center"><i class="fa fa-trash"></i> </td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center"><i class="fa fa-trash"></i> </td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center"><i class="fa fa-trash"></i> </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <button class="btn btn-outline-secondary btn-sm pl-2 mb-2 col-lg-3" type="button"><i class="fa fa-plus"></i> Add Work Item</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header" id="headingTwo">--}}
{{--                        <h5 class="mb-0">--}}
{{--                            <button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">--}}
{{--                                Work Element 2--}}
{{--                            </button>--}}
{{--                        </h5>--}}
{{--                    </div>--}}
{{--                    <div class="collapse" id="collapseTwo" aria-labelledby="headingTwo" data-bs-parent="#accordion">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="table-responsive mb-2">--}}
{{--                                <table class="table table-striped">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th scope="col" class="col-8">Title</th>--}}
{{--                                        <th scope="col" class="col-2">Volume</th>--}}
{{--                                        <th scope="col" class="col-2 text-center">Action</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            <input class="form-control js-project_sub_project_title" id="validationCustom02" type="text" value="" required="">--}}
{{--                                        </td>--}}
{{--                                        <td class="text-center"><i class="fa fa-trash"></i> </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <button class="btn btn-outline-secondary btn-sm pl-2 mb-2 col-lg-3" type="button"><i class="fa fa-plus"></i> Add Work Item</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        <div class="f1-buttons">--}}
{{--            <button class="btn btn-primary btn-previous" type="button">Previous</button>--}}
{{--            <button class="btn btn-primary btn-next" type="button">Next</button>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    </fieldset>--}}
{{--    <fieldset>--}}
{{--        summary--}}
{{--        this fieldset will be table summary--}}

{{--        <a href="">Generate Summary</a> redirect to cost estimate detail--}}
{{--    </fieldset>--}}
{{--</form>--}}




{{--<form class="needs-validation" novalidate="">--}}
{{--    <div class="element_row mb-3">--}}
{{--        <div class="row mb-2">--}}
{{--            <label>Discipline</label>--}}
{{--            <div class="col-md-10">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--                <div class="valid-feedback">Looks good!</div>--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <div class="dropdown">--}}
{{--                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-target="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                        Add Item--}}
{{--                    </button>--}}
{{--                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                        <a class="dropdown-item"><i class="fa fa-plus-circle"></i> Work Element</a>--}}
{{--                        <a class="dropdown-item"><i class="fa fa-plus-circle"></i> Work Item</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <label class="offset-md-1">Work Element</label>--}}
{{--            <div class="col-md-9 mb-1 offset-md-1">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--                <div class="valid-feedback">Looks good!</div>--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <button class="btn btn-secondary p-1"><i class="fa fa-plus-circle"></i> Work Item</button>--}}
{{--                <button class="btn btn-danger p-1"><i class="fa fa-trash"></i></button>--}}
{{--            </div>--}}

{{--            --}}{{-- Work Item Start --}}
{{--            <div class="col-md-8 mb-2 mt-2 offset-md-2">--}}
{{--                <label>Work Item</label>--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <br><br>--}}
{{--                <button class="btn btn-danger p-1"><i class="fa fa-trash"></i></button>--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-1 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <button class="btn btn-danger p-1"><i class="fa fa-trash"></i></button>--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-1 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            --}}{{-- work item end --}}

{{--            <div class="col-md-9 mb-1 offset-md-1">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--                <div class="valid-feedback">Looks good!</div>--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <button class="btn btn-primary p-1"><i class="fa fa-plus-circle"></i> Item</button>--}}
{{--            </div>--}}

{{--            --}}{{-- Work Item Start --}}
{{--            <div class="col-md-8 mb-2 mt-2 offset-md-2">--}}
{{--                <label>Work Item</label>--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-1 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-1 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            --}}{{-- work item end --}}

{{--            <div class="col-md-9 mb-1 offset-md-1">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--                <div class="valid-feedback">Looks good!</div>--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <button class="btn btn-primary p-1"><i class="fa fa-plus-circle"></i> Item</button>--}}
{{--            </div>--}}

{{--            --}}{{-- Work Item Start --}}
{{--            <div class="col-md-8 mb-2 mt-2 offset-md-2">--}}
{{--                <label>Work Item</label>--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            --}}{{-- work item end --}}
{{--        </div>--}}
{{--        <hr>--}}
{{--    </div>--}}
{{--    <div class="element_row mb-3">--}}
{{--        <div class="row mb-2">--}}
{{--            <div class="col-md-10">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--                <div class="valid-feedback">Looks good!</div>--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <div class="dropdown">--}}
{{--                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-target="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                        Add Item--}}
{{--                    </button>--}}
{{--                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                        <a class="dropdown-item"><i class="fa fa-plus-circle"></i> Work Element</a>--}}
{{--                        <a class="dropdown-item"><i class="fa fa-plus-circle"></i> Work Item</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            --}}{{-- Work Item Start --}}
{{--            <div class="col-md-8 mb-2 mt-2 offset-md-2">--}}
{{--                <label>Work Item</label>--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-1 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-1 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-2 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-1 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            <div class="col-md-8 mb-2 mt-1 offset-md-2">--}}
{{--                <input class="form-control" id="validationCustom01" type="text" value="Mark" required="">--}}
{{--            </div>--}}
{{--            --}}{{-- work item end --}}

{{--        </div>--}}
{{--    </div>--}}

{{--    <button class="btn btn-outline-secondary btn-sm pl-2 mt-3 mb-2 col-lg-3" type="button"><i class="fa fa-plus"></i> Add Discipline</button>--}}
{{--    <button class="btn btn-primary mt-5 float-end" type="submit">Submit form</button>--}}
{{--</form>--}}


{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>--}}

<div class="card">
    <div class="card-body">
        <div class="mb-2">
            <label class="col-form-label">Select Discipline</label>
            <select name="work_scope" class="js-example-basic-single col-sm-12">
                <option value="general">General</option>
                <option value="electrical">Electrical</option>
                <option value="instrument">Instrument</option>
                <option value="mechanical">Mechanical</option>
                <option value="civil">Civil</option>
            </select>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Work Element</a></li>
            <li class="nav-item"><a class="nav-link" id="profile-tabs" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Work Item</a></li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="col-md-12 mt-5">
                    <div class="table-responsive mb-5">
                        <table class="table js-work-element-table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="col-11">Title</th>
                                    <th scope="col" class="col-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="js-work-element-input-column">
                                    <td>
                                        <input class="form-control typeahead form-control tt-input js-input-work-element-idx-0
                                                       js-work-element-input" type="text"
                                               name="work_element[]"
                                               placeholder="Work Element" autocomplete="off"
                                               spellcheck="false" dir="auto" style="position: relative; vertical-align: top;">
                                    </td>
                                    <td class="text-center"><i class="fa fa-trash-o js-delete-work-element text-danger text-20" data-idx="0"></i></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="float-end text-12 cursor-pointer js-add-work-element"><i class="fa fa-plus-circle"></i> Add new work element</div>
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Save Work Element</button>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="col-md-12 mt-5">
                    <div class="table-responsive mb-2">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" class="col-8">Title</th>
                                <th scope="col" class="col-2 text-center">Vol</th>
                                <th scope="col" class="col-3 text-center">Work Element</th>
                                <th scope="col" class="col-1 text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input class="form-control typeahead form-control tt-input" type="text"
                                           placeholder="Work Element" autocomplete="off"
                                           spellcheck="false" dir="auto" style="position: relative; vertical-align: top;">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Vol" aria-label="Vol">
                                        <span class="input-group-text">
                                            Kg
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <select class="js-example-basic-single col-sm-12">
                                        <option value="WY">General</option>
                                        <option value="WY">Electrical</option>
                                        <option value="WY">Instrument</option>
                                        <option value="WY">Mechanical</option>
                                        <option value="WY">Civil</option>
                                    </select>
                                </td>
                                <td class="">
                                    <i class="fa fa-plus-circle pl-3"> </i>
                                </td>
                                <td class=""><i class="fa fa-trash-o"></i></td>
                            </tr>
                            <tr>
                                <td>
                                    <input class="form-control typeahead form-control tt-input" type="text"
                                           placeholder="Work Element" autocomplete="off"
                                           spellcheck="false" dir="auto" style="position: relative; vertical-align: top;">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Vol" aria-label="Vol">
                                        <span class="input-group-text">
                                            Kg
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <select class="js-example-basic-single col-sm-12">
                                        <option value="WY">Empty</option>
                                        <option value="WY">General</option>
                                        <option value="WY">Electrical</option>
                                        <option value="WY">Instrument</option>
                                        <option value="WY">Mechanical</option>
                                        <option value="WY">Civil</option>
                                    </select>
                                </td>
                                <td class="">
                                    <i class="fa fa-plus-circle pl-3"> </i>
                                </td>
                                <td class=""><i class="fa fa-trash-o"></i></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-5 float-end">
                    <button class="btn btn-primary">Save As Draft</button>
                    <a href="/cost-estimate/detail"><button class="btn btn-primary">Publish</button></a>
                </div>
            </div>
        </div>
    </div>
</div>





