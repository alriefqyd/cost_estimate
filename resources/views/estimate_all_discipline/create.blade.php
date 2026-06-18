@extends('layouts.main')
@section('main')
    @php
        $initData = [
            'projectId'      => $project->id,
            'userId'         => auth()->user()->id,
            'userName'       => auth()->user()->profiles?->full_name ?? auth()->user()->name,
            'userDiscipline' => $userDiscipline,
            'wsUrl'          => $wsUrl,
            'rows'           => $flatRows,
            'wbsOptions'     => $wbsOptions->toArray(),
            'contingency'    => optional($project->projectSettings)->contingency ?? 15,
            'canPublish'     => $project->isDesignEngineer(),
            'isAdmin'        => $isAdmin,
            'publishStatus'  => $publishStatus,
        ];
    @endphp

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Estimate Discipline</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/project">Project List</a></li>
                        <li class="breadcrumb-item"><a href="/project/{{ $project->id }}">Project Detail</a></li>
                        <li class="breadcrumb-item active">Estimate Discipline</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-end pt-2 mt-5 mb-5">
                    <a href="/project/{{ $project->id }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Back to Project
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card est-page-header-card">
                    <div class="card-body est-page-header-body">
                        <div class="est-page-project-info">
                            <span class="est-page-project-label">Project</span>
                            <span class="est-page-project-name">{{ $project->project_title }}</span>
                        </div>
                        @if($project->wbsLevel3s())
                            <a href="/project/{{ $project->id }}/wbs/edit" class="est-wbs-btn">
                                <i class="fa fa-sitemap"></i>
                                Work Breakdown Structure
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="row">
                <div class="col-md-12 alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            </div>
        @endif

        <script>window.__ESTIMATE_INIT__ = @json($initData);</script>
        <div id="estimate-react-root" class="col-sm-12 px-0"></div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/estimate-discipline.js') }}"></script>
@endpush
