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
                                            <th scope="col" class="text-left min-w-200 bg-primary">Loc/Equip</th>
                                            <th scope="col" class="text-left min-w-250 bg-primary">Discipline</th>
                                            <th scope="col" class="text-left min-w-250 bg-primary">Work Element</th>
                                            <th scope="col" class="text-left min-w-400 bg-primary">Work Item</th>
                                            <th scope="col" class="text-left min-w-75 bg-primary">Vol</th>
                                            <th scope="col" class="text-left min-w-150 bg-primary">Man Power Cost</th>
                                            <th scope="col" class="text-left min-w-150 bg-primary">Equipment Cost</th>
                                            <th scope="col" class="text-left min-w-120 bg-primary">Material Cost</th>
                                            <th scope="col" class="text-left min-w-100 bg-primary">Labor Fac</th>
                                            <th scope="col" class="text-left min-w-100 bg-primary">Equip Fac</th>
                                            <th scope="col" class="text-left min-w-100 bg-primary">Material Fac</th>
                                            <th scope="col" class="text-left min-w-65 bg-primary">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="js-table-body-work-item-item">
                                    @php($previousWorkElement = null)
                                    @foreach($estimateAllDiscipline as $key => $discipline)
                                        <tr class="js-column-location" style="background-color: #C5C5C7D0" data-key="{{$key}}">
                                            <td class="min-w-100">
                                                <span class="float-start f-w-700 f-14">
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
                                                <td class="min-w-100 js-discipline">
                                                    <span class="float-start f-w-700">
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
                                                @if($a !== $previousWorkElement)
                                                    <tr class="js-column-work-element"
                                                        data-wbs-level3-id="{{$wbsId}}"
                                                        style="background-color: #EFEFEFD0">
                                                        <td></td>
                                                        <td>

                                                        </td>
                                                        <td class="min-w-170">
                                                            <div>
                                                                <span class="float-start js-text-work-element f-w-700">
                                                                    {{$a}}
                                                                </span>
                                                                <div class="d-inline-block float-end">
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
                                                @endif
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
                <button class="btn btn-primary js-save-estimate-discipline mb-4">Save As Draft
                <div class="loader-box float-end d-none js-loading-save" style="height: 1px; width: 50px; margin-top: 7%">
                    <div class="loader-15"></div>
                </div>
                </button>
            </div>
        </div>
    </span>
</div>


<div class="modal" id="modal-loading" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="loading-spinner mb-2"></div>
                <div>Loading....</div>
            </div>
        </div>
    </div>
</div>




