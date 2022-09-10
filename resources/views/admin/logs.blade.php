@extends('layouts.app')
@section('content') 

    @foreach($logs as $key => $log) 
        @php
            $user = \App\Models\User::find($log->user_id); 
        @endphp
        

        <div class="card">
            <div class="card-header" id="heading{{$log->id}}">
                <h2 class="mb-0">
                    <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse{{$log->id}}" aria-expanded="false" aria-controls="collapse{{$log->id}}">
                        <span>{{$user ? $user->email : ''}}</span> - <span > {{$log->created_at}} </span>   
                    </button>
                </h2>
            </div>

            <div id="collapse{{$log->id}}" class="collapse" aria-labelledby="heading{{$log->id}}" >
                <div class="card-body" style="direction: rtl">
                    <div class="row">
                    
                        <div class="col-md-4">
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.id') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['id'] != $logs[$key]->properties['id']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['id'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.order_code') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['order_code'] != $logs[$key]->properties['order_code']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['order_code'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.receiver_name') }}
                                </b>
                                :
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['receiver_name'] != $logs[$key]->properties['receiver_name']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['receiver_name'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.phone_1') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['phone_1'] != $logs[$key]->properties['phone_1']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['phone_1'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.phone_2') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['phone_2'] != $logs[$key]->properties['phone_2']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['phone_2'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.country_name') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['country_id'] != $logs[$key]->properties['country_id']) style="color:red" @endif 
                                        @endisset>
                                (   
                                    @isset($log->properties['country_id'])
                                        @php
                                            $country = \App\Models\Country::find($log->properties['country_id']);
                                        @endphp
                                        
                                        @if($country)
                                            {{ $country->name }} 
                                        @endif
                                    @endisset 
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.district') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['district'] != $logs[$key]->properties['district']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['district'] ?? ''}}
                                )</span>
                            </p>
                        </div>
                        <div class="col-md-4">

                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.address') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['address'] != $logs[$key]->properties['address']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['address'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.cost') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['cost'] != $logs[$key]->properties['cost']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['cost'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.shipping_cost') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['shipping_cost'] != $logs[$key]->properties['shipping_cost']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['shipping_cost'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.supply_price') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['supply_price'] != $logs[$key]->properties['supply_price']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['supply_price'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.in_return_case') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['in_return_case'] != $logs[$key]->properties['in_return_case']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['in_return_case'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.commission') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['commission'] != $logs[$key]->properties['commission']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['commission'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.extra_commission') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @isset($logs[$key-1]->properties['extra_commission']) 
                                                @if($logs[$key-1]->properties['extra_commission'] != $logs[$key]->properties['extra_commission']) style="color:red" @endif 
                                            @endisset>
                                        @endisset
                                (   {{ $log->properties['extra_commission'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.type') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['type'] != $logs[$key]->properties['type']) style="color:red" @endif 
                                        @endisset>
                                (   
                                    @isset($log->properties['type'])
                                        {{ $log->properties['type'] ?trans('global.order.type.'.$log->properties['type']) : ''  }}
                                    @endisset
                                    
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.status') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['status'] != $logs[$key]->properties['status']) style="color:red" @endif 
                                        @endisset>
                                (   
                                    @isset($log->properties['status'])
                                        {{ $log->properties['status'] ? trans('global.order.order_status.'.$log->properties['status']) : '' }}
                                    @endisset
                                    
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.supply_status') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @isset($logs[$key-1]->properties['supply_status']) 
                                                @if($logs[$key-1]->properties['supply_status'] != $logs[$key]->properties['supply_status']) style="color:red" @endif 
                                            @endisset>
                                        @endisset
                                (   
                                    @isset($log->properties['supply_status'])
                                        {{ $log->properties['supply_status'] ? trans('global.order.supply_status.'.$log->properties['supply_status']) : '' }}
                                    @endisset
                                    
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.return_status') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['return_status'] != $logs[$key]->properties['return_status']) style="color:red" @endif 
                                        @endisset>
                                (   
                                    @isset($log->properties['return_status'])
                                        {{ $log->properties['return_status'] ? trans('global.order.return_status.'.$log->properties['return_status']) : ''  }}
                                    @endisset
                                    
                                )</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.delivery_receive_date') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['delivery_receive_date'] != $logs[$key]->properties['delivery_receive_date']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['delivery_receive_date']  ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.done_time') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['done_time'] != $logs[$key]->properties['done_time']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['done_time']  ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.wating_reason') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['wating_reason_1'] != $logs[$key]->properties['wating_reason_1']) style="color:red" 
                                            @elseif($logs[$key-1]->properties['wating_reason_2'] != $logs[$key]->properties['wating_reason_2']) style="color:red" 
                                            @elseif($logs[$key-1]->properties['wating_reason_3'] != $logs[$key]->properties['wating_reason_3']) style="color:red" 
                                            @endif
                                        @endisset>
                                    {{ $log->properties['wating_reason_1'] ? 'محاولة اولي: ' . $log->properties['wating_reason_1'] : ''}} <br>
                                    {{ $log->properties['wating_reason_2'] ? 'محاولة ثانية: ' . $log->properties['wating_reason_2']  : ''}} <br>
                                    {{ $log->properties['wating_reason_3'] ? 'محاولة ثالثة: ' . $log->properties['wating_reason_3'] : ''}}  
                                </span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.delay_date') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['delay_date'] != $logs[$key]->properties['delay_date']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['delay_date'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.return_note') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['return_note'] != $logs[$key]->properties['return_note']) style="color:red" @endif 
                                        @endisset>
                                ( 
                                    @isset($log->properties['return_note'])
                                        {{ $log->properties['return_note'] ? trans('global.order.return_note.'.$log->properties['return_note']) : ''  }}
                                    @endisset
                                
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.description') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['description'] != $logs[$key]->properties['description']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['description'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.note') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['note'] != $logs[$key]->properties['note']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['note'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.receipt_code') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['receipt_code'] != $logs[$key]->properties['receipt_code']) style="color:red" @endif 
                                        @endisset>
                                (    
                                    {{ $log->properties['receipt_code'] ?? ''}}
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.company') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['company_id'] != $logs[$key]->properties['company_id']) style="color:red" @endif 
                                        @endisset>
                                (   
                                    @isset($log->properties['company_id'])
                                        @php
                                            $company = \App\Models\Company::find($log->properties['company_id']);
                                        @endphp
                                        
                                        @if($company)
                                            {{ $company->company_name }} 
                                        @endif
                                    @endisset 
                                )</span>
                            </p>
                            <p>
                                <b>
                                    {{ trans('cruds.order.fields.delivery_man') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['delivery_man_id'] != $logs[$key]->properties['delivery_man_id']) style="color:red" @endif 
                                        @endisset>
                                (   
                                    @isset($log->properties['delivery_man_id'])
                                    
                                        @php
                                            $delivery = \App\Models\DeliveryMan::find($log->properties['delivery_man_id']);
                                        @endphp
                                        
                                        @if($delivery)
                                            {{ $delivery->user ? $delivery->user->email : '' }} 
                                        @endif
                                    @endisset
                                    
                                )</span>
                            </p> 
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>  
    
    @endforeach 
        
@endsection