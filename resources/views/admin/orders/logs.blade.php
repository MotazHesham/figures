
    @foreach($logs as $key => $log) 
        @php
            $user = \App\Models\User::find($log->user_id); 
        @endphp     
        <div class="card">
            <div class="card-header" id="heading{{$log->id}}">
                <h2 class="mb-0">
                    <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse{{$log->id}}" aria-expanded="false" aria-controls="collapse{{$log->id}}">
                        <span>{{$user ? $user->email : ''}}</span> - <span style="color:rebeccapurple"> {{ Format_Date_Time(strtotime($log->created_at)) }} </span>   
                    </button>
                </h2>
            </div> 
            <div id="collapse{{$log->id}}" class="collapse" aria-labelledby="heading{{$log->id}}" >
                <div class="card-body" style="direction: rtl;background: #f1f1f1;  padding: 20px; border-radius: 40px;">
                    <div class="row"> 
                        <div class="col-md-3">
                            <p>
                                <b>
                                    <span class="badge badge-info">{{ __('Note') }}</span>
                                </b>  
                                @isset($logs[$key]->properties['note'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['note'])
                                                    @if($logs[$key-1]->properties['note'] != $logs[$key]->properties['note']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                            <?php echo $log->properties['note'] ?? '';?> 
                                    </span>
                                @endisset
                            </p> 
                        </div>
                        <div class="col-md-3">  
                            <p>
                                <b>
                                    {{ __('Delivery Status') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['delivery_status'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['delivery_status'])
                                                    @if($logs[$key-1]->properties['delivery_status'] != $logs[$key]->properties['delivery_status']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (  {{ __(ucfirst(str_replace('_', ' ', $log->properties['delivery_status'])))}}
                                    )</span>
                                @endisset
                            </p> 
                            <p>
                                <b>
                                    {{ __('Payment Status') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['payment_status'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['payment_status'])
                                                    @if($logs[$key-1]->properties['payment_status'] != $logs[$key]->properties['payment_status']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (  {{ __(ucfirst($log->properties['payment_status'])) }}
                                    )</span>
                                @endisset
                            </p> 
                            <p>
                                <b>
                                    حالة التشغيل
                                </b>
                                : 
                                @isset($logs[$key]->properties['playlist_status'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['playlist_status'])
                                                    @if($logs[$key-1]->properties['playlist_status'] != $logs[$key]->properties['playlist_status']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ __('playlist_status_' . $log->properties['playlist_status']) }}
                                    )</span>
                                @endisset
                            </p> 

                            <br>   

                            <p>
                                <b>
                                    {{ __('Delay Reason') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['delay_reason'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['delay_reason'])
                                                    @if($logs[$key-1]->properties['delay_reason'] != $logs[$key]->properties['delay_reason']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['delay_reason'] ?? ''}}
                                    )</span>
                                @endisset
                            </p> 
                            <p>
                                <b>
                                    {{ __('Cancel Reason') }}
                                </b>
                                :
                                @isset($logs[$key]->properties['cancel_reason'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['cancel_reason'])
                                                    @if($logs[$key-1]->properties['cancel_reason'] != $logs[$key]->properties['cancel_reason']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['cancel_reason'] ?? ''}}
                                    )</span>
                                @endisset
                            </p> 
                        </div>
                        <div class="col-md-3"> 
                            <p>
                                <b>
                                    {{ __('Date Created') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['created_at'] != $logs[$key]->properties['created_at']) style="color:red" @endif 
                                        @endisset>
                                (   {{ format_Date_Time(strtotime($log->properties['created_at'])) ?? ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    تاريخ التشغيل
                                </b>
                                :  
                                @isset($logs[$key]->properties['send_to_playlist_date'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['send_to_playlist_date'])
                                                    @if($logs[$key-1]->properties['send_to_playlist_date'] != $logs[$key]->properties['send_to_playlist_date']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['send_to_playlist_date'] ? format_Date(strtotime($log->properties['send_to_playlist_date'])) : ''}}
                                    )</span>
                                @endisset
                            </p> 
                            <p>
                                <b>
                                    {{ __('Date of Receiving Order') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['date_of_receiving_order'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['date_of_receiving_order'])
                                                    @if($logs[$key-1]->properties['date_of_receiving_order'] != $logs[$key]->properties['date_of_receiving_order']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['date_of_receiving_order'] ? format_Date(strtotime($log->properties['date_of_receiving_order'])) : ''}}
                                    )</span>
                                @endisset
                            </p>  

                            <br>

                            
                            <p>
                                <b>
                                    {{ __('Calling') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['calling'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['calling'])
                                                    @if($logs[$key-1]->properties['calling'] != $logs[$key]->properties['calling']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['calling'] ? 'ON' : 'OFF'}}
                                    )</span>
                                @endisset
                            </p>  
                        </div>
                        <div class="col-md-3">
                            <p>
                                <b>
                                    {{ __('id') }}
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
                                    {{ __('Order Num') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['code'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['code'])
                                                    @if($logs[$key-1]->properties['code'] != $logs[$key]->properties['code']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['code'] ?? ''}}
                                    )</span>
                                @endisset
                            </p> 
                            <p>
                                <b>
                                    {{ __('Client') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['client_name'] != $logs[$key]->properties['client_name']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['client_name'] ?? ''}}
                                )</span>
                            </p>  
                            <p>
                                <b>
                                    {{ __('Phone Number') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['phone_number'] != $logs[$key]->properties['phone_number']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['phone_number'] ?? ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Phone Number 2') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['phone_number2'] != $logs[$key]->properties['phone_number2']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['phone_number2'] ?? ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Shipping Cost') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['shipping_country_name'] != $logs[$key]->properties['shipping_country_name']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['shipping_country_name'] ?? ''}}
                                )</span>
                                |
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['shipping_country_cost'] != $logs[$key]->properties['shipping_country_cost']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['shipping_country_cost'] ?? ''}}EGP
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Address') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['shipping_address'] != $logs[$key]->properties['shipping_address']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['shipping_address'] ?? ''}}
                                )</span>
                            </p> 
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>  
    
    @endforeach  