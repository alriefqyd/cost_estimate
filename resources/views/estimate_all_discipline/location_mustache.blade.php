<div class="card js-card-items-wbs-level-3">
    <ul class="navbar-card-custom">
        <li class="m-2 cursor-pointer js-hide-location"><i class="fa fa-chevron-circle-up "></i></li>
        <li class="m-2 cursor-pointer d-none js-show-location"><i class="fa fa-chevron-circle-down "></i></li>
        <li class="m-2 cursor-pointer js-delete-location"><i class="fa fa-trash"></i></li>
    </ul>
    <div class="card-body">
        <fieldset>
            <div class="row mb-2">
                <input type="hidden" class="js-wbs-l3-identifier"  value="{{isset($uniqueId) ? $uniqueId : (isset($wbs?->first()?->identifier) ? $wbs->first()->identifier : '')}}">
                <div class="col-md-12 mb-3">
                    <label>Type</label>
                    <select name="type[]" class="select2 form-control js-wbs-l3-type">
                        <option
                            {{isset($wbs?->first()?->type) && $wbs?->first()->type == 'location' ? 'selected' : ''}} value="location">
                            Location
                        </option>
                        <option
                            {{isset($wbs?->first()?->type) && $wbs?->first()->type == 'equipment' ? 'selected' : ''}} value="equipment">
                            Equipment
                        </option>
                    </select>
                </div>
                <label>Location/Equipment</label>
                <div class="col-md-12 mb-3">
                    <input type="text" name="locationEquipment[]"
                           value="{{isset($wbs) ? $wbs?->first()?->title : ''}}" placeholder="Location/Equipment"
                           class="form-control js-wbs-l3-location_equipment-title"/>
                </div>
                <div class="col-md-4">
                    <label>Discipline</label>
                </div>
                <div class="col-md-7">
                    <label>Work Element</label>
                </div>
                <div class="col-md-1">
                    <label>Action</label>
                </div>
            </div>
            <div class="row js-row-work-element">
                @php($existingDisciplines = isset($wbs) ? $wbs->groupBy('discipline') : null)
                @if(isset($existingDisciplines))
                    @foreach($existingDisciplines as $key => $exDiscipline)
                        @include('estimate_all_discipline.discipline_work_element',['key' => $key])
                    @endforeach
                @else
                    @include('estimate_all_discipline.discipline_work_element')
                @endif
            </div>
            <div class="col-md-7">
                <div class="btn btn-outline-primary mt-3 js-add-new-discipline-work-element"><i
                        class="fa fa-plus-circle"></i> Add New Discipline and Work Element
                </div>
            </div>
        </fieldset>
    </div>
</div>
