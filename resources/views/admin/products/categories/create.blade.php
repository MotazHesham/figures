@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading text-center">
            <h3 class="panel-title">{{__('Category Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-10">
                        <input type="text"  id="name" name="name" value="{{old('name')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="banner">{{__('Banner')}} <small>(200x300)</small></label>
                    <div class="col-sm-10">
                        <input type="file" id="banner" name="banner" value="{{old('banner')}}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="icon">{{__('Icon')}} <small>(32x32)</small></label>
                    <div class="col-sm-10">
                        <input type="file" id="icon" name="icon" value="{{old('icon')}}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Meta Title')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="meta_title" value="{{old('meta_title')}}"  >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Meta Description')}}</label>
                    <div class="col-sm-10">
                        <textarea name="meta_description" rows="8" class="form-control">{{old('meta_description')}}</textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer ">
                <button class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
