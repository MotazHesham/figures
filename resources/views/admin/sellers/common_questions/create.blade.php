@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Common Questions')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('common_questions.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="question">{{__('Question')}}</label>
                    <div class="col-sm-10">
                        <input type="text"id="question" name="question" class="form-control" required>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Answer')}}</label>
                    <div class="col-sm-10">
                        <textarea name="answer" class="editor" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple btn-block btn-rounded" type="submit">{{__('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
