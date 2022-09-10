<div class="modal-body p-4">
    <div class="row no-gutters cols-xs-space cols-sm-space cols-md-space">
        <div class="col-lg-6">
            <h4>Attached images</h4><hr>
            <div class="product-gal sticky-top d-flex ">
                @if(is_array(json_decode($order_details->photos)) && count(json_decode($order_details->photos)) > 0)
                    {{-- <div class="product-gal-img">
                        <img src="{{ asset('frontend/images/placeholder.jpg') }}" class="xzoom img-fluid lazyload"
                            src="{{ asset('frontend/images/placeholder.jpg') }}"
                            data-src="{{ asset(json_decode($order_details->photos)[0]) }}"
                            xoriginal="{{ asset(json_decode($order_details->photos)[0]) }}"/>
                    </div>
                    <div class="product-gal-thumb">
                        <div class="xzoom-thumbs">
                            @foreach (json_decode($order_details->photos) as $key => $photo)
                                <a href="{{ asset($photo) }}">
                                    <img src="{{ asset('frontend/images/placeholder.jpg') }}"
                                        class="xzoom-gallery lazyload"
                                        src="{{ asset('frontend/images/placeholder.jpg') }}" width="80"
                                        data-src="{{ asset($photo) }}"
                                        @if($key == 0) xpreview="{{ asset($photo) }}" @endif title="{{json_decode($order_details->photos_note)[$key]?? '' }}">
                                </a>
                            @endforeach
                        </div>
                    </div> --}}
                    @php
                        $order_code = \App\Models\Order::find($order_details->order_id)->code;
                    @endphp
                    <div>
                        @foreach (json_decode($order_details->photos) as $key => $photo)
                            <div style="display: inline;position: relative;">
                                <img style="padding:3px" src="{{ asset($photo) }}" alt="" height="140" width="140" title="{{json_decode($order_details->photos_note)[$key]?? '' }}"> 
                                <div style=" display: inline; position: absolute; left: 11px; top: -22px;">
                                    <div style=" background-color: #00000069; text-align: center; color: white; width: 120px;">
                                        {{json_decode($order_details->photos_note)[$key]?? '' }}
                                    </div> 
                                </div>
                                <div class="text-center" style="display: inline;position: absolute; left: 3px; top: -57px;">
                                    <a href="{{ asset($photo) }}" download="{{$order_code}}_{{$key}}_{{json_decode($order_details->photos_note)[$key]?? '' }}" class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                
            </div>
            @else 
            </div>
                <p style="color: brown">There is no attached images for this product ....</p>
            @endif
        </div>





        <div class="col-lg-6">
            <!-- Product description -->
            <div class="product-description-wrapper">
                <!-- Product title -->
                <h2 class="product-title">
                    {{ __($order_details->product->name) }}
                </h2>

                @if($order_details->variation != null)
                    <div class="row no-gutters mt-3">
                        <div class="col-2">
                            <div class="product-description-label">{{__('Variation')}}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price">
                                    {{ $order_details->variation }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row no-gutters mt-3">
                    <div class="col-2">
                        <div class="product-description-label">{{__('Price')}}:</div>
                    </div>
                    <div class="col-10">
                        <div class="product-price">
                            
                                {{ single_price($order_details->price) }}
                            
                        </div>
                    </div>
                </div>

            
            

            
                <div class="row no-gutters mt-3">
                    <div class="col-2">
                        <div class="product-description-label">{{__('Quantity')}}:</div>
                    </div>
                    <div class="col-10">
                        <div class="product-price">
                            
                                {{ $order_details->quantity }}
                            
                        </div>
                    </div>
                </div>
                <div class="row no-gutters mt-3">
                    <div class="col-2">
                        <div class="product-description-label">{{__('Total')}}:</div>
                    </div>
                    <div class="col-10">
                        <div class="product-price">
                            
                                {{ single_price($order_details->total_cost) }}
                            
                        </div>
                    </div>
                </div>
                <hr>
                @if($order_details->description != null)
                    <div class="row no-gutters mt-3">
                        <div class="col-2">
                            <div class="product-description-label">{{__('Description')}}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price">
                                
                                <?php echo $order_details->description; ?>
                                
                            </div>
                        </div>
                    </div>
                @endif

                @if($order_details->link != null)
                    <div class="row no-gutters mt-3">
                        <div class="col-2">
                            <div class="product-description-label">{{__('Link')}}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price">
                                
                                    <a href="{{$order_details->link}}">{{ $order_details->link }}</a>
                                
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row no-gutters mt-3">
                    <div class="col-2">
                        <div class="product-description-label">{{__('File Sent To Email')}}:</div>
                    </div>
                    <div class="col-10">
                        <div class="product-price">
                            
                                @if($order_details->email_sent == 1)
                                    Yes
                                @endif
                                @if($order_details->email_sent == 0)
                                    No
                                @endif
                            
                        </div>
                    </div>
                </div>

                <hr>
                
                @if($order_details->pdf != null)
                    <div class="row no-gutters mt-3">
                        <div class="col-2">
                            <div class="product-description-label">{{__('PDf')}}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price">
                                
                                    <a href="{{asset($order_details->pdf)}}"  class="btn btn-outline-success"> {{ __('Download') }}</a>
                                
                            </div>
                        </div>
                    </div>
                @endif

        
    </div>
</div>

<script type="text/javascript">
</script>
