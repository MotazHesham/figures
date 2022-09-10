@extends('layouts.app')

@section('content')

<div class="col-lg-6">
    <div class="panel">
        <div class="panel-heading">
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('social.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="panel-body">
                <div class="form-group">
                    @if ($errors->has('name'))
                        <div class="help-block alert-danger">
                            <strong>{{ $errors->first('name') }}</strong>
                        </div>
                    @endif
                    <label class="col-sm-2 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-10">
                        <input type="text"  id="name" name="name" value="{{old('name')}}" class="form-control"  required>
                    </div>

                    @if ($errors->has('photo'))
                        <div class="help-block alert-danger">
                            <strong>{{ $errors->first('photo') }}</strong>
                        </div>
                    @endif
                    <label class="col-sm-2 control-label" for="photo">{{__('photo')}}</label>
                    <div class="col-sm-10">
                        <input type="file" name="photo" id="">
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
