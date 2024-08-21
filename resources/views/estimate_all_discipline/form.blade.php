<div class="row mb-2 js-confirm-load-page js-confirm-row font-arial" data-confirm-onload="false">
    <div class="col-md-6">
        <div class="btn btn-primary js-fullscreen mb-2">Maximize Table <i data-feather="maximize" style="width: 12px !important; padding-top: 5px !important;"></i></div>
    </div>
    <div class="col-md-6">
        <div class="btn btn-outline-success js-btn-loading-sync float-end">
            Sync
            <div class="notification-ring d-none"></div>
        </div>
    </div>
    <span class="js-fullscreen-element">
        <div class="col-md-12">
            <div class="card mb-1 pb-2">
                <div class="card-body p-0">
                    <form method="post"
                          class="js-form-estimate-discipline"
                          data-method="post"
                          data-id="{{$project?->id}}"
                          action="">
                            @csrf
                        <input type="hidden" class="js-version-project-estimate" value="{{$version}}">
                        <div class="table-responsive col-sm-12 col-lg-12 col-xl-12 table-overflow">
                            <div class="table-custom table-container">
                                <table class="table table-custom">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th scope="col" class="text-left min-w-110 bg-primary">Loc / Equip</th>
                                            <th scope="col" class="text-left min-w-110 bg-primary">Discipline</th>
                                            <th scope="col" class="text-left min-w-100 bg-primary">Work Element</th>
                                            <th scope="col" class="text-left min-w-250 bg-primary">Work Item</th>
                                            <th scope="col" class="text-left min-w-30 bg-primary">Vol</th>
                                            <th scope="col" class="text-left min-w-110 bg-primary">Man Power</th>
                                            <th scope="col" class="text-left min-w-110 bg-primary">Equipment</th>
                                            <th scope="col" class="text-left min-w-110 bg-primary">Material</th>
                                            <th scope="col" class="text-left min-w-65 bg-primary">Labor Fac</th>
                                            <th scope="col" class="text-left min-w-65 bg-primary">Equip. Fac</th>
                                            <th scope="col" class="text-left min-w-65 bg-primary">Material Fac</th>
                                            <th scope="col" class="text-left min-w-65 bg-primary">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="js-table-body-work-item-item">
                                    @php($previousWorkElement = null)
                                    @foreach($estimateAllDiscipline as $key => $discipline)
                                        <tr class="js-column-location" style="background-color: #C5C5C7D0" data-key="{{$key}}">
                                            <td class="min-w-100">
                                                <span class="float-start f-w-500 f-12">
                                                        {{ucwords(strtolower($key))}}
                                                    </span>
                                                <div class="d-inline-block float-end">
                                                    <i class="fa fa-chevron-up js-minimize cursor-pointer"></i>
                                                    <i class="fa fa-chevron-down js-maximize cursor-pointer d-none"></i>
                                                </div>
                                            </td>
                                            <td colspan="10"></td>
                                            <td></td>
                                        </tr>
                                        @foreach($discipline as $k => $workElement)
                                            <tr class="js-column-discipline"
                                                style="background-color: #DEDEDED0">
                                                <td></td>
                                                <td class="js-discipline min-w-120">
                                                    <span class="float-start f-w-500">
                                                        {{ucwords(strtolower($k))}}
                                                    </span>
                                                    <div class="d-inline-block float-end">
                                                        <i class="fa fa-chevron-up js-minimize cursor-pointer"></i>
                                                        <i class="fa fa-chevron-down js-maximize cursor-pointer d-none"></i>
                                                    </div>
                                                </td>
                                                <td colspan="9"></td>
                                                <td></td>
                                            </tr>
                                            @foreach($workElement as $a => $b)
                                                @php ($wbsId = isset($b?->id) ? $b->id : $b[0]->wbs_level3_id)
                                                @php ($workElement = isset($b?->work_element) ? $b->work_element : $b[0]->work_element_id)
{{--                                                @if($a !== $previousWorkElement)--}}
                                                    <tr class="js-column-work-element"
                                                        data-wbs-level3-id="{{$wbsId}}"
                                                        style="background-color: #EFEFEFD0">
                                                        <td></td>
                                                        <td>

                                                        </td>
                                                        <td class="min-w-160">
                                                            <div>
                                                                <span class="float-start js-text-work-element f-w-500">
                                                                    {{$a}}
                                                                </span>
                                                                <div class="d-inline-block float-end m-l-5">
                                                                    <i class="fa fa-chevron-up js-minimize cursor-pointer"></i>
                                                                    <i class="fa fa-chevron-down js-maximize cursor-pointer d-none"></i>
                                                                    <i class="fa fa-plus-circle cursor-pointer font-success js-add-work-item-element js-button-work-element"
                                                                       data-id="{{$wbsId}}" data-work-element="{{$workElement}}"
                                                                    ></i>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="8"></td>
                                                        <td></td>
                                                    </tr>
{{--                                                @endif--}}
                                                @foreach($b as $item)
                                                    @if(isset($item->workItemId))
                                                        @include('estimate_all_discipline.work_item_row',
                                                                [
                                                                    'item' => $item,
                                                                    'wbsId' => $wbsId,
                                                                    'workElement' => $workElement
                                                                ])
                                                    @endif
                                                @endforeach
                                                @php($previousWorkElement = $a)
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                    <tr class="f-w-700">
                                        <td colspan="10"><p>Contingency</p></td>
                                        <td>
                                             <div class="input-group">
                                                <input class="form-control js-input-contingency" style="height:40px" type="number" placeholder="Contingency"
                                                       @if(isset($project->projectSettings->contingency))
                                                           value="{{$project->projectSettings->contingency}}"
                                                       @else
                                                           value="15"
                                                       @endif
                                                       {{!isset($item) ? 'disabled="disabled"' : '' }}
                                                       aria-label="Vol">
                                                <span class="input-group-text font f-w-500 f-15" style="font-size: 10px">
                                                   %
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="js-work-item-total-contingency">{{number_format($project->getContingencyCost(),2,',','.')}}</p>
                                        </td>
                                    </tr>
                                    <tr class="f-w-700">
                                        <td colspan="11">Total</td>
                                        <td>
                                            <p class="js-total-cost-estimate">{{number_format($project->getTotalCostWithContingency(),2,',','.')}}</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="js-modal-detail-estimate-template"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="float-end">
                <a href="/project/{{$project->id}}"><button class="btn btn-danger js-btn-cancel-estimate-form cancel mb-4" >Back</button></a>
                <button class="btn btn-secondary js-save-estimate-discipline mb-4" data-status="DRAFT">Save As Draft
                <div class="loader-box float-end d-none js-loading-save" style="height: 1px; width: 50px; margin-top: 7%">
                    <div class="loader-15"></div>
                </div>
                </button>
                @if($project->estimate_discipline_status == "DRAFT")
                    <button class="btn btn-primary js-save-estimate-discipline mb-4" data-status="MODAL">Publish
                    <div class="loader-box float-end d-none js-loading-save" style="height: 1px; width: 50px; margin-top: 7%">
                        <div class="loader-15"></div>
                    </div>
                    </button>
                @endif
            </div>
        </div>
    </span>
</div>

@if($project->estimate_discipline_status == "DRAFT")
    <div class="modal fade js-modal-confirm-publish" id="publishEstimateDiscipline" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Publish Estimate Discipline</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to publish estimate discipline, once you publish you cannot update it anymore?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success js-save-estimate-discipline" data-status="PUBLISH" type="button">Publish</button>
                </div>
            </div>
        </div>
    </div>
@endif

@include('layouts.loading')




