@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel"> 

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('common_questions.update', $common_questions->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="question">{{__('Question')}}</label>
                    <div class="col-sm-10">
                        <input type="text"id="question" name="question" class="form-control" required value="{{$common_questions->question}}">
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Answer')}}</label>
                    <div class="col-sm-10">
                        <textarea name="answer"  class="editor"><?php echo $common_questions->answer; ?> </textarea>
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
