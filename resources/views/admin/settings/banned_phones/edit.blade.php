@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('banned_phones.update', $phone->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
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
                        <input type="text" placeholder="{{__('Phone')}}" id="phone" name="phone" class="form-control" required value="{{ $phone->phone }}">
                    </div>
                </div>
                <div class="form-group">
                    @if ($errors->has('reason'))
                        <div class="help-block alert-danger">
                            <strong>{{ $errors->first('reason') }}</strong>
                        </div>
                    @endif
                    <label class="col-sm-2 control-label" for="reason">{{__('Reason')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{__('Reason')}}" id="reason" name="reason" class="form-control" required value="{{ $phone->reason }}">
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
