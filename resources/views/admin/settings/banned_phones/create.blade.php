@extends('layouts.app')

@section('content')

<div class="col-lg-6">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__(' Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('banned_phones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="panel-body">
                <div class="form-group">
                    @if ($errors->has('phone'))
                        <div class="help-block alert-danger">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </div>
                    @endif
                    <label class="col-sm-2 control-label" for="phone">{{__('Phone')}}</label>
                    <div class="col-sm-10">
                        <input type="text"  id="phone" name="phone" value="{{old('phone')}}" class="form-control"  required>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    @if ($errors->has('reason'))
                        <div class="help-block alert-danger">
                            <strong>{{ $errors->first('reason') }}</strong>
                        </div>
                    @endif
                    <label class="col-sm-2 control-label" for="reason">{{__('Reason')}}</label>
                    <div class="col-sm-10">
                        <input type="text"  id="reason" name="reason" value="{{old('reason')}}" class="form-control"  required>
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
