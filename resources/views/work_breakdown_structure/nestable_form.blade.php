
@if(isset($disciplineList))
    @foreach($disciplineList as $disciplines)
        <li class="dd-item" data-id="{{$disciplines?->id}}">
            <div class="dd-handle">
                <div class="float-start col-md-10 dd-nodrag">
                    {{$disciplines?->title}}
                </div>
                <div class="float-end">
                        <span class="js-add-new-nestable-wbs dd-nodrag">
                            <i data-feather="plus-circle"></i>
                        </span>
                    <span class="cursor-pointer text-danger js-delete-wbs-discipline dd-nodrag">
                        <i data-feather="x"></i>
                    </span>
                </div>
            </div>
        </li>
    @endforeach
@endif

