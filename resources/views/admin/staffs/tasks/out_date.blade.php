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
                    <div class="priority high"><span>{{__('Done out Time')}}</span></div>
                    @foreach($tasks as $task)
                        <div class="task high" style="padding:25px">
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
                            
                                <div class="date" > 
                                    <div style="font-size: 13px">
                                        <span style=" font-size: 12px; color: #494988; font-weight: bolder;">Start :</span>
                                            {{ format_Date_time($task->start) }} 
                                    </div>
                                    <div style="font-size: 13px">
                                        <span style=" font-size: 12px; color: #d25555; font-weight: bolder;">End :</span>  
                                            {{ format_Date_time($task->end) }}
                                    </div>
                                    
                                    <div style="font-size: 13px">
                                        <span style=" font-size: 12px; color: #438549; font-weight: bolder;">Done :</span>  
                                            {{ format_Date_time($task->done_time) }}
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
    function update_task(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('tasks.update_done') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Task Done');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }
</script>
    
@endsection
