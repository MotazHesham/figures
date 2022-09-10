@extends('layouts.app')



@section('content')

<form class="" id="sort_calender" action="" method="GET">
    <div class="box-inline pad-rgt pull-right">
        <div class="box-inline " style="min-width: 200px;">
            <input type="date" class="form-control" id="date" name="date" onchange="sort_calender()">
        </div>
        <div class="box-inline " style="min-width: 200px;">
            <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{__('Phone Number')}}" onchange="sort_calender()">
        </div>
    </div>
</form>
    <div class="row">
        <div class="col-lg-12 pull-right">
            <a data-toggle="modal" data-target="#calender_modal" class="btn btn-lg btn-rounded btn-purple pull-left">{{__('Download Calender')}}</a>
        </div>
    </div>

    <br>

    <div class="panel">
        <div class="panel-body">
            @foreach($users as $user)
                @if(count($user->calenders) > 0)
                    <div class="col-md-4">
                        <div class="card-body" style=" padding: 18px;">
                            <div style="background-image: linear-gradient(to bottom, #2980B9, #34495E); padding: 20px; border-radius: 12px;color:white">
                                <h5 class="card-title" style="    background-color: #44444452; padding: 15px; font-size: 27px; text-align: center; color: white; border-radius: 15px;">
                                    {{$user->name}}
                                </h5>
                                <hr>
                                <div style="font-size: 15px">
                                    <p class="card-text" > <span class="badge bg-default">{{__('Phone')}}</span>: <span>{{$user->phone}}</span></p>
                                    <p class="card-text" ><span class="badge bg-default">{{__('Address')}}</span>: {{$user->address}}</p>
                                    <p class="card-text" ><span class="badge bg-default">{{__('Email')}}</span>: {{$user->email}}</p>
                                </div>
                                <p>
                                    <button style="border: solid black 1px;" class="btn btn-outline-dark" type="button" data-toggle="collapse" data-target="#collapseExample{{$user->id}}" aria-expanded="false" aria-controls="collapseExample{{$user->id}}">
                                        {{__('Show Ocacasions')}} <i class="fa fa-angle-right"></i>
                                    </button>
                                </p>
                                <div class="collapse" id="collapseExample{{$user->id}}">
                                    <div class="card card-body">
                                        @foreach($user->calenders as $occasion)
                                            <div style=" font-size: 15px; padding: 8px;  border: solid 1px white;">
                                                <p><span style="color:#9adca2;font-size:13px">{{__('Occasion')}}</span>: {{$occasion->title}}</p>
                                                <p><span style="color:#9adca2;font-size:13px">{{__('Occasion Descrioption')}}</span>: {{$occasion->description}}</p>
                                                <p><span style="color:#9adca2;font-size:13px">{{__('Date')}}</span>: {{format_Date($occasion->date)}}</p>
                                            <a onclick="confirm_modal('{{route('calender.admin.delete',$occasion->id)}}');" class="btn btn-warning">{{__('Delete')}}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <p class="card-text"><small class="text-muted">{{_('Num of Occasions')}} {{count($user->calenders)}} </small></p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        
        <div class="clearfix">

            <div class="pull-right">

                {{ $users->links() }}

            </div>

        </div> 

    </div>



    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content" id="modal-content">



            </div>

        </div>

    </div> 

    <!-- calender modal -->
    <div class="modal fade" id="calender_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Calender</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('download.calender')}}" class="form-horizontal"  method="post">
                        @csrf 
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="from">{{__('From')}}</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" name="from" id="from" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="to">{{__('To')}}</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" name="to" id="to" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <label class="col-sm-2 control-label" for="description"></label>
                                <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple" type="submit">{{__('Download')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection



@section('script')

    <script type="text/javascript">

        function sort_calender(el){
            $('#sort_calender').submit();
        } 


    </script>

@endsection
