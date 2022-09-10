<div class="modal-body p-4">
    <div class="row">
        <div class="col-lg-5">
            <h4>{{ __('Attached images') }}</h4><hr>
            @php
                $order_code = \App\Models\Order::find($order_details->order_id)->code;
            @endphp
                @if(is_array(json_decode($order_details->photos)) && count(json_decode($order_details->photos)) > 0)
                <div> 
                    @foreach (json_decode($order_details->photos) as $key => $photo)
                        <div style="display: inline;position: relative;">
                            <img style="padding:3px" src="{{ asset($photo) }}" alt="" height="140" width="140" title="{{json_decode($order_details->photos_note)[$key]?? '' }}"> 
                            <div style=" display: inline; position: absolute; left: 11px; top: -22px;">
                                <div style=" background-color: #00000069; text-align: center; color: white; width: 120px;">
                                    {{json_decode($order_details->photos_note)[$key]?? '' }}
                                </div> 
                            </div>
                            <div class="text-center" style="display: inline;position: absolute; left: 3px; top: -58px;">
                                <a href="{{ asset($photo) }}" download="{{$order_code}}_{{$key}}_{{json_decode($order_details->photos_note)[$key]?? '' }}" class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                    @endforeach 
                </div> 
                @else 
                    <p style="color: brown">There is no attached images for this product ....</p>
                @endif
        </div>





        <div class="col-lg-7">
            <!-- Product description -->
            <div class="product-description-wrapper">
                <!-- Product title -->
                <h3 class="product-title text-center">
                    {{ __($order_details->product->name) }}
                </h3>

                @if($order_details->variation != null)
                    <div >
                            <span class="badge badge-default">{{__('Variation')}}</span> : <b style="color: #2980B9;font-size: 23px;"> ( {{ $order_details->variation }} )</b>
                    </div>
                @endif

                <div >
                        <span class="badge badge-default">{{__('Price')}}</span> : <b style="color: #2980B9;font-size: 23px;">{{ single_price($order_details->price) }}</b>
                </div>
            

            
                <div>
                        <span class="badge badge-default">{{__('Quantity')}}</span> : <b style="color: #2980B9;font-size: 23px;">{{ $order_details->quantity }}</b>
                </div>
                <hr> 

                <div>
                        <span class="badge badge-default">{{__('Description')}}</span> : <b style="color: #2980B9;font-size: 23px;"><?php echo $order_details->description;?> </b>
                </div> 

                @if($order_details->link != null)
                    <div>
                            <span class="badge badge-default">{{__('Link')}}</span> : <b style="color: #2980B9;font-size: 23px;"><a href="{{$order_details->link}}">{{ $order_details->link }}</a></b>
                    </div>
                @endif

                <hr>
                
                @if($order_details->pdf != null)
                    <div>
                            <span class="badge badge-default">{{__('PDf')}}</span> : <b style="color: #2980B9;font-size: 23px;"><a href="{{asset($order_details->pdf)}}"  class="btn btn-outline-success"> {{ __('Download') }}</a></b>
                    </div>
                @endif

                <div>
                    <span class="badge badge-default">{{__('File Sent To Email')}}</span> : <b style="color: #2980B9;font-size: 23px;">
                            @if($order_details->email_sent == 1)
                                Yes
                            @endif
                            @if($order_details->email_sent == 0)
                                No
                            @endif</b>
                </div>

        
    </div>
</div>

<script type="text/javascript">
</script>
