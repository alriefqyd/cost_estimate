<div class="modal-detail">
{{--    @foreach($workItem as $item)--}}
        <div class="modal fade js-modal-detail-work-item-" id="materialsModal_" tabindex="-1" role="dialog" aria-labelledby="materialsModal_Label" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Material</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Unit Price (Rp)</th>
                            <th>Amount (Rp)</th>
                            </thead>
                            <tbody>
{{--                            @foreach($item?->workItems?->materials as $tools)--}}
{{--                                <tr>--}}
{{--                                    <td>{{ $tools?->tool_equipment_description }}</td>--}}
{{--                                    <td>{{ $tools?->pivot?->unit }}</td>--}}
{{--                                    <td>{{ number_format($tools?->pivot?->quantity,2) }}</td>--}}
{{--                                    <td>{{ $workItemController->toCurrency($tools?->pivot?->unit_price) }}</td>--}}
{{--                                    <td>{{ $workItemController->toCurrency($tools?->pivot?->amount) }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
                            <tr>
                                <td>
                                    <select class="select2 js-select-item-additional"
                                            data-url="/getWorkItems"
                                            style="max-width: 100% !important;">
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-secondary" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade js-modal-detail-work-item-" id="toolsEquipmentsModal_" tabindex="-1" role="dialog" aria-labelledby="materialsModal_Label" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Equipment Tool</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Unit Price (Rp)</th>
                            <th>Amount (Rp)</th>
                        </thead>
                        <tbody>
{{--                        @foreach($item?->workItems?->equipmentTools as $tools)--}}
{{--                            <tr>--}}
{{--                                <td>{{ $tools->description }}</td>--}}
{{--                                <td>{{ $tools?->pivot?->unit }}</td>--}}
{{--                                <td>{{ number_format($tools?->pivot?->quantity,2) }}</td>--}}
{{--                                <td>{{ $workItemController->toCurrency($tools?->pivot?->unit_price) }}</td>--}}
{{--                                <td>{{ $workItemController->toCurrency($tools?->pivot?->amount) }}</td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
                        </tbody>
                    </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-secondary" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade js-modal-detail-work-item-" id="manPowersModal_" role="dialog" aria-labelledby="materialsModal_Label" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Man Power</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body js-modal-work-item">
                        <table class="table table-striped">
                            <thead>
                            <th>Title</th>
                            <th>Unit</th>
                            <th>Coef</th>
                            <th>Rate (Rp)</th>
                            <th>Amount (Rp)</th>
                            </thead>
                            <tbody>
{{--                            @foreach($item?->workItems?->manPowers as $manPower)--}}
{{--                                @php($labor_coef = $workItemController->toDecimalRound($manPower?->pivot?->labor_coefisient))--}}
{{--                                @php($rateManPower = $manPower?->overall_rate_hourly)--}}
{{--                                <tr>--}}
{{--                                    <td>{{$manPower->title}}</td>--}}
{{--                                    <td>{{$manPower?->pivot?->labor_unit}}</td>--}}
{{--                                    <td>{{$labor_coef}}</td>--}}
{{--                                    <td>{{$workItemController->toCurrency($rateManPower)}}</td>--}}
{{--                                    <td>{{$workItemController->toCurrency((float) $rateManPower * (float) $labor_coef)}}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-secondary" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
{{--    @endforeach--}}
</div>