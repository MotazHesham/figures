@extends('layouts.app')

@section('content')

<div class="panel">
    <div style="  position: relative;  background-color: #10141d;  padding: 17px; width: 25%;  margin-left: 34%;  border-radius: 32%;" class="text-center">
        @if($my_conversation)
            <h1 style="color: white">{{__('Your Contacts')}}</h1>
            
            <a href="#" data-toggle="modal" data-target="#new_contact" style="   background-color: #0bdd56;
            position: absolute;
            padding: 10px;
            font-size: 26px;
            color: white;
            bottom: 18%;
            right: -4%;
            border-radius: 50%;">+</a>
        @else 
            <h1 style="color: white">{{__('Staff Contacts')}}</h1>
        @endif
                    
    </div>
    <div class="panel-heading" style="padding: 10px">
        @if($my_conversation)
            <a href="{{ route('admin.conversation.index',0)}}" class="btn btn-rounded btn-lg  btn-purple pull-right">{{__('Show Staff Chat')}}</a> 
        @else 
            <a href="{{ route('admin.conversation.index')}}" class="btn btn-rounded btn-lg  btn-success pull-right">{{__('Back To My Chat')}}</a> 
        @endif
        <form class="" id="sort_chat" action="" method="GET">
            <div class="box-inline pad-rgt pull-right">
                <div class="select" style="min-width: 200px;">
                    <select class="form-control demo-select2" name="sort_user" id="type" onchange="sort_chat()">
                        <option value="">Choose User</option>
                        @foreach($users2 as $user)
                            <option value="{{$user->id}}" @isset($sort_user) @if($sort_user == $user->id) selected @endif @endisset>{{$user->email}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if(!$my_conversation)
                <div class="box-inline pad-rgt pull-right">
                    <div class="select" style="min-width: 200px;">
                        <select class="form-control demo-select2" name="sort_staff" id="type" onchange="sort_chat()">
                            <option value="">Choose Staff</option>
                            @foreach($staffs as $staff)
                                <option value="{{$staff->id}}" @isset($sort_staff) @if($sort_staff == $staff->id) selected @endif @endisset>{{$staff->email}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </form>
    </div>
    <div class="panel-body">
        <div class="container">
            <div class="row">
                @foreach($conversations as $raw)
                    @php
                        $user = $raw->receiver;
                        $seen = $raw->sender_viewed;
                    @endphp 
                    <div class="col-lg-3 text-center " style="position: relative;">
                        <a href="{{route('admin.conversation.show', encrypt($raw->id))}}" class="text-center">
                            @if($user)
                                @if ($user->avatar_original != null)
                                    <img class="image_chating_index" height="200" width="200" style="border-radius:50%;margin:15px" src="{{ asset($user->avatar_original) }}" >
                                @else
                                    <img class="image_chating_index" height="200" width="200" style="margin:15px" src="{{ asset('frontend/images/user.png') }}" >          
                                @endif
                            @else
                                <img class="image_chating_index" height="200" width="200" style="margin:15px" src="{{ asset('frontend/images/user.png') }}" >
                            @endif
                            <div  class="image_chating_index_title" style="   color: white;
                                            padding: 10px;
                                            position: absolute;
                                            bottom: -3%;
                                            left: 8%;
                                            border-radius: 30px;
                                            width:180px;
                                            font-size:10px;
                                            background-color: #0d190df0;    margin-left: 12%;">
                                @if($my_conversation)
                                        {{$raw->title}}  
                                    @if(!$seen)<span style="    background-color: rgb(255, 165, 0);
                                        border-radius: 63%;
                                        padding: 10px;
                                        position: absolute;
                                        right: -3%;
                                        top: -20%;"></span>
                                    @endif
                                @else 
                                    {{$raw->title}}
                                    <br> <span style="color: rgb(90, 179, 90)">{{$raw->sender->email}}</span>
                                @endif
                            </div>
                        </a>
                    </div> 
                @endforeach
            </div>
        </div>
        {{ $conversations->links() }}

    </div>
</div>

<!-- new_contact -->
<div class="modal fade" id="new_contact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{__('Add Contact')}}</h3>
                    </div>
                    <!--Horizontal Form-->
                    <!--===================================================-->
                    <form class="form-horizontal" action="{{ route('admin.conversation.new_contact') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">{{__('Email')}}</label>
                                <div class="col-sm-10">
                                    <select class="form-control demo-select2" name="user_id" >
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->email}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">{{__('Message content')}}</label>
                                <div class="col-sm-10">
                                    <textarea  cols="50" rows="5" name="message" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button class="btn btn-purple" type="submit">{{__('Send')}}</button>
                        </div>
                    </form>
                    <!--===================================================-->
                    <!--End Horizontal Form-->

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
        function sort_chat(el){
            $('#sort_chat').submit();
        }
</script>

@endsection
