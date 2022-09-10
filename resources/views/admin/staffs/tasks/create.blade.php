@extends('layouts.app')

@section('content')
    <div class="col-lg-6 col-lg-offset-3">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('New Task')}}</h3>
            </div>
            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <div class="panel-body">
                    <div class="form-group">
                        <select class="form-control demo-select2" name="staff" id="staff" required>
                            <option value="">Choose Staff</option>
                            @foreach($staffs as $staff)
                                    <option value="{{$staff->id}}" >{{$staff->email}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label" for="title">Title</label>
                        <div class="col-sm-11">
                            <input type="text" name="title" class="form-control">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px">   
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">{{__('Start Date')}}</span>
                            <input type="text"  disabled id="start_date_text" class="form-control" style="position: relative;">
                            <input type="datetime-local" onkeydown="return false" class="form-control my_custom_date_input"  name="start_date" id="start_date" > 
                        </div>  
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;"> 
                            <span class="badge badge-default">{{__('End Date')}}</span>
                            <input type="text" disabled id="end_date_text" class="form-control" style="position: relative;" >
                            <input type="datetime-local" onkeydown="return false" class="form-control my_custom_date_input"  name="end_date" id="end_date" >
                        </div> 
                    </div>  
                    <div class="form-group">
                        <label class="col-sm-1 control-label" for="task">{{__('Task')}}</label>
                        <div class="col-sm-11">
                            <textarea  class="editor" name="task" ></textarea>
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
