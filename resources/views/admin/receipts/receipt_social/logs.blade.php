
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
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['note'] != $logs[$key]->properties['note']) style="color:red" @endif 
                                        @endisset> 
                                    <?php echo $log->properties['note'] ?? '';?>
                                </span>
                            </p> 

                            <hr>   

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
                                    {{ __('Order Cost') }}
                                </b>
                                :
                                @isset($logs[$key]->properties['total'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['total'])
                                                    @if($logs[$key-1]->properties['total'] != $logs[$key]->properties['total']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (  {{ single_price($log->properties['total']) ?? ''}}
                                    )</span>
                                @endisset
                            </p> 
                            <p>
                                <b>
                                    {{ __('Discount') }}
                                </b>
                                :
                                @isset($logs[$key]->properties['discount'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['discount'])
                                                    @if($logs[$key-1]->properties['discount'] != $logs[$key]->properties['discount']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (  {{ single_price($log->properties['discount']) ?? ''}}
                                    )</span>
                                @endisset
                            </p>  
                            <p>
                                <b>
                                    {{ __('Shipping Cost') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['shipping_country_cost'] != $logs[$key]->properties['shipping_country_cost']) style="color:red" @endif 
                                        @endisset>
                                (  {{ single_price($log->properties['shipping_country_cost']) ?? ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Deposit') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['deposit'] != $logs[$key]->properties['deposit']) style="color:red" @endif 
                                        @endisset>
                                (  {{ single_price($log->properties['deposit']) ?? ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Commission') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['commission'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['commission'])
                                                    @if($logs[$key-1]->properties['commission'] != $logs[$key]->properties['commission']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (  {{ single_price($log->properties['commission']) ?? ''}}
                                    )</span>
                                @endisset
                            </p> 
                            <p>
                                <b>
                                    {{ __('Extra Commission') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['extra_commission'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['extra_commission'])
                                                    @if($logs[$key-1]->properties['extra_commission'] != $logs[$key]->properties['extra_commission']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (  {{ single_price($log->properties['extra_commission']) ?? ''}}
                                    )</span>
                                @endisset
                            </p> 

                            <br> 

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
                            <p>
                                <b>
                                    {{ __('Delivery Date') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['deliver_date'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['deliver_date'])
                                                    @if($logs[$key-1]->properties['deliver_date'] != $logs[$key]->properties['deliver_date']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['deliver_date'] ? format_Date(strtotime($log->properties['deliver_date'])) : ''}}
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
                            <p>
                                <b>
                                    {{ __('Confirm') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['confirm'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['confirm'])
                                                    @if($logs[$key-1]->properties['confirm'] != $logs[$key]->properties['confirm']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['confirm'] ? 'ON' : 'OFF'}}
                                    )</span>
                                @endisset
                            </p> 
                            <p>
                                <b>
                                    {{ __('Quickly') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['quickly'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['quickly'])
                                                    @if($logs[$key-1]->properties['quickly'] != $logs[$key]->properties['quickly']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['quickly'] ? 'ON' : 'OFF'}}
                                    )</span>
                                @endisset
                            </p>  
                            <p>
                                <b>
                                    {{ __('Done') }}
                                </b>
                                : 
                                @isset($logs[$key]->properties['done'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['done'])
                                                    @if($logs[$key-1]->properties['done'] != $logs[$key]->properties['done']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['done'] ? 'ON' : 'OFF'}}
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
                                @isset($logs[$key]->properties['order_num'])
                                    <span   @isset($logs[$key-1]) 
                                                @isset($logs[$key-1]->properties['order_num'])
                                                    @if($logs[$key-1]->properties['order_num'] != $logs[$key]->properties['order_num']) style="color:red" @endif 
                                                @else 
                                                    style="color:red"
                                                @endisset
                                            @endisset>
                                    (   {{ $log->properties['order_num'] ?? ''}}
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
                                    {{ __('Receipt Type') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['type'] != $logs[$key]->properties['type']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['type'] ?? ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Phone Number') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['phone'] != $logs[$key]->properties['phone']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['phone'] ?? ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Phone Number 2') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['phone2'] != $logs[$key]->properties['phone2']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['phone2'] ?? ''}}
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
                                            @if($logs[$key-1]->properties['address'] != $logs[$key]->properties['address']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['address'] ?? ''}}
                                )</span>
                            </p> 
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>  
    
    @endforeach  