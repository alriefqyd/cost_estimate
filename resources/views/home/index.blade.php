@extends('layouts.main')
@section('main')
<div class="row">
    <div class="col-12">
        <div class="home-slider owl-carousel js-fullheight" style="height: 550px">
            <div class="slider-item js-fullheight" style="background-image:url({{ asset('/assets/images/vale2.jpg') }}); height: 550px">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row slider-text js-fullheight align-items-center m-l-25">
                        <div class="col-md-12 m-t-5 ftco-animate">
                            <div class="text-animation-slider">
                                <h1>Cost Estimate</h1>
                                <h2 class="mb-3 m-l-5">Every Count Matters!</h2>
                            </div>
                            <a href="/project" class="btn btn-square btn-outline-primary btn-outline-light-2x shadow m-l-5" style="border-color: white; color: white; font-size: 15px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);" role="button" data-bs-original-title="" title="">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-item js-fullheight" style="background-image:url({{ asset('/assets/images/vale1.jpg') }}); height: 550px">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row slider-text js-fullheight align-items-center m-l-25">
                        <div class="col-md-12 m-t-5 ftco-animate">
                            <div class="text-animation-slider">
                                <h1>Cost Estimate</h1>
                                <h2 class="mb-3 m-l-5">Every Count Matters!</h2>
                            </div>
                            <a href="/project" class="btn btn-square btn-outline-primary btn-outline-light-2x m-l-5" style="border-color: white; color: white; font-size: 15px;" role="button" data-bs-original-title="" title="">
                                Create Cost Estimate
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="row mt-3">
    <div class="col-xl-4 xl-50 col-lg-6 box-col-6">
        <div class="card bg-primary">
            <div class="card-body">
                <div class="media faq-widgets">
                    <div class="media-body">
                        <h5>About</h5>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                    </div><i data-feather="book-open"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 xl-50 col-lg-6 box-col-6">
        <div class="card bg-primary">
            <div class="card-body">
                <div class="media faq-widgets">
                    <div class="media-body">
                        <h5>Guidelines</h5>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                    </div><i data-feather="aperture"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-12 xl-100 box-col-12">
        <div class="card bg-primary">
            <div class="card-body">
                <div class="media faq-widgets">
                    <div class="media-body">
                        <h5>Feedback</h5>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                    </div><i data-feather="file-text"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
