@extends('layouts.main')
@section('main')

<div class="db-wrap">

    {{-- ── Greeting ──────────────────────────────────────── --}}
    <div class="db-greeting" id="tour-home-greeting">
        <div>
            <h4>
                @php $hour = \Carbon\Carbon::now()->format('G'); @endphp
                @if($hour < 12)
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-3px; margin-right:6px;"><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="4"/><line x1="12" y1="20" x2="12" y2="22"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="2" y1="12" x2="4" y2="12"/><line x1="20" y1="12" x2="22" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>Good Morning
                @elseif($hour < 17)
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-3px; margin-right:6px;"><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="4"/><line x1="12" y1="20" x2="12" y2="22"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="2" y1="12" x2="4" y2="12"/><line x1="20" y1="12" x2="22" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>Good Afternoon
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-3px; margin-right:6px;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>Good Evening
                @endif
                , {{ explode(' ', auth()->user()->profiles?->full_name ?? auth()->user()->user_name)[0] }}
            </h4>
            <p>{{ \Carbon\Carbon::now()->format('l, d F Y') }} &nbsp;·&nbsp; PT Vale Indonesia, Tbk — Engineering &amp; Project Services</p>
        </div>
        @canAny(['viewAny','create'], App\Models\Project::class)
            @can('create', App\Models\Project::class)
                <a href="/project/create" class="btn btn-primary btn-sm px-3" style="border-radius:8px; font-weight:600;">
                    <i class="fa fa-plus me-1"></i> New Project
                </a>
            @endcan
        @endcanany
    </div>

    {{-- ── Hero Banner ───────────────────────────────────── --}}
    <div class="db-hero mb-4">
        <div class="db-hero-overlay"></div>
        <div class="db-hero-content">
            <div class="db-hero-logo">
                <img src="{{ asset('assets/images/Vale_logo.svg') }}" alt="Vale" style="height:36px; filter:brightness(0) invert(1);">
            </div>
            <div>
                <h5 class="db-hero-title">Web Cost Estimate</h5>
                <p class="db-hero-sub">Engineering &amp; Project Services &nbsp;·&nbsp; PT Vale Indonesia, Tbk</p>
                <p class="db-hero-tagline">&ldquo; every count matters &rdquo;</p>
            </div>
        </div>
    </div>

    {{-- ── KPI Stats ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4" id="tour-home-kpi">

        @canAny(['viewAny','view'], App\Models\Project::class)
        @php $projPct = $projectTotal > 0 ? round(($projectApproved / $projectTotal) * 100) : 0; @endphp
        <div class="col-xl col-lg-4 col-sm-6">
            <div class="kpi-card" style="--kpi-color:#2e75b6; --kpi-bg:#e8f0fb;">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="kpi-label">Projects</div>
                    <div class="kpi-icon"><i class="fa fa-folder-open"></i></div>
                </div>
                <div class="kpi-value counter">{{ $projectTotal }}</div>
                <div class="kpi-pills">
                    <span class="kpi-pill pill-draft">Draft {{ $projectDraft }}</span>
                    <span class="kpi-pill pill-approved">Approved {{ $projectApproved }}</span>
                </div>
                <div class="kpi-progress"><div class="kpi-progress-bar" style="width:{{ $projPct }}%"></div></div>
            </div>
        </div>
        @endcanany

        @canAny(['viewAny','view'], App\Models\WorkItem::class)
        @php $wiPct = $workItemTotal > 0 ? round(($workItemReviewed / $workItemTotal) * 100) : 0; @endphp
        <div class="col-xl col-lg-4 col-sm-6">
            <div class="kpi-card" style="--kpi-color:#ED7D31; --kpi-bg:#fdeee3;">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="kpi-label">Work Items</div>
                    <div class="kpi-icon"><i class="fa fa-briefcase"></i></div>
                </div>
                <div class="kpi-value counter">{{ $workItemTotal }}</div>
                <div class="kpi-pills">
                    <span class="kpi-pill pill-draft">Draft {{ $workItemDraft }}</span>
                    <span class="kpi-pill pill-reviewed">Reviewed {{ $workItemReviewed }}</span>
                </div>
                <div class="kpi-progress"><div class="kpi-progress-bar" style="width:{{ $wiPct }}%"></div></div>
            </div>
        </div>
        @endcanany

        @canAny(['viewAny','view'], App\Models\ManPower::class)
        @php $mpPct = $manPowerTotal > 0 ? round(($manPowerReviewed / $manPowerTotal) * 100) : 0; @endphp
        <div class="col-xl col-lg-4 col-sm-6">
            <div class="kpi-card" style="--kpi-color:#548235; --kpi-bg:#e6f2df;">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="kpi-label">Man Power</div>
                    <div class="kpi-icon"><i class="fa fa-users"></i></div>
                </div>
                <div class="kpi-value counter">{{ $manPowerTotal }}</div>
                <div class="kpi-pills">
                    <span class="kpi-pill pill-draft">Draft {{ $manPowerDraft }}</span>
                    <span class="kpi-pill pill-reviewed">Reviewed {{ $manPowerReviewed }}</span>
                </div>
                <div class="kpi-progress"><div class="kpi-progress-bar" style="width:{{ $mpPct }}%"></div></div>
            </div>
        </div>
        @endcanany

        @canAny(['viewAny','view'], App\Models\Material::class)
        @php $matPct = $materialTotal > 0 ? round(($materialReviewed / $materialTotal) * 100) : 0; @endphp
        <div class="col-xl col-lg-4 col-sm-6">
            <div class="kpi-card" style="--kpi-color:#b8860b; --kpi-bg:#fff8e1;">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="kpi-label">Materials</div>
                    <div class="kpi-icon"><i class="fa fa-truck"></i></div>
                </div>
                <div class="kpi-value counter">{{ $materialTotal }}</div>
                <div class="kpi-pills">
                    <span class="kpi-pill pill-draft">Draft {{ $materialDraft }}</span>
                    <span class="kpi-pill pill-reviewed">Reviewed {{ $materialReviewed }}</span>
                </div>
                <div class="kpi-progress"><div class="kpi-progress-bar" style="width:{{ $matPct }}%"></div></div>
            </div>
        </div>
        @endcanany

        @canAny(['viewAny','view'], App\Models\EquipmentTools::class)
        @php $eqPct = $equipmentTotal > 0 ? round(($equipmentReviewed / $equipmentTotal) * 100) : 0; @endphp
        <div class="col-xl col-lg-4 col-sm-6">
            <div class="kpi-card" style="--kpi-color:#7030A0; --kpi-bg:#f3e8fb;">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="kpi-label">Tools &amp; Equipment</div>
                    <div class="kpi-icon"><i class="fa fa-wrench"></i></div>
                </div>
                <div class="kpi-value counter">{{ $equipmentTotal }}</div>
                <div class="kpi-pills">
                    <span class="kpi-pill pill-draft">Draft {{ $equipmentDraft }}</span>
                    <span class="kpi-pill pill-reviewed">Reviewed {{ $equipmentReviewed }}</span>
                </div>
                <div class="kpi-progress"><div class="kpi-progress-bar" style="width:{{ $eqPct }}%"></div></div>
            </div>
        </div>
        @endcanany

    </div>

    {{-- ── Main row: Recent Projects + Quick Access ─────── --}}
    <div class="row g-3 mb-4">

        {{-- Recent Projects -------------------------------- --}}
        @canAny(['viewAny','view'], App\Models\Project::class)
        <div class="col-lg-8" id="tour-home-recent">
            <div class="db-card">
                <div class="db-card-header">
                    <div>
                        <h6>My Recent Projects</h6>
                        <p style="margin:2px 0 0; font-size:12px; color:#9ca3af;">Projects you created or are assigned to</p>
                    </div>
                    <a href="/project" style="font-size:12px; font-weight:600; color:#2e75b6; text-decoration:none;">
                        View all <i class="fa fa-arrow-right ms-1" style="font-size:10px;"></i>
                    </a>
                </div>
                <div class="db-card-body" style="overflow-x:auto;">
                    @if($recentProjects->isEmpty())
                        <div class="empty-state">
                            <i class="fa fa-folder-open d-block"></i>
                            <p>No projects yet. <a href="/project/create">Create one</a></p>
                        </div>
                    @else
                    <table class="proj-table">
                        <thead>
                            <tr>
                                <th>Project No</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProjects as $project)
                            <tr onclick="window.location='/project/{{ $project->id }}'">
                                <td><span class="proj-no">{{ $project->project_no }}</span></td>
                                <td><span class="proj-title" title="{{ $project->project_title }}">{{ $project->project_title }}</span></td>
                                <td>
                                    @php
                                        $sClass = match($project->status) {
                                            'APPROVE'  => 's-approved',
                                            'DRAFT'    => 's-draft',
                                            'REJECTED' => 's-rejected',
                                            default    => str_contains($project->status, 'PENDING') || str_contains($project->status, 'WAITING') ? 's-pending' : 's-other',
                                        };
                                        $sLabel = match($project->status) {
                                            'APPROVE'  => 'Approved',
                                            'DRAFT'    => 'Draft',
                                            'REJECTED' => 'Rejected',
                                            default    => Str::title(strtolower($project->status)),
                                        };
                                    @endphp
                                    <span class="s-badge {{ $sClass }}">{{ $sLabel }}</span>
                                </td>
                                <td class="proj-date">{{ $project->created_at->format('d M Y') }}</td>
                                <td style="color:#d1d5db; font-size:11px;"><i class="fa fa-chevron-right"></i></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
        @endcanany

        {{-- Quick Access ----------------------------------- --}}
        <div class="col-lg-4" id="tour-home-qa">
            <div class="db-card">
                <div class="db-card-header">
                    <h6>Quick Access</h6>
                </div>
                <div class="db-card-body">
                    <div class="qa-grid">

                        @canAny(['viewAny','view'], App\Models\Project::class)
                        <a href="/project" class="qa-tile">
                            <div class="qa-icon" style="background:#e8f0fb; color:#2e75b6;"><i class="fa fa-folder-open"></i></div>
                            <span class="qa-label">Cost Estimate</span>
                        </a>
                        @endcanany

                        @canAny(['viewAny','view'], App\Models\WorkItem::class)
                        <a href="/work-item" class="qa-tile">
                            <div class="qa-icon" style="background:#fdeee3; color:#ED7D31;"><i class="fa fa-briefcase"></i></div>
                            <span class="qa-label">Work Items</span>
                        </a>
                        @endcanany

                        @canAny(['viewAny','view'], App\Models\ManPower::class)
                        <a href="/man-power" class="qa-tile">
                            <div class="qa-icon" style="background:#e6f2df; color:#548235;"><i class="fa fa-users"></i></div>
                            <span class="qa-label">Man Power</span>
                        </a>
                        @endcanany

                        @canAny(['viewAny','view'], App\Models\EquipmentTools::class)
                        <a href="/tool-equipment" class="qa-tile">
                            <div class="qa-icon" style="background:#f3e8fb; color:#7030A0;"><i class="fa fa-wrench"></i></div>
                            <span class="qa-label">Tools &amp; Equipment</span>
                        </a>
                        @endcanany

                        @canAny(['viewAny','view'], App\Models\Material::class)
                        <a href="/material" class="qa-tile">
                            <div class="qa-icon" style="background:#fff8e1; color:#b8860b;"><i class="fa fa-truck"></i></div>
                            <span class="qa-label">Materials</span>
                        </a>
                        @endcanany

                        @canAny(['viewAny','view'], App\Models\WorkBreakdownStructure::class)
                        <a href="/work-breakdown-structure" class="qa-tile">
                            <div class="qa-icon" style="background:#e8f0fb; color:#1565c0;"><i class="fa fa-sitemap"></i></div>
                            <span class="qa-label">WBS Setting</span>
                        </a>
                        @endcanany

                        @can('viewAny', App\Models\User::class)
                        <a href="/user" class="qa-tile">
                            <div class="qa-icon" style="background:#f0f4ff; color:#3949ab;"><i class="fa fa-cog"></i></div>
                            <span class="qa-label">User Management</span>
                        </a>
                        @endcan

                        <a href="/survey" class="qa-tile">
                            <div class="qa-icon" style="background:#fce4ec; color:#c62828;"><i class="fa fa-comment"></i></div>
                            <span class="qa-label">Feedback</span>
                        </a>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Info row ──────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="info-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="info-icon-wrap" style="background:#e8f0fb; color:#2e75b6;"><i class="fa fa-info-circle"></i></div>
                    <h6>About</h6>
                </div>
                <p>Web Cost Estimate is a digital platform for creating and managing project cost estimates, adopted from the Excel-based tools used by the Engineering Project &amp; Services (EPS) department at PT Vale Indonesia.</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="info-icon-wrap" style="background:#e6f2df; color:#548235;"><i class="fa fa-book"></i></div>
                    <h6>Guidelines</h6>
                </div>
                <p>User manuals and video tutorials are available to help you get started. Contact the EPS team for access to the latest documentation and training materials.</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="info-icon-wrap" style="background:#fce4ec; color:#c62828;"><i class="fa fa-comment"></i></div>
                    <h6>Feedback</h6>
                </div>
                <p>Your suggestions help us improve this platform. Share your experience so we can keep it aligned with your needs.</p>
                <a href="/survey" class="btn btn-sm mt-auto" style="background:#fce4ec; color:#c62828; border:none; border-radius:8px; font-weight:600; font-size:12px; width:fit-content;">
                    Give Feedback &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- ── Calendar ──────────────────────────────────────── --}}
    <div class="row" id="tour-home-calendar">
        <div class="col-12">
            <div class="calendar-card">
                <div style="padding: 16px 20px; border-bottom: 1px solid #f4f5f7;">
                    <h6 style="margin:0; font-weight:700; font-size:14px; color:#1a2b47;">
                        <i class="fa fa-calendar me-2" style="color:#2e75b6;"></i> Production Calendar
                    </h6>
                </div>
                @include('layouts.calendar')
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ '/js/home_tour.js' }}"></script>
@endpush
