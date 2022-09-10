
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
                                    <span class="badge badge-success">{{ __('Description') }}</span>
                                </b> 
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['description'] != $logs[$key]->properties['description']) style="color:red" @endif 
                                        @endisset>
                                        
                                    <?php echo $log->properties['description'] ?? '';?>
                                </span>
                            </p> 
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
                        </div>
                        <div class="col-md-3"> 
                            <p>
                                <b>
                                    {{ __('Order Cost') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['order_cost'] != $logs[$key]->properties['order_cost']) style="color:red" @endif 
                                        @endisset>
                                (  {{ single_price($log->properties['order_cost']) ?? ''}}
                                )</span>
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

                            <br> 

                            <p>
                                <b>
                                    {{ __('Delivery Status') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['delivery_status'] != $logs[$key]->properties['delivery_status']) style="color:red" @endif 
                                        @endisset>
                                (  {{ __(ucfirst(str_replace('_', ' ', $log->properties['delivery_status'])))}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Payment Status') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['payment_status'] != $logs[$key]->properties['payment_status']) style="color:red" @endif 
                                        @endisset>
                                (  {{ __(ucfirst( $log->properties['payment_status'] ))}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    حالة التشغيل
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['playlist_status'] != $logs[$key]->properties['playlist_status']) style="color:red" @endif 
                                        @endisset>
                                (   {{ __('playlist_status_' . $log->properties['playlist_status']) }}
                                )</span>
                            </p> 

                            <br>   

                            <p>
                                <b>
                                    {{ __('Delay Reason') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['delay_reason'] != $logs[$key]->properties['delay_reason']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['delay_reason'] ?? ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Cancel Reason') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['cancel_reason'] != $logs[$key]->properties['cancel_reason']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['cancel_reason'] ?? ''}}
                                )</span>
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
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['send_to_playlist_date'] != $logs[$key]->properties['send_to_playlist_date']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['send_to_playlist_date'] ? format_Date(strtotime($log->properties['send_to_playlist_date'])) : ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Date of Receiving Order') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['date_of_receiving_order'] != $logs[$key]->properties['date_of_receiving_order']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['date_of_receiving_order'] ? format_Date($log->properties['date_of_receiving_order']) : ''}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Delivery Date') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['deliver_date'] != $logs[$key]->properties['deliver_date']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['deliver_date'] ? format_Date($log->properties['deliver_date']) : ''}}
                                )</span>
                            </p> 

                            <br>

                            
                            <p>
                                <b>
                                    {{ __('Calling') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['calling'] != $logs[$key]->properties['calling']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['calling'] ? 'ON' : 'OFF'}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Quickly') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['quickly'] != $logs[$key]->properties['quickly']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['quickly'] ? 'ON' : 'OFF'}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('No Answer') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['no_answer'] != $logs[$key]->properties['no_answer']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['no_answer'] ? 'ON' : 'OFF'}}
                                )</span>
                            </p> 
                            <p>
                                <b>
                                    {{ __('Done') }}
                                </b>
                                :
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['done'] != $logs[$key]->properties['done']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['done'] ? 'ON' : 'OFF'}}
                                )</span>
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
                                
                                <span   @isset($logs[$key-1]) 
                                            @if($logs[$key-1]->properties['order_num'] != $logs[$key]->properties['order_num']) style="color:red" @endif 
                                        @endisset>
                                (   {{ $log->properties['order_num'] ?? ''}}
                                )</span>
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