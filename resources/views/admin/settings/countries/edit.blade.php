@extends('layouts.app')

@section('content')
<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Countries')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('countries.update',$country->id) }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <input type="hidden" name="_method" value="PATCH">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Title')}}</label>
                    <div class="col-sm-9">
                        <input type="text" value="{{ $country->name }}" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="cost">{{__('Cost')}}</label>
                    <div class="col-sm-9">
                        <input type="number" min="0" step="1.0" value="{{ $country->cost }}" id="cost" name="cost" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="cost">{{__('Type')}}</label>
                    <div class="col-sm-9">
                        <select name="type" id="" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="countries" @if($country->type == 'countries') selected @endif>{{__('Countries')}}</option>
                            <option value="districts" @if($country->type == 'districts') selected @endif>{{__('Districts')}}</option>
                            <option value="metro" @if($country->type == 'metro') selected @endif> {{__('Metro')}}</option>
                        </select>
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
