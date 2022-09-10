@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <a href="{{ route('tasks.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Task')}}</a>
        </div>
    </div>

    <br>

    <div class="panel">
        <div class="text-right" style="padding:10px">
            <div class="row">
                <div class="col-md-4">
                    {{__('Done')}} ({{$done_count}}) <i class="fa fa-check-circle" style="font-size: 30px; color: green;"></i> <br>
                </div>
                <div class="col-md-4">
                    {{__('In Progress')}} ({{$in_progress}}) <i class="fa fa-spinner" style="font-size: 30px; color: blue;"></i> <br>
                </div>
                <div class="col-md-4">
                    {{__('Out Time')}} ({{$out_time}}) <i class="fa fa-times-circle" style="font-size: 30px; color: red;"></i> <br>
                </div>
            </div>
        </div>

        
        
        <form class="" id="sort_staff_form" action="" method="GET">
                <div class="box-inline pad-rgt pull-right">
                    <div class="select" style="min-width: 200px;">
                        <select class="form-control demo-select2" name="sort_staff" id="sort_staff">
                            <option value="">Choose Staff</option>
                            @foreach($staffs as $staff)
                                <option value="{{$staff->id}}" @isset($sort_staff) @if($sort_staff == $staff->id) selected @endif @endisset>{{$staff->email}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        </form>
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Staff Tasks')}}</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered " cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Staff')}}</th>
                        <th>{{__('Title')}}</th>
                        <th  width="13%">{{__('Task')}}</th>
                        <th>{{__('Start')}}</th>
                        <th>{{__('End')}}</th>
                        <th>{{__('Done At')}}</th>
                        <th>{{__('Status')}}</th>
                        <th width="10%">{{__('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $key => $task)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$task->user->email}}</td>
                            <td>{{$task->title}}</td>
                            <td><?php echo $task->task; ?></td>
                            <td>{{format_Date_time($task->start)}}</td>
                            <td>{{format_Date_time($task->end)}}</td>
                            <td>{{format_Date_time($task->done_time)}}</td>
                            <td>
                                <?php
                                    if($task->done == 0){
                                        echo '<i class="fa fa-spinner" style="font-size: 30px; color: blue;"></i>';
                                    }else{
                                        if($task->done_time > $task->end){
                                            echo '<i class="fa fa-times-circle" style="font-size: 30px; color: red;"></i>';
                                        }else{
                                            echo '<i class="fa fa-check-circle" style="font-size: 30px; color: green;"></i>';
                                        }
                                    }
                                ?>
                            </td>
                            
                            <td>
                                @if($task->user_id != Auth::user()->id)
                                    <div class="btn-group dropdown">
                                        <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                            {{__('Actions')}} <i class="dropdown-caret"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="{{route('tasks.edit', encrypt($task->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                            <li><a onclick="confirm_modal('{{route('tasks.destroy', $task->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $tasks->appends(request()->input())->links() }}
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')

<script>
    $(document).ready(function(){
        $('#sort_staff').on('change',function(){
            $('#sort_staff_form').submit();
        });
    });
</script>

@endsection

