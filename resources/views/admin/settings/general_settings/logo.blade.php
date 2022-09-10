@extends('layouts.app')

@section('content')

    <div class="col-lg-6 col-lg-offset-3">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('Logo Settings')}}</h3>
            </div>
            @php
                $generalsetting = \App\Models\GeneralSetting::first();
            @endphp
            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('generalsettings.logo.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="control-label" for="logo">Frontend logo <br> <small>(max height 40px)</small></label>
                            @if($generalsetting->logo != null)
                                <img loading="lazy"  src="{{ asset($generalsetting->logo) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <input type="file" id="logo" name="logo" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="control-label" for="admin_logo">Admin logo <br> <small>(60x60)</small></label>
                            @if($generalsetting->admin_logo != null)
                                <img loading="lazy"  src="{{ asset($generalsetting->admin_logo) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <input type="file" id="admin_logo" name="admin_logo" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="control-label" for="favicon">Favicon <br> <small>(32x32)</small></label>
                            @if($generalsetting->favicon != null)
                                <img loading="lazy"  src="{{ asset($generalsetting->favicon) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <input type="file" id="favicon" name="favicon" class="form-control">
                        </div>
                    </div>
                    
                    <hr>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="control-label" for="admin_login_background">Admin login <br> background image <br> <small>(1920x1080)</small></label>
                            @if($generalsetting->admin_login_background != null)
                                <img loading="lazy"  src="{{ asset($generalsetting->admin_login_background) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <input type="file" id="admin_login_background" name="admin_login_background" class="form-control">
                        </div>
                    </div>
                    
                    <hr>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="control-label" for="admin_login_sidebar">Admin login <br> sidebar image <br> <small>(600x500)</small></label>
                            @if($generalsetting->admin_login_sidebar != null)
                                <img loading="lazy"  src="{{ asset($generalsetting->admin_login_sidebar) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <input type="file" id="admin_login_sidebar" name="admin_login_sidebar" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">Save</button>
                </div>
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>

@endsection
