@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <h3 class="panel-title text-center" style="background-color: #2b2b3c;color:white;padding:5px">
        @if($conversation->receiver != null)
            @if ($conversation->receiver->avatar_original != null)
                <img src="{{ asset($conversation->receiver->avatar_original) }}" class="img-circle img-sm">
            @else
                <img src="{{ asset('frontend/images/user.png') }}" class="img-circle img-sm">
            @endif
        @endif
        <span>{{ $conversation->title }}</span>
        
        {{-- <div id="load_data_message" style="position: absolute;left:45%"></div> --}}
    </h3>
    <div class="panel">

        <div class="panel-body" id="admin_message_box" style="height: 420px;overflow-x:hidden;display:flex;flex-direction:column-reverse">
            
            @foreach($messages as $message)
            
                @if ($message->user_id != $conversation->receiver_id)
                    <div class="form-group">
                        <div>
                            <div class="media-body">
                                <div style="clear: both"></div>
                                <p style="  float: right;
                                            background-color: #362458;
                                            color:white;
                                            width: fit-content;
                                            width: -moz-fit-content;
                                            padding: 15px;" title="{{format_Date_time(strtotime($message->created_at))}}">
                                    <?php echo nl2br($message->message)  ?>
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <div class="media-body">
                            <p style="  
                                        background-color: #534c4c;
                                        color:white;
                                        width: fit-content;
                                        width: -moz-fit-content;
                                        padding: 15px;" title="{{format_Date_time(strtotime($message->created_at))}}">
                                <?php echo nl2br($message->message)  ?>
                            </p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <hr style="border-width: 3px;">
        <div style="margin: 20px">
            @if (Auth::user()->id == $conversation->sender_id)
                <form id="admin_send_message" action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                    <div class="row">
                        <div class="col-md-9">
                            <textarea class="form-control" rows="4" id="send_message_from_admin" name="message" placeholder="Type your reply" required></textarea>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info btn-block btn-lg" style="height: 100%;background-color:#2b2b3c">{{__('Send')}}</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
        
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">
        // ------------------ lazy load chat -----------------------

        var limit = 30; //The number of records to display per request
        var start = 0; //The starting pointer of the data
        var action = 'inactive'; //Check if current action is going on or not. If not then inactive otherwise active
        function load_chat_data(l, s){
            $.post('{{ route('admin.conversation.show',encrypt($conversation->id)) }}', {_token:'{{ @csrf_token() }}', limit:l,start:s}, function(data){
                $('#admin_message_box').append(data);
                if(data == ''){
                    let endofchat = '<p style="background-color: greenyellow;font-size: 17px;text-align: center;margin: -1px;">End of Chat</p>';
                    $('#admin_message_box').append(endofchat);
                }else{
                    action = 'inactive';
                }
            });
        }

        // if(action == 'inactive'){
        //     action = 'active';
        //     load_chat_data(limit, start);
        // }

        $('#admin_message_box').scroll(function(){
            if(action == 'inactive'){
                action = 'active';
                start = start + limit;
                setTimeout(function(){
                    load_chat_data(limit, start);
                }, 1000);
            }
        });
        // ---------------------------------------------------------



        $('textarea').bind("enterKey",function(e){
            e.preventDefault();
            $('#admin_send_message').submit();
        });
        $('textarea').keyup(function(e){
            if (e.keyCode == 13 && !e.shiftKey)
            {
                e.preventDefault();
                $('#admin_send_message').submit();
            }
        });

        $('#admin_send_message').on('submit',function(e){
            e.preventDefault();
            let m =  $('#send_message_from_admin').val();
            $('#send_message_from_admin').val('');
            $.post('{{ route('messages.store') }}', {_token:'{{ @csrf_token() }}', conversation_id:'{{ $conversation->id }}',message:m}, function(data){
                //
            });
        });


        var channel = pusher.subscribe('new-message');

        channel.bind('App\\Events\\ChattingMessages',function(x){
            $.post('{{ route('admin.conversation.refresh') }}', {_token:'{{ @csrf_token() }}', id:'{{ encrypt($conversation->id) }}'}, function(data){
                $('#admin_message_box').html(data);
            });
        });

</script>
@endsection
