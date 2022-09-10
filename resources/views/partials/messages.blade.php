@foreach($conversation->messages as $message)
            
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
                        width: fit-content;
                        width: -moz-fit-content;
                        padding: 15px;" title="{{format_Date_time(strtotime($message->created_at))}}">
                <?php echo nl2br($message->message)  ?>
            </p>
        </div>
    </div>
    @endif
    
@endforeach