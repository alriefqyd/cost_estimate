@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Survey</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Survey Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                @if(session('message'))
                    @include('flash')
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @if($errors->any())
                                <div class="col-md-12 alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <form class="needs-validation js-add-survey" method="post" action="/survey" novalidate="">
                                @csrf
                                <p>Thank you for taking the time to provide feedback on our app. Your insights are valuable to us and will help us improve the app to better meet your needs.

                                </p>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">How often do you use the app?</label>
                                        <div class="col">
                                            <label class="d-block" for="Often">
                                                <input class="radio_animated" value="daily" type="radio" name="often">
                                                Daily
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" value="weakly" type="radio" name="often">
                                               Weakly
                                            </label>
                                            <label class="d-block" for="edo-ani2">
                                                <input class="radio_animated" value="monthly" type="radio" name="often">
                                                Monthly
                                            </label>
                                            <label class="d-block" for="edo-ani13">
                                                <input class="radio_animated" value="rarely" type="radio" name="often">
                                                Rarely
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">How would you rate the overall performance of the app?</label>
                                        <div class="col">
                                            <label class="d-block" for="Often">
                                                <input class="radio_animated" value="excellent" type="radio" name="performance">
                                                Excellent
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" value="good" type="radio" name="performance">
                                                Good
                                            </label>
                                            <label class="d-block" for="edo-ani2">
                                                <input class="radio_animated" value="average" type="radio" name="performance">
                                                Average
                                            </label>
                                            <label class="d-block" for="edo-ani13">
                                                <input class="radio_animated" value="poor" type="radio" name="performance">
                                                Poor
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">How would you rate the overall user experience of the cost estimate web-based app</label>
                                        <div class="col">
                                            <label class="d-block" for="Often">
                                                <input class="radio_animated" value="very_statisfied" type="radio" name="user_experience">
                                                Very Satisfied
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" value="satisfied" type="radio" name="user_experience">
                                                Satisfied
                                            </label>
                                            <label class="d-block" for="edo-ani2">
                                                <input class="radio_animated" value="unsatisfied" type="radio" name="user_experience">
                                                Unsatisfied
                                            </label>
                                            <label class="d-block" for="edo-ani13">
                                                <input class="radio_animated" value="very_unsatisfied" type="radio" name="user_experience">
                                                Very Unsatisfied
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">How would you rate the ease of use</label>
                                        <div class="col">
                                            <label class="d-block" for="Often">
                                                <input class="radio_animated" value="very_statisfied" type="radio" name="ease">
                                                Very Satisfied
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" value="satisfied" type="radio" name="ease">
                                                Satisfied
                                            </label>
                                            <label class="d-block" for="edo-ani2">
                                                <input class="radio_animated" value="unsatisfied" type="radio" name="ease">
                                                Unsatisfied
                                            </label>
                                            <label class="d-block" for="edo-ani13">
                                                <input class="radio_animated" value="very_unsatisfied" type="radio" name="ease">
                                                Very Unsatisfied
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">How intuitive is the app's user interface?</label>
                                        <div class="col">
                                            <label class="d-block" for="Often">
                                                <input class="radio_animated" value="very_intuitive" type="radio" name="interface">
                                                Very intuitive
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" value="intuitive" type="radio" name="interface">
                                                Intuitive
                                            </label>
                                            <label class="d-block" for="edo-ani2">
                                                <input class="radio_animated" value="neutral" type="radio" name="interface">
                                                Neutral
                                            </label>
                                            <label class="d-block" for="edo-ani13">
                                                <input class="radio_animated" value="unintuitive" type="radio" name="interface">
                                                Unintuitive
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">Are there any features that are difficult to find or use? If so, please specify</label>
                                        <div class="col">
                                            <label class="d-block" for="Often">
                                                <input class="radio_animated" value="yes" type="radio" name="difficult_feature">
                                                Yes
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" value="no" type="radio" name="difficult_feature">
                                                No
                                            </label>
                                            <div class="col">
                                                <textarea class="form-control" name="difficult_feature_text" id="summernote"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">Are there any features you feel are missing or need improvement? If so, please specify</label>
                                        <div class="col">
                                            <label class="d-block" for="Often">
                                                <input class="radio_animated" value="yes" type="radio" name="missing_feature">
                                                Yes
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" value="no" type="radio" name="missing_feature">
                                                No
                                            </label>
                                            <div class="col">
                                                <textarea class="form-control" name="missing_feature_text" id="summernote"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">How satisfied are you with the app overall? </label>
                                        <div class="col">
                                            <label class="d-block" for="Often">
                                                <input class="radio_animated" value="very_statisfied" type="radio" name="overall">
                                                Very Satisfied
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" value="satisfied" type="radio" name="overall">
                                                Satisfied
                                            </label>
                                            <label class="d-block" for="edo-ani2">
                                                <input class="radio_animated" value="unsatisfied" type="radio" name="overall">
                                                Unsatisfied
                                            </label>
                                            <label class="d-block" for="edo-ani13">
                                                <input class="radio_animated" value="very_unsatisfied" type="radio" name="overall">
                                                Very Unsatisfied
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-5">
                                        <label class="form-label" for="validationCustom01">Do you have any suggestions for improving the app?</label>
                                        <div class="col">
                                            <textarea class="form-control" name="suggestion" id="summernote"></textarea>
                                        </div>
                                    </div>

                                <button type="submit" class="btn btn-success float-end">Save Data</button>
                                <a href="/project"><div class="btn btn-danger float-end m-r-5">Cancel</div></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('viewAny', App\Models\User::class)
    @if($surveys && $surveys->count())
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header card-header-custom pb-0">
                        <h6 class="mb-0"><i class="fa fa-comment me-2" style="color:#c62828;"></i> All Feedback Responses
                            <span class="badge bg-secondary ms-2" style="font-size:11px;">{{ $surveys->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0" style="font-size:13px;">
                                <thead>
                                    <tr style="background:#f4f6fb;">
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Usage</th>
                                        <th>Performance</th>
                                        <th>UX</th>
                                        <th>Ease</th>
                                        <th>Interface</th>
                                        <th>Overall</th>
                                        <th>Suggestions / Issues</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($surveys as $i => $s)
                                    @php $ans = json_decode($s->answer, true) ?? []; @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div style="font-weight:600;">{{ $s->user?->profiles?->full_name ?? $s->user?->user_name ?? '—' }}</div>
                                            <div style="font-size:11px;color:#9ca3af;">{{ $s->user?->email }}</div>
                                        </td>
                                        <td>{{ $ans['often'] ?? '—' }}</td>
                                        <td>{{ $ans['performance'] ?? '—' }}</td>
                                        <td>{{ $ans['user_experience'] ?? '—' }}</td>
                                        <td>{{ $ans['ease'] ?? '—' }}</td>
                                        <td>{{ $ans['interface'] ?? '—' }}</td>
                                        <td>{{ $ans['overall'] ?? '—' }}</td>
                                        <td style="max-width:280px;">
                                            @if(!empty($ans['suggestion']))
                                                <div><b>Suggestion:</b> {{ $ans['suggestion'] }}</div>
                                            @endif
                                            @if(!empty($ans['difficult_feature_text']))
                                                <div><b>Difficult feature:</b> {{ $ans['difficult_feature_text'] }}</div>
                                            @endif
                                            @if(!empty($ans['missing_feature_text']))
                                                <div><b>Missing feature:</b> {{ $ans['missing_feature_text'] }}</div>
                                            @endif
                                            @if(empty($ans['suggestion']) && empty($ans['difficult_feature_text']) && empty($ans['missing_feature_text']))
                                                —
                                            @endif
                                        </td>
                                        <td style="white-space:nowrap;">{{ $s->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endcan
@endsection
    <!-- Container-fluid Ends-->

