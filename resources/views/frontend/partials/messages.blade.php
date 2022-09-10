@foreach ($conversation->messages as $key => $message)
    @if ($message->user_id == Auth::user()->id)
        <div class="block block-comment mb-3">
            <div class="d-flex flex-row-reverse">
                <div class="flex-grow-1 ml-5 pl-5">
                    <div class="p-3 bg-gray rounded text-right" style="
                    float: right;background-color: #a356ad;color: white; width:fit-content"  title="{{ format_Date_time(strtotime($message->created_at)) }}">
                        <?php echo nl2br($message->message)  ?>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="block block-comment mb-3">
            <div class="d-flex">
                <div class="flex-grow-1 mr-5 pr-5">
                    <div class="p-3 bg-gray rounded" style="    width: fit-content;
                    
                    " title="{{ format_Date_time(strtotime($message->created_at)) }}">
                        <?php echo nl2br($message->message)  ?>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
