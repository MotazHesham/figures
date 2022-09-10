@extends('layouts.app')

@section('content')


    <br>
    
        <div class="container page-todo bootstrap snippets bootdeys">
            <div class="col-sm-12 tasks">
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
                
                <div class="task-list">
                    <h1>Tasks</h1>
                    <div class="priority medium"><span>{{__('IN Progress')}}</span></div>
                    @foreach($tasks as $task)
                        <div class="task medium" style="padding:25px">
                            <div class="desc">
                                <div class="title">{{$task->title}}</div>
                                <a style="    border: solid black 1px;" class="btn btn-outline-dark" data-toggle="collapse" href="#collapseExample{{$task->id}}" role="button" aria-expanded="false" aria-controls="collapseExample{{$task->id}}">
                                    See more <i class="fa fa-angle-right"></i>
                                </a>
                                <div class="collapse" id="collapseExample{{$task->id}}" style=" padding: 24px; background-color: ;">
                                    <div class="card card-body" >
                                        <?php echo $task->task; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="time" style="width: fit-content">
                                    <form action="{{route('tasks.update_done')}}" method="post" id="update_done">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$task->id}}">
                                        <label class="switch">
                                        <input name="status" onchange="update_task()" value="{{ $task->id }}" type="checkbox" <?php if($task->done == 1) echo "checked";?> >
                                        <span class="slider round"></span></label>
                                    </form>
                            
                                <div class="date" > 
                                    <div style="font-size: 13px">
                                        <span style=" font-size: 12px; color: #494988; font-weight: bolder;">Start :</span>
                                            {{ format_Date_time($task->start) }} 
                                    </div>
                                    <div style="font-size: 13px">
                                        <span style=" font-size: 12px; color: #d25555; font-weight: bolder;">End :</span>  
                                            {{ format_Date_time($task->end) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="clearfix"></div>		
                </div>		
                
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
    function update_task(){
            $('#update_done').submit();
    }
</script>
    
@endsection
