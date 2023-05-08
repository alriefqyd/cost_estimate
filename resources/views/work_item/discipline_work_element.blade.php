<div class="row js-form-row-discipline">
    <div class="col-md-3 mb-2">
        <select class="select2 js-select-discipline form-control js-wbs-l3-discipline" name="discipline[]">
            @if(isset($disciplineList))
                @foreach($disciplineList as $k => $item)
                    <option {{isset($key) && $key == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->title}}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="col-md-8 mb-2">
        <select class="select2 js-select-work-element form-control js-wbs-l3-work_element"
                data-url="/getWorkElement" name="workElement[]" multiple="multiple">
            @if(isset($exDiscipline))
                @foreach($exDiscipline as $workElement)
                    @if(($workElement->work_element) != 'null')
                        <option value="{{$workElement->workElements?->id}}" selected>{{$workElement->workElements?->title}}</option>
                    @endif
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-1 mb-2">
        <button class="btn btn-danger js-remove-form-row-discipline" style="height: 40px">
            <i class="fa fa-trash"></i>
        </button>
    </div>
</div>

