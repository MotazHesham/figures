@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block"> 
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @elseif(Auth::user()->user_type == 'designer')
                        @include('frontend.inc.designer_side_nav') 
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Manage Profile')}}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        

                        <div class="row">
                            
                            <div class="col-md-7">
                                <form class="" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-box bg-white mt-4">
                                        <div class="form-box-title px-3 py-2">
                                            {{__('Basic info')}}
                                        </div>
                                        <div class="form-box-content p-3">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Your Email')}}</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="email" class="form-control mb-3" name="email" value="{{ Auth::user()->email }}" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Your Name')}}</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control mb-3" name="name" value="{{ Auth::user()->name }}">
                                                </div>
                                            </div>
                                            @if(Auth::user()->user_type == 'seller')
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>{{__('Social Name')}}</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control mb-3" name="social_name" value="{{ Auth::user()->seller->social_name }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>{{__('Social Link')}}</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control mb-3" name="social_link" value="{{ Auth::user()->seller->social_link }}">
                                                    </div>
                                                </div>
                                            @elseif(Auth::user()->user_type == 'designer')
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>{{__('Store Name')}}</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control mb-3" name="store_name" value="{{ Auth::user()->store_name }}">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Phone Number')}}</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control mb-3"  name="phone_number" value="{{ Auth::user()->phone }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Address')}}</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control mb-3" name="address" value="{{ Auth::user()->address }}">
                                                </div>
                                            </div>
                                            @if(Auth::user()->user_type == 'seller')
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>{{__('Qualification')}}</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control mb-3" name="qualification" value="{{ Auth::user()->seller->qualification }}">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col col-md-2">
                                                    <label>{{__('Photo')}}</label>
                                                </div>
                                                <div class="col col-md-10">
                                                    <input type="file" name="photo" id="file-3" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                                    <label for="file-3" class="mw-100 mb-3">
                                                        <span></span>
                                                        <strong>
                                                            <i class="fa fa-upload"></i>
                                                            {{__('Choose image')}}
                                                        </strong>
                                                    </label>
                                                </div>  
                                            </div>
                                            @if(Auth::user()->user_type == 'seller')
                                                <div class="row">
                                                    <div class="col col-md-2">
                                                        <label>{{__('Identity Front')}}</label>
                                                    </div>
                                                    <div class="col col-md-8">
                                                        <input type="file" name="identity_front" id="file-4" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                                        <label for="file-4" class="mw-100 mb-3">
                                                            <span></span>
                                                            <strong>
                                                                <i class="fa fa-upload"></i>
                                                                {{__('Choose image')}}
                                                            </strong>
                                                        </label>
                                                    </div>
                                                    <div class="col col-md-2">
                                                        <img src="{{asset(Auth::user()->seller->identity_front)}}" alt="" height="50" width="50">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col col-md-2">
                                                        <label>{{__('Identity Back')}}</label>
                                                    </div>
                                                    <div class="col col-md-8">
                                                        <input type="file" name="identity_back" id="file-5" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                                        <label for="file-5" class="mw-100 mb-3">
                                                            <span></span>
                                                            <strong>
                                                                <i class="fa fa-upload"></i>
                                                                {{__('Choose image')}}
                                                            </strong>
                                                        </label>
                                                    </div>
                                                    <div class="col col-md-2">
                                                        <img src="{{asset(Auth::user()->seller->identity_back)}}" alt="" height="50" width="50">
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Your Password')}}</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="password" class="form-control mb-3" name="new_password">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Confirm Password')}}</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="password" class="form-control mb-3" name="confirm_password">
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                
                                    <div class="text-right mt-4">
                                        <button type="submit" class="btn btn-styled btn-base-1">{{__('Update Profile')}}</button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-5">
                                @if ($errors->has('password'))
                                    @include('admin.partials.error_message')
                                @endif
                                <form class="" action="{{ route('user.profile.update_password') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-box bg-white mt-4">
                                        <div class="form-box-title px-3 py-2">
                                            {{__('Update Password')}}
                                        </div>
                                        <div class="form-box-content p-3">
                                            @if(auth()->user()->password != null)
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>{{__('Current Password')}}</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="password" class="form-control mb-3" name="old_password">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>{{__('New Password')}}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control mb-3" name="password">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>{{__('Confirm Password')}}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control mb-3" name="password_confirmation">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right mt-4">
                                        <button type="submit" class="btn btn-danger">{{__('Update')}}</button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@if(count($errors) > 0)
    @section('script')
        <script type="text/javascript">
            showFrontendAlert('error', 'Error Occured');
        </script>
    @endsection
@endif

@section('script')
    <script type="text/javascript">
        function add_new_address(){
            $('#new-address-modal').modal('show');
        }
    </script>
@endsection
