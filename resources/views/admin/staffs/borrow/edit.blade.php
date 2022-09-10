@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading text-center">
            <h3 class="panel-title">{{__('Update')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('borrow.update', $borrow->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
        	@csrf
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="amount">{{__('Borrow')}}</label>
                    <div class="col-sm-10">
                        <input type="number" min="0" step="0.1" id="amount" name="amount" class="form-control" required value="{{ $borrow->amount }}">
                    </div>
                </div> 
                
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
