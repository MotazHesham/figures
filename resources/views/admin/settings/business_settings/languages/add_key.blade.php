@extends('layouts.app')

@section('content')


<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title text-center">{{ __('Add New Word') }}</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="{{ route('languages.store.key') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{decrypt($id)}}">
                <div class="form-group">
                    <div class="col-lg-3">
                        <label class="control-label">{{ __('Word in English') }}</label>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" class="form-control" name="key"  required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-12 text-right">
                        <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
