
<div class="card" style="font-family: cursive">
    <div class="px-5 py-3 py-lg-4">
        <div class=""> 
            @if(str_contains(url()->current(),'customer/register')) 
                <div> 
                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 mb-3">
                        <i class="icon fa fa-facebook"></i> {{__('Register with Facebook')}}
                    </a> 
                    <a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 mb-3">
                        <i class="icon fa fa-google"></i> {{__('Register with Google')}}
                    </a> 
                    {{-- <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4">
                        <i class="icon fa fa-twitter"></i> {{__('Register with Twitter')}}
                    </a> --}}
                    <div class="row"> 
                        <div class="col">
                            <a href="{{ route('seller_landingpage') }}" class="btn btn-success btn-block btn-icon-left c-white">
                                Be a Seller 
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('designer_landingpage') }}" class="btn btn-purple btn-block btn-icon-left c-white">
                                Be a Designer 
                            </a> 
                        </div>
                    </div>
                </div> 
            @endif
            <div class="text-center px-35 pt-5">
                <h1 class="heading heading-4 strong-500">
                    @if(str_contains(url()->current(),'customer/register')) 
                        {{__('Create Customer Account')}} 
                    @elseif(str_contains(url()->current(),'seller')) 
                        {{__('Create Seller Account')}}
                    @else
                        {{__('Create Designer Account')}}
                    @endif
                </h1>
            </div>
            <form id="user-register" class="form-default" role="form" action="{{ route('user.register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="input-group input-group--style-1">
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{ __('Name') }}" name="name" required>
                        <span class="input-group-addon">
                            <i class="text-md la la-user"></i>
                        </span>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group input-group--style-1">
                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ __('Email') }}" name="email" required>
                        <span class="input-group-addon">
                            <i class="text-md la la-envelope"></i>
                        </span>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> 
                
                <div class="form-group">
                    <div class="input-group input-group--style-1">
                        <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{ __('Mobile Number') }}" name="phone" required>
                        <span class="input-group-addon">
                            <i class="text-md la la-phone"></i>
                        </span>
                        @if ($errors->has('phone'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>  
                
                <div class="form-group">
                    <div class="input-group input-group--style-1">
                        <input type="password"  class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" name="password" required> 
                        <span class="input-group-addon">
                            <i class="text-md la la-lock"></i>
                        </span>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group input-group--style-1">
                        <input type="password"  class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required>
                        <span class="input-group-addon">
                            <i class="text-md la la-lock"></i>
                        </span>
                    </div>
                </div>

                @if(str_contains(url()->current(),'customer/register'))  

                    <input type="hidden" name="type" value="c">
                @else

                <div class="form-group">
                    @if(str_contains(url()->current(),'seller')) 
                        <input type="hidden" name="type" value="s">
                    @else 
                        <input type="hidden" name="type" value="d">
                        <div class="input-group input-group--style-1">
                            <input type="text" class="form-control{{ $errors->has('store_name') ? ' is-invalid' : '' }}" value="{{ old('store_name') }}" placeholder="{{ __('Store Name') }}" name="store_name" required>
                            <span class="input-group-addon">
                                <i class="text-md la la-store_name"></i>
                            </span>
                            @if ($errors->has('store_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('store_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                    @if ($errors->has('address'))
                        <div class="help-block alert-danger">
                            <strong>{{ $errors->first('address') }}</strong>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="input-group input-group--style-1">
                            <input type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') }}" placeholder="{{ __('Address') }}" name="address" required>
                            <span class="input-group-addon">
                                <i class="text-md la la-address"></i>
                            </span>
                            @if ($errors->has('address'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @if(str_contains(url()->current(),'seller')) 
                        @if ($errors->has('social_name'))
                            <div class="help-block alert-danger">
                                <strong>{{ $errors->first('social_name') }}</strong>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="input-group input-group--style-1">
                                        <input type="text" class="form-control{{ $errors->has('social_name') ? ' is-invalid' : '' }}" value="{{ old('social_name') }}" placeholder="{{ __('Social Name') }}" name="social_name" required>
                                        <span class="input-group-addon"> 
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($errors->has('social_link'))
                            <div class="help-block alert-danger">
                                <strong>{{ $errors->first('social_link') }}</strong>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="input-group input-group--style-1">
                                        <input type="text" class="form-control{{ $errors->has('social_link') ? ' is-invalid' : '' }}" value="{{ old('social_link') }}" placeholder="{{ __('Social Link') }}" name="social_link" required>
                                        <span class="input-group-addon"> 
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                @endif

                <div class="checkbox pad-btm text-left">
                    <input class="magic-checkbox" type="checkbox" name="checkbox_example_1" id="checkboxExample_1a" required>
                    <label for="checkboxExample_1a" class="text-sm">{{__('By signing up you agree to our')}} <a href="{{route('terms')}}">{{__(' terms and conditions.')}}</a></label>
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-styled btn-base-1 w-100 btn-md">{{ __('Create Account') }}</button>
                </div>
            </form> 
        </div>
    </div>
    <div class="text-center px-35 pb-3">
        <p class="text-md">
            {{__('Already have an account?')}}<a href="{{ route('user.login.form') }}" class="strong-600">{{__('Log In')}}</a>
        </p>
    </div>
</div>