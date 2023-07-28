<div class="row mb-5 js-confirm-load-page js-confirm-row" data-confirm-onload="false">
    <div class="col-md-12">
        <div class="card mb-1 pb-2">
            <div class="card-body p-0">
                <form method="post"
                      class="js-form-estimate-discipline"
                      data-method="post"
                      data-id="{{$project?->id}}"
                      action="">
                        @csrf
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive ">
                            <table class="table table-bordered js-table-work-item-item js-font-sm">
                                <thead class="bg-primary">
                                    <tr>
                                        <th scope="col" class="text-left min-w-200">Loc/Equip</th>
                                        <th scope="col" class="text-left min-w-250">Discipline</th>
                                        <th scope="col" class="text-left min-w-250">Work Element</th>
                                        <th scope="col" class="text-left min-w-400">Work Item</th>
                                        <th scope="col" class="text-left min-w-75">Vol</th>
                                        <th scope="col" class="text-left min-w-120">Man Power Cost</th>
                                        <th scope="col" class="text-left min-w-120">Equipment Cost</th>
                                        <th scope="col" class="text-left min-w-120">Material Cost</th>
                                        <th scope="col" class="text-left min-w-100">Labor Fac</th>
                                        <th scope="col" class="text-left min-w-100">Equip Fac</th>
                                        <th scope="col" class="text-left min-w-100">Material Fac</th>
                                        <th scope="col" class="text-left min-w-50">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="js-table-body-work-item-item">
                                @php($previousWorkElement = null)
                                @foreach($estimateAllDiscipline as $key => $discipline)
                                    <tr class="js-column-location" style="background-color: #C5C5C7D0">
                                        <td class="min-w-100">
                                            <span class="float-start">
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
                                        <tr class="js-column-discipline" style="background-color: #DEDEDED0">
                                            <td></td>
                                            <td class="min-w-100 js-discipline">
                                                <span class="float-start">
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
                                                <tr class="js-column-work-element" style="background-color: #EFEFEFD0">
                                                    <td></td>
                                                    <td>

                                                    </td>
                                                    <td class="min-w-170">
                                                        <div>
                                                            <span class="float-start js-text-work-element">
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
                            <div class="js-modal-detail-estimate-template"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="col-md-12">
        <div class="float-end">
            <button class="btn btn-primary js-save-estimate-discipline" >Save As Draft</button>
            <button class="btn btn-primary js-save-estimate-discipline" >Publish</button>
        </div>
    </div>
</div>




