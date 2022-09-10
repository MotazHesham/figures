@extends('layouts.app')

@section('content')

    <div class="col-lg-6 col-lg-offset-3">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('Quality Responsible')}}</h3>
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('quality_responsible.update',$quality_responsible->id ) }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="text-center">
                    <img src="{{ asset($quality_responsible->photo) }}" height="100" width="100" style="border-radius: 50px" alt="">
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{__('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="name" name="name" value="{{ $quality_responsible->name }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="phone">{{__('Phone')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="phone" name="phone" value="{{ $quality_responsible->phone }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="country_code">{{__('Country Code')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="country_code" name="country_code" value="{{ $quality_responsible->country_code }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="wts_phone">{{__('Whatsapp')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="wts_phone" name="wts_phone" value="{{ $quality_responsible->wts_phone }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="photo">{{__('Photo')}}</label>
                        <div class="col-sm-9">
                            <input type="file" id="photo" name="photo" value="{{ $quality_responsible->photo }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                </div>
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>

@endsection
