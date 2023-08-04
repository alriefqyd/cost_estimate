<form method="post" action="store"
      class="js-form-wbs-estimate-discipline"
      data-id="{{$project->id}}"
      data-url="/project/{{$project->id}}/wbs/{{isset($existingWbs) ? 'update' : 'store'}}">
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">

                <div class="col-md-12 mb-5">
                    <div class="input-group">
                        <input class="form-control js-form-location" type="text" placeholder="Location Equipment">
                        <div class="btn btn-success js-add-btn-wbs">+</div>
                    </div>
                </div>
                <div class="dd js-nestable-wbs-form" style="max-width: 100%" id="nestable">
                    <div class="row">
                         <div class="col-md-1 text-12-custom">Loc/Equip</div>
                        <div class="col-md-1 text-12-custom">Discipline</div>
                        <div class="col-md-2 text-12-custom">Work Element</div>
                    </div>
                <ol class="dd-list js-nestable-wbs js-get-idx" data-idx="1">
                    @if(isset($existingWbs))
                       @foreach($existingWbs as $key => $value)
                            <li class="dd-item" data-id="{{$key}}">
                                <div class="dd-handle">
                                    <div class="float-start col-md-10">
                                        {{$key}}
                                    </div>
                                    <div class="float-end">
                                        <span class="js-add-new-nestable-wbs" data-is-element="false">
                                           <i data-feather="plus-circle"></i>
                                        </span>
                                        <span class="cursor-pointer text-danger js-delete-wbs-discipline">
                                            <i data-feather="x"></i>
                                        </span>
                                    </div>
                                </div>
                                @foreach($value as $k => $discipline)
                                    <ol class="dd-list js-get-idx" data-idx="2">
                                        <li class="dd-item" data-identifier="{{$discipline->first()['identifier']}}" data-id="{{$discipline->first()['disciplineId']}}">
                                            <div class="dd-handle">
                                                <div class="float-start col-md-10 js-dd-handle-edit">
                                                    <span class="js-dd-title">{{$k}}</span>
                                                </div>
                                                <div class="float-end">
                                                    <span class="js-add-new-nestable-wbs" data-is-element="true">
                                                        <i data-feather="plus-circle"></i>
                                                    </span>

                                                    <span class="cursor-pointer text-danger js-delete-wbs-discipline">
                                                        <i data-feather="x"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="js-dd-select d-none">
                                                <select class="select2 js-select-update-element js-select-update-discipline">
                                                  @foreach($disciplineList as $disc)
                                                      <option {{$discipline->first()['disciplineId'] == $disc->id ? 'selected="selected"' : ''}}
                                                              value="{{$disc->id}}">{{$disc->title}}</option>
                                                  @endforeach
                                                </select>
                                            </span>
                                            @foreach($discipline as $d)
                                                <ol class="dd-list" data-idx="2">
                                                    <li class="dd-item" data-id="{{$d['title']}}" data-old-element="{{$d['title']}}">
                                                        <div class="dd-handle">
                                                            <div class="float-start col-md-10 cursor-text p-1 js-dd-handle-edit">
                                                                <div class="js-dd-title-text js-dd-title-element" contenteditable="true">{{$d['title']}}</div>
                                                            </div>
                                                            <div class="float-end">
                                                                <span class="cursor-pointer text-danger js-delete-wbs-discipline">
                                                                    <i data-feather="x"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ol>
                                            @endforeach
                                        </li>
                                    </ol>
                                @endforeach
                            </li>
                           @endforeach
                    @endif
                </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="float-end">
        <div class="col-md-12 m-10">
            <a class="" href="/project/{{$project->id}}/">
                <div class="btn btn-danger">Cancel</div>
            </a>
            <button class="btn btn-success js-form-list-location-submit">
                <div class="loader-box" style="height: auto">
                    Save <div style="margin-left:3px" class="loader-34 d-none"></div>
                </div>
            </button>
        </div>
    </div>
</form>

<div class="modal js-modal-loading-wbs" id="modal-loading" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="loading-spinner mb-2"></div>
                <div>Loading....</div>
            </div>
        </div>
    </div>
</div>



