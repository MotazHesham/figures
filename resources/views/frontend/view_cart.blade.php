@extends('frontend.layouts.app')

@section('content')

    <section class="slice-xs sct-color-2 border-bottom">
        <div class="container container-sm">
            <div class="row cols-delimited justify-content-center">
                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center active">
                        <div class="block-icon mb-0">
                            <i class="la la-shopping-cart"></i>
                        </div>
                        <div class="block-content d-none d-md-block">
                            <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center">
                        <div class="block-icon mb-0 c-gray-light">
                            <i class="la la-truck"></i>
                        </div>
                        <div class="block-content d-none d-md-block">
                            <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center">
                        <div class="block-icon c-gray-light mb-0">
                            <i class="la la-credit-card"></i>
                        </div>
                        <div class="block-content d-none d-md-block">
                            <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Payment')}}</h3>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="icon-block icon-block--style-1-v5 text-center">
                        <div class="block-icon c-gray-light mb-0">
                            <i class="la la-check-circle"></i>
                        </div>
                        <div class="block-content d-none d-md-block">
                            <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">4. {{__('Confirmation')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('admin.partials.error_message')

    <section class="py-4 gry-bg" id="cart-summary">
        <div class="container">
            @if(auth()->check() && auth()->user()->carts)
                @include('frontend.partials.cart_details')
            @else
                <div class="dc-header">
                    <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                </div>
            @endif
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="GuestCheckout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{__('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group--style-1">
                                    <input type="email" name="email" class="form-control" placeholder="{{__('Email')}}">
                                    <span class="input-group-addon">
                                        <i class="text-md la la-user"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group--style-1">
                                    <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
                                    <span class="input-group-addon">
                                        <i class="text-md la la-lock"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <a href="{{ route('password.request') }}" class="link link-xs link--style-3">{{__('Forgot password?')}}</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-styled btn-base-1 px-4">{{__('Sign in')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="text-center pt-3">
                        <p class="text-md">
                            {{__('Need an account?')}} <a href="{{ route('user.register.form') }}" class="strong-600">{{__('Register Now')}}</a>
                        </p>
                    </div>
                    @if(\App\Models\BusinessSetting::where('type', 'google_login')->first()->value == 1 || \App\Models\BusinessSetting::where('type', 'facebook_login')->first()->value == 1 || \App\Models\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                        <div class="or or--1 my-3 text-center">
                            <span>or</span>
                        </div>
                        <div class="p-3 pb-0"> 
                            <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 mb-3">
                                <i class="icon fa fa-facebook"></i> {{__('Login with Facebook')}}
                            </a> 
                            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 mb-3">
                                <i class="icon fa fa-google"></i> {{__('Login with Google')}}
                            </a> 
                            {{-- <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4 mb-3">
                                <i class="icon fa fa-twitter"></i> {{__('Login with Twitter')}}
                            </a> --}} 
                        </div>
                    @endif 
                </div>
            </div>
        </div>
    </div>

@endsection 
