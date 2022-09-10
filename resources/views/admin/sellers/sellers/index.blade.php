@extends('layouts.app')

@section('styles')
    <style>
        td{
            font-size: 15px
        }
        
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <a  class="btn btn-purple btn-rounded btn-lg" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">{{__('Send Offer To All Sellers')}}</a>
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    <form action="{{route('seller.send_offers')}}" method="post">
                        @csrf
                        <div class="col-sm-12">
                            <textarea  cols="50" rows="5" name="message" class="form-control" required></textarea>
                        </div>
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-success">{{__('Send')}}</button></div>
                    </form>
                </div>
            </div>
            <a href="{{ route('sellers.create')}}" class="btn btn-rounded btn-lg btn-info pull-right">{{__('Add New Seller')}}</a>
        </div>
    </div>

    <br>

    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="panel">
        <div class="panel-heading " style="padding: 10px">
            <h3 class="text-center">{{__('Sellers')}}</h3> 
        </div>
        <div class="panel-body">
            <table class="table table-striped demo-dt-basic mar-no" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 200px">{{__('Seller info')}}</th>
                    <th></th>
                    <th>{{__('Page Or Group')}}</th>     
                    <th>{{__('Approval')}}</th>
                    <th>{{ __('Commission') }}</th> 
                    <th>{{ __('Num. of Orders') }}</th> 
                    <th>{{ __('Orders') }}</th> 
                    <th>{{__('Identity')}}</th> 
                    <th width="10%">{{__('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $user)
                    @php
                        if($user->seller && $user->seller->seller_type == 'seller'){
                            $calculate_commission = calculate_commission($user->orders);
                        }else{
                            $calculate_commission = calculate_commission($user->social_orders);  
                        }
                    @endphp
                    <tr>
                        <td>{{ ($key+1)  }}</td>
                        <td>
                            <span class="badge badge-default">{{__('Name')}}</span>
                            {{$user->name}} 
                            @if($user->seller && $user->seller->seller_type == 'seller')
                                <span class="badge badge-success">{{ $user->seller->seller_type }}</span>
                            @else
                                <span class="badge badge-purple">{{ $user->seller->seller_type }}</span>
                                <br>
                                <span class="badge badge-info"> <b>code</b> {{$user->seller->discount_code }} / {{$user->seller->discount }}%</span>
                            @endif
                            <hr>
                            <span class="badge badge-default">{{__('Phone')}}</span>
                            {{$user->phone}}
                            <hr>
                            <span class="badge badge-default">{{__('Address')}}</span>
                            {{$user->address}}
                        </td>
                        <td>
                            <span class="badge badge-default">{{__('Email')}}</span>
                            {{$user->email}}
                            <hr>
                            <span class="badge badge-default">{{__('Seller Code')}}</span> 
                            {{$user->seller ? $user->seller->seller_code : ""}}
                            <hr>
                            <span class="badge badge-default">{{__('Qualification')}}</span>
                            {{$user->seller ? $user->seller->qualification : ""}}
                        </td>
                        <td class="text-center">
                            <br>
                            <span class="badge badge-default">{{__('Name')}}</span>
                            <div>
                                {{$user->seller ? $user->seller->social_name : ""}} 
                            </div> 
                            <hr>
                            <span class="badge badge-default">{{__('Link')}}</span>
                            @if($user->seller)
                                @if($user->seller->social_link)
                                    <div>
                                        <a href="{{$user->seller->social_link}}" class="btn btn-primary" target="_blanc">{{__('click here')}}</a>
                                    </div>
                                @endif
                            @endif
                            
                        </td>    
                        @if($user->seller) 
                        
                            <td>
                                <label class="switch">
                                    <input onchange="update_approved(this)" value="{{ $user->id }}" type="checkbox" <?php if($user->seller->verification_status == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>

                        @else 
                            <td></td>
                        @endif
                        

                        <td>
                            
                            <br>
                            <span class="badge badge-info">{{__('pending_commission')}} {{ single_price( $calculate_commission['pending'] ) }}</span>

                            <span class="badge badge-purple">{{__('available_commission')}} {{ single_price( $calculate_commission['available'] ) }}</span>
                            
                            <span class="badge badge-warning">{{__('requested_commission')}} {{ single_price( $calculate_commission['requested'] ) }} </span>
                            
                            <span class="badge badge-success">{{__('delivered_commission')}} {{ single_price( $calculate_commission['delivered'] ) }}</span>
                        </td>

                        <td>
                            @if($user->seller && $user->seller->seller_type == 'seller')
                                <br>
                                <span class="badge badge-default">{{__('In Website')}} {{ $user->seller ? $user->seller->order_in_website : "" }}</span> 
                                <br> 
                                <span class="badge badge-default">{{__('Out Website')}} {{ $user->seller ? $user->seller->order_out_website : "" }}</span> 
                            @endif
                        </td>

                        <td> 
                            <br>
                            
                            @if($user->seller && $user->seller->seller_type == 'seller')
                                <span class="badge badge-info">{{__('Pending')}} {{ $user->orders ? $user->orders->where('delivery_status','pending')->count() : ''}}</span>
                                
                                <span class="badge badge-danger">{{__('Cancel')}} {{ $user->orders ? $user->orders->where('delivery_status','cancel')->count() : ''}}</span>
                                
                                <span class="badge badge-warning">{{__('Delay')}} {{ $user->orders ? $user->orders->where('delivery_status','delay')->count() : ''}} </span>
                                
                                <span class="badge badge-success">{{__('Delivered')}} {{ $user->orders ? $user->orders->where('delivery_status','delivered')->count() : ''}} </span>
                                
                                <span class="badge badge-purple">{{__('On review')}}  {{ $user->orders ? $user->orders->where('delivery_status','on_review')->count() : ''}} </span>
                                
                                <span class="badge badge-grey">{{__('On delivery')}} {{ $user->orders ? $user->orders->where('delivery_status','on_delivery')->count() : ''}} </span>
                            @else 
                                <span class="badge badge-info">{{__('Pending')}} {{ $user->social_orders ? $user->social_orders->where('delivery_status','pending')->count() : ''}}</span>
                                
                                <span class="badge badge-danger">{{__('Cancel')}} {{ $user->social_orders ? $user->social_orders->where('delivery_status','cancel')->count() : ''}}</span>
                                
                                <span class="badge badge-warning">{{__('Delay')}} {{ $user->social_orders ? $user->social_orders->where('delivery_status','delay')->count() : ''}} </span>
                                
                                <span class="badge badge-success">{{__('Delivered')}} {{ $user->social_orders ? $user->social_orders->where('delivery_status','delivered')->count() : ''}} </span>
                                
                                <span class="badge badge-purple">{{__('On review')}}  {{ $user->social_orders ? $user->social_orders->where('delivery_status','on_review')->count() : ''}} </span>
                                
                                <span class="badge badge-grey">{{__('On delivery')}} {{ $user->social_orders ? $user->social_orders->where('delivery_status','on_delivery')->count() : ''}} </span>
                            @endif
                            
                        </td> 

                        @if($user->seller) 
                            <td>
                                @if($user->seller->identity_back) 
                                    <span class="badge badge-default">{{__('From Back')}}</span> 
                                    <img src="{{asset($user->seller->identity_back)}}" alt="" height="50" width="50">
                                @endif 
                                @if($user->seller->identity_front)
                                <hr>
                                <span class="badge badge-default">{{__('From Front')}}</span> 
                                    <img src="{{asset($user->seller->identity_front)}}" alt="" height="50" width="50">
                                @endif
                            </td> 
                        @else 
                            <td></td>
                        @endif

                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('sellers.edit', encrypt($user->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('sellers.destroy', $user->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                </ul>
                            </div> 
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table> 
        </div>
    </div>


    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="profile_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-content">

            </div>
        </div>
    </div>


@endsection

@section('script')
    <script type="text/javascript">

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('sellers.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Approved sellers updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function sort_sellers(el){
            $('#sort_sellers').submit();
        }
    </script>
@endsection
