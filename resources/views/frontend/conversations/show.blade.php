@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @elseif(Auth::user()->user_type == 'designer')
                        @include('frontend.inc.designer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="card no-border p-3" style=" background-color: #7f508c;
                                                                text-align: center;
                                                                font-size: 20px;
                                                                color: white;position:relative">
                        <div style="position: absolute ;   top: 5px;
                        left: 14px;">
                            @if (Auth::user()->id == $conversation->sender_id)
                                <img @if ($conversation->receiver->avatar_original == null) src="{{ asset('frontend/images/user.png') }}" @else src="{{ asset($conversation->receiver->avatar_original) }}" @endif class="rounded-circle">
                            @else
                                <img width="50" height="50" @if ($conversation->sender->avatar_original == null) src="{{ asset('frontend/images/user.png') }}" @else src="{{ asset($conversation->sender->avatar_original) }}" @endif class="rounded-circle">
                            @endif
                        </div>
                        
                            {{ $conversation->sender->email }}
                            <br>
                        </div>
                        <div id="card_messages_seller" class="card no-border mt-4 p-3" style="height: 420px;overflow-x:hidden;display:flex;flex-direction:column-reverse">
                            <div id="messages_seller">
                                @foreach ($messages as $key => $message)
                                    @if ($message->user_id == Auth::user()->id)
                                        <div class="block block-comment mb-3">
                                            <div class="d-flex flex-row-reverse">
                                                <div class="flex-grow-1 ml-5 pl-5">
                                                    <div class="p-3 bg-gray rounded text-right" style="
                                                    float: right;
                                                    background-color: #a356ad;
                                                    color: white; width:fit-content"  title="{{ format_Date_time(strtotime($message->created_at)) }}">
                                                        <?php echo nl2br($message->message)  ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="block block-comment mb-3">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 mr-5 pr-5">
                                                    <div class="p-3 bg-gray rounded" style="    width: fit-content;" title="{{ format_Date_time(strtotime($message->created_at)) }}">
                                                        <?php echo nl2br($message->message)  ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <form class="mt-4" id="seller_send_message" action="{{ route('messages.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ encrypt($conversation->id) }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea class="form-control" rows="4" id="send_message_from_seller" name="message" placeholder="Type your reply" required></textarea>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-base-1 mt-3">{{__('Send')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')

<script type="text/javascript">
    // ------------------ lazy load chat -----------------------

        var limit2 = 10; //The number of records to display per request
        var start2 = 0; //The starting pointer of the data
        var action = 'inactive'; //Check if current action is going on or not. If not then inactive otherwise active
        function load_chat_data(l, s){
            $.get('{{ route('conversations.show',encrypt($conversation->id)) }}', { limit2:l,start2:s}, function(data){
                $('#messages_seller').prepend(data);
                if(data == ''){
                    let endofchat = '<p style="background-color: greenyellow;font-size: 17px;text-align: center;margin: -1px;">End of Chat</p>';
                    $('#messages_seller').prepend(endofchat);
                }else{
                    action = 'inactive';
                }
            });
        }

        // if(action == 'inactive'){
        //     action = 'active';
        //     load_chat_data(limit2, start2);
        // }

        $('#card_messages_seller').scroll(function(){
            if(action == 'inactive'){
                action = 'active';
                start2 = start2 + limit2;
                setTimeout(function(){
                    load_chat_data(limit2, start2);
                }, 1000);
            }
        });
    // ---------------------------------------------------------

    $("textarea").keydown(function(e){
        // Enter was pressed without shift key
        if (e.keyCode == 13 && !e.shiftKey)
        {
            // prevent default behavior
            e.preventDefault();
            $('#seller_send_message').submit();
        }
    });

    $('#seller_send_message').on('submit',function(e){
        e.preventDefault();
        let m =  $('#send_message_from_seller').val();
        $('#send_message_from_seller').val('');
        $.post('{{ route('messages.store') }}', {_token:'{{ @csrf_token() }}', conversation_id:'{{ $conversation->id }}',message:m}, function(data){
            //
        });
    });

    var channel = pusher.subscribe('new-message');

    channel.bind('App\\Events\\ChattingMessages',function(x){
        $.post('{{ route('conversations.refresh') }}', {_token:'{{ @csrf_token() }}', id:'{{ encrypt($conversation->id) }}'}, function(data){
            $('#messages_seller').html(data);
        });
    });
</script>
@endsection
    
