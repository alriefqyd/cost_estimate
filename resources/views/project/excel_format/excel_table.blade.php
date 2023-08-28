@if($isDetail)
    @include('project.excel_format.detail')
@else
    @include('project.excel_format.summary')
@endif
