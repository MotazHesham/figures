
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{$resource['order_num']}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="@if($resource['route'] == 'receipt.company') col-md-8 @else col-md-12 @endif">
            @if($resource['route'] == 'admin.orders')
                @php
                    $order = \App\Models\Order::with('orderDetails')->where('code',$resource['order_num'])->first();
                @endphp
                @foreach($order->orderDetails as $orderDetail)
                    @if ($orderDetail->product != null)
                        <div>
                            <a href="{{ asset($orderDetail->product->thumbnail_img) }}" target="_blank"><img width="150" height="150" src={{ asset($orderDetail->chosen_photo ?? $orderDetail->product->thumbnail_img) }}/></a>
                            @if(is_array(json_decode($orderDetail->photos)) && count(json_decode($orderDetail->photos)) > 0)
                                <div>
                                    @foreach (json_decode($orderDetail->photos) as $key => $photo)
                                        <div style="display: inline;position: relative;">
                                            <a href="{{ asset($photo) }}" target="_blanc">
                                                <img style="padding:3px" src="{{ asset($photo) }}" alt="" height="140" width="140" title="{{json_decode($orderDetail->photos_note)[$key]?? '' }}">
                                            </a>
                                            <div style=" display: inline; position: absolute; left: 11px; top: -22px;">
                                                <div style=" background-color: #00000069; text-align: center; color: white; width: 120px;">
                                                    {{json_decode($orderDetail->photos_note)[$key]?? '' }}
                                                </div>
                                            </div>
                                            <div class="text-center" style="display: inline;position: absolute; left: 3px; top: -58px;">
                                                <a href="{{ asset($photo) }}" download="{{$order->code}}_{{$key}}_{{json_decode($orderDetail->photos_note)[$key]?? '' }}" class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <strong>{{ __('N/A') }}</strong>
                    @endif
                    <hr>
                @endforeach
            @elseif($resource['route'] == 'receipt.social')
                @php
                    $receipt = \App\Models\Receipt_social::with('receipt_social_products')->where('order_num',$resource['order_num'])->first();
                @endphp
                @foreach($receipt->receipt_social_products as $receipt_product)
                    @if ($receipt_product->product != null)
                        <h3>{{$receipt_product->product->name}}</h3>
                        <div><?php echo $receipt_product->description; ?></div>
                        <div>
                            @if(is_array(json_decode($receipt_product->product->photos)) && count(json_decode($receipt_product->product->photos)) > 0)
                                @foreach (json_decode($receipt_product->product->photos) as $key => $photo0)
                                    <a href="{{ asset($photo0) }}" target="_blank"><img width="150" height="150" src={{ asset($photo0) }}/></a>
                                @endforeach
                            @endif
                            @if(is_array(json_decode($receipt_product->photos)) && count(json_decode($receipt_product->photos)) > 0)
                                <div>
                                    @foreach (json_decode($receipt_product->photos) as $key => $photo)
                                        <div style="display: inline;position: relative;">
                                            <a href="{{ asset($photo) }}" target="_blanc">
                                                <img style="padding:3px" src="{{ asset($photo) }}" alt="" height="140" width="140" title="{{json_decode($receipt_product->photos_note)[$key]?? '' }}">
                                            </a>
                                            <div style=" display: inline; position: absolute; left: 11px; top: -22px;">
                                                <div style=" background-color: #00000069; text-align: center; color: white; width: 120px;">
                                                    {{json_decode($receipt_product->photos_note)[$key]?? '' }}
                                                </div>
                                            </div>
                                            <div class="text-center" style="display: inline;position: absolute; left: 3px; top: -58px;">
                                                <a href="{{ asset($photo) }}" download="{{$receipt->order_num}}_{{$key}}_{{json_decode($receipt_product->photos_note)[$key]?? '' }}" class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <br>
                            @if($receipt_product->pdf)
                                <a href="{{ asset($receipt_product->pdf) }}" target="_blanc" class="btn btn-info">show pdf</a>
                            @endif
                        </div>
                    @else
                        <strong>{{ __('N/A') }}</strong>
                    @endif
                    <hr>
                @endforeach
            @else
                @if(is_array(json_decode($resource['photos'])) && count(json_decode($resource['photos'])) > 0)
                    @foreach (json_decode($resource['photos']) as $key => $photo)
                        <a href="{{ asset($photo) }}">
                            <img src="{{ asset($photo) }}" alt="" class="img-responsive" width="200" height="200">
                        </a>  <br>
                    @endforeach
                @endif
            @endif
        </div>
        <div class="@if($resource['route'] == 'receipt.company') col-md-4 @else col-md-12 @endif">
            <div class="row">
                <div class="col-md-8">
                    <span class="badge badge-default">{{__('Description')}}</span>
                    <br>
                    <div style="color: rgb(110, 110, 206);">
                        <?php echo $resource['description']; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <span class="badge badge-default">{{__('Note')}}</span>
                    <br>
                    <div style="color: rgb(110, 110, 206);">
                        <?php echo $resource['note']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
