@include('layouts.assets')
{{--<x-guest-layout>--}}
{{--    <x-jet-authentication-card>--}}
{{--        <x-slot name="logo">--}}
{{--            <x-jet-authentication-card-logo />--}}
{{--        </x-slot>--}}


{{--        <x-jet-validation-errors class="mb-4"></x-jet-validation-errors>--}}

{{--        @if (session('status'))--}}
{{--            <div class="mb-4 font-medium text-sm text-green-600">--}}
{{--                {{ session('status') }}--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        <div class="col-md-12 mb-5">--}}
{{--            <img src="{{asset('assets/images/Vale_logo.svg')}}">--}}
{{--        </div>--}}
{{--        <div class="col-md-12">--}}
{{--            <p>Cost Estimate Management</p>--}}
{{--        </div>--}}


{{--        <form method="POST" action="{{ route('login') }}">--}}
{{--            @csrf--}}

{{--            <div>--}}
{{--                <x-jet-label for="user_name" value="{{ __('User Name') }}" />--}}
{{--                <x-jet-input id="user_name" class="block mt-1 w-full" type="text" name="user_name" :value="old('user_name')" required autofocus />--}}
{{--            </div>--}}

{{--            <div class="mt-4">--}}
{{--                <x-jet-label for="password" value="{{ __('Password') }}" />--}}
{{--                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />--}}
{{--            </div>--}}

{{--            <div class="block mt-4">--}}
{{--                <label for="remember_me" class="flex items-center">--}}
{{--                    <x-jet-checkbox id="remember_me" name="remember" />--}}
{{--                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>--}}
{{--                </label>--}}
{{--            </div>--}}

{{--            <div class="flex items-center justify-end mt-4">--}}
{{--                @if (Route::has('password.request'))--}}
{{--                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">--}}
{{--                        {{ __('Forgot your password?') }}--}}
{{--                    </a>--}}
{{--                @endif--}}

{{--                <x-jet-button class="ml-4">--}}
{{--                    {{ __('Log in') }}--}}
{{--                </x-jet-button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </x-jet-authentication-card>--}}
{{--</x-guest-layout>--}}

<section>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="login-card">
                    <form method="POST" class="theme-form login-form" action="{{ route('login') }}">
                        @csrf
                        <div class="col-md-12 mb-4 text-center">
                            <img class="mb-1" src="{{asset('assets/images/Vale_logo.svg')}}">
                            <h5 class="mb-2">Cost Estimate Management</h5>
                        </div>

                        <x-jet-validation-errors class="mb-4 text-danger"></x-jet-validation-errors>

                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="form-group">
                            <label>User Name</label>
                            <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                <input class="form-control" name="user_name" value="{{old('user_name')}}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="icon-lock"></i></span>
                                <input class="form-control js-validate js-password js-user-password height-40" name="password"  type="password"
                                       autocomplete="off"
                                       value="{{old('password')}}">
                                <div class="input-group-text pt-2">
                                    <i class="fa fa-eye cursor-pointer js-show-hide-password"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{asset('/assets/js/jquery-3.5.1.min.js')}}"></script>
<!-- feather icon js-->
<script src="{{'/assets/js/jquery-validation/dist/jquery.validate.min.js'}}"></script>
<script src="{{'/js/application.js'}}"></script>

