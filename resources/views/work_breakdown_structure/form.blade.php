<form method="post" action="store"
      class="js-form-wbs-estimate-discipline"
      data-id="{{$project->id}}"
      data-url="/project/{{$project->id}}/wbs/{{isset($existingWbs) ? 'update' : 'store'}}">
    <div class="js-form-list-location">
        @csrf
        @if(isset($existingWbs))
            @foreach($existingWbs as $wbs)
                @include('work_item.location_mustache', ['wbs' => $wbs])
            @endforeach
        @else
            @include('work_item.location_mustache')
        @endif

    </div>
    <div class="btn btn-outline-primary float-end cursor-pointer-white js-add-location_equipment mb-5">
        <i class="fa fa-plus-circle"></i> Add New Location/Equipment
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="card">
            <div class="col-md-12 m-10">
                <button class="btn btn-success js-form-list-location-submit">
                    <div class="loader-box" style="height: auto">
                        Save <div style="margin-left:3px" class="loader-34 d-none"></div>
                    </div>
                </button>
                <button class="btn btn-success">Cancel</button>

            </div>
        </div>
    </div>
</form>

