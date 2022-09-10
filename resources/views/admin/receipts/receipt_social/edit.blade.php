@extends('layouts.app')

@section('content')

<div class="col-sm-12">
    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px">
            <h3 class="text-center">{{__('Products Of Receipt')}}</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>{{__('#')}}</th>
                        <th>{{__('Product Name')}}</th>
                        <th>{{__('Description')}}</th>
                        <th>{{__('Cost')}}</th>
                        <th>{{__('Extra Commission')}}</th>
                        <th>{{__('Quantity')}}</th>
                        <th>{{__('Total')}}</th>
                        <th>{{__('Commission')}}</th>
                        <th>{{__('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                        <tr>
                            <td>{{ $product ->id }}</td>
                            <td>{{ $product ->title }}</td>
                            <td><?php echo $product->description; ?></td>
                            <td>{{ single_price($product ->cost) }}</td>
                            <td>{{single_price($product ->extra_commission)}}</td>
                            <td>{{ $product ->quantity }}</td>
                            <td>{{ single_price($product ->total + ($product ->extra_commission * $product->quantity)) }}</td>
                            <td>
                                {{ single_price($product ->commission) }}
                            </td>
                            <td>
                                <a class="btn btn-info btn-rounded" href="{{route('receipt.social.edit_product', $product->id)}}">{{__('Edit')}}</a>
                                <a class="btn btn-danger btn-rounded" onclick="confirm_modal('{{route('receipt.social.product.destroy', $product->id)}}');">{{__('Delete')}}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @php
                $discount = round( ( ($receipt->total/100) * $receipt->discount ) , 2);
            @endphp
            <div class="text-center">
                <span class="badge badge-default">{{ single_price($receipt->deposit) }}{{__('Deposit')}}</span>
                <span class="badge badge-default">{{ single_price($receipt->extra_commission) }}  {{__('Extra Commission')}}</span> <br>
                <span class="badge badge-default">{{ single_price($receipt->shipping_country_cost) }}  {{__('Shipping Cost')}}</span> <br>
                <span class="badge badge-default">{{ single_price($receipt->total) }} {{__('Total')}}</span> <br>
                <span class="badge badge-success">= {{ single_price($receipt->total + $receipt->extra_commission + $receipt->shipping_country_cost - $receipt->deposit) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px">
            <h3 class="text-center">{{__('Add Product To Receipt')}}</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="{{ route('receipt.social.store_product') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-sm-5">
                            <input type="hidden" class="form-control" name="receipt_id" id= "receipt_id" value="{{$receipt->id}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="product">{{__('Product')}}</label>
                        <div class="col-sm-8">
                            <select class="form-control demo-select2" data-size="7" data-live-search="true" data-title="Select Product..." data-width="100%" name="title" id="receipt_social_title" required>
								<option value="">{{ ('Select Product') }}</option>
                                @foreach(\App\Models\ReceiptProduct::where('type','social')->get() as $product)
                                    <option value="{{$product->name}}" data-price="{{$product->price}}" data-id="{{$product->id}}">{{ $product->name }} - EGP{{($product->price)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="quantity">{{__('Quantity')}}</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" step="1" class="form-control" name="quantity" id="quantity" required>
                        </div>

                        <input type="hidden" name="cost" id="receipt_social_price">
                        <input type="hidden" name="product_id" id="receipt_product_id">
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('PDF')}}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="file" name="pdf" id="file-6" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="pdf/*" />
                            <label for="file-6" class="mw-100 mb-3">
                                <span></span>
                                <strong>
                                    <i class="fa fa-upload"></i>
                                    {{__('Choose PDF')}}
                                </strong>
                            </label>
                        </div>
                    </div>
                    <div class="form-box bg-white mt-4">
                        <div class="form-box-content p-3">
                            <div id="product-images">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{__('Main Images')}}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" name="photos[]" id="photos-1" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                        <label for="photos-1" class="mw-100 mb-3">
                                            <span></span>
                                            <strong>
                                                <i class="fa fa-upload"></i>
                                                {{__('Choose image')}}
                                            </strong>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">
                                    </div>

                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{ __('Add More') }}</button>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <h3 class="text-center">{{__('Description')}}</h3>
                        <textarea  class="editor" name="description" > </textarea>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for=" "> </label>
                        <div class="col-sm-5">
                            <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-success btn-lg btn-rounded" type="submit">{{__('Add')}}</button>
                        </div>
                        <div class="col-sm-5">
                            <a style="padding: 8px 58px;font-size: 20px;" class="btn btn-info btn-lg btn-rounded" target="_blanc" href="{{route('receipt.social.print_new', $receipt->id)}}">{{__('Print')}}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="col-sm-6">
    <div class="panel mb-5 text-center">


            @if(\App\Models\GeneralSetting::first()->delivery_system == 'ebtekar')
                <div class="panel" style="border-radius:15px">
                    <div class="panel-heading" style="padding: 3px">
                        <h3 class="text-center">{{__('Delivery Man')}}</h3>
                    </div>
                    <div class="panel-body">
                        <form action="{{route('receipt.social.update_delivery_man')}}"  class="form-horizontal" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="shipping_cost_for_delivery">{{__('Delivery Man')}}</label>
                                <div class="col-sm-6">
                                    <select class="form-control demo-select2" name="delivery_man_id" id="delivery_man_id" >
                                        <option value="0">{{__('Select Delivery Man ...')}}</option>
                                        @foreach($users as $user)
                                                <option value="{{$user->id}}" @if ($receipt->delivery_man == $user->id) selected @endif>{{$user->email}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="receipt_id" value="{{$receipt->id}}" id="">

                            <button type="submit" class="btn btn-info btn-block btn-rounded">{{__('Update')}}</button>
                        </form>
                    </div>

                </div>
            @elseif(\App\Models\GeneralSetting::first()->delivery_system == 'wasla')

                @if($response['data'] ?? null)
                    <div class="panel" style="border-radius:15px">
                        <div class="panel-heading" style="padding: 3px">
                            <h3 class="text-center">وصلة </h3>
                        </div>
                        <div class="panel-body">
                            @if(!$receipt->sent_to_wasla)
                                <form action="{{route('receipt.social.send_to_wasla')}}"  class="form-horizontal" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="#">{{__('Countries')}}</label>
                                        <div class="col-sm-4">
                                            @if($response['data'] ?? null)
                                                <select class="form-control demo-select2" name="country_id" id="country_id" required>
                                                    @foreach($response['data'] as $country)
                                                            <option value="{{$country['id']}}">EGP {{$country['cost']}} - {{$country['name']}}  </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                not found
                                            @endif
                                        </div>

                                        <label class="col-sm-2 control-label" for="#">هل سيستلم الكابتن طرد من العميل ؟</label>
                                        <div class="col-sm-4">
                                            <select class="form-control demo-select2" name="type" id="type" required>
                                                <option value="no">لا</option>
                                                <option value="partial">مرتجع جزئي</option>
                                                <option value="change">مرتجع استبدال</option>
                                                <option value="return">مرتجع استرجاع</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="#">الحالة</label>
                                        <div class="col-sm-4">
                                            <select class="form-control demo-select2" name="status" id="status" required>
                                                <option value="draft">في المحفوطة</option>
                                                <option value="sent">مرسلة للشحن</option>
                                            </select>
                                        </div>
                                        <label class="col-sm-2 control-label" for="#">المنطقة</label>
                                        <div class="col-sm-4">
                                            <input type="text"  name="district" class="form-control" id="" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="#">المطلوب تحصيله شامل الشحن</label>
                                        <div class="col-sm-4">
                                            <input type="number"  name="cost" class="form-control" id="" value="{{$receipt->total + $receipt->extra_commission + $receipt->shipping_country_cost - $receipt->deposit}}" required>
                                        </div>
                                        <label class="col-sm-2 control-label" for="#">في حالة الأسترجاع</label>
                                        <div class="col-sm-4">
                                            <input type="number"  name="in_return_case" class="form-control" id="" required>
                                        </div>
                                    </div>

                                    <input type="hidden" name="receipt_id" value="{{$receipt->id}}" id="">

                                    <button type="submit" class="btn btn-danger btn-block btn-rounded">أرسال لوصلة</button>
                                </form>
                            @else
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="text-center" style="padding:20px">
                                        <i class="fa fa-check-circle" style="font-size: 40px; color: green;padding:10px"></i>
                                        <br>
                                        تم أرسال الفاتورة لوصلة
                                    </h4>
                                </div>
                                <div class="col-md-6" style="padding:45px">
                                    <div>
                                        <span style="font-size: 20px;" class="badge badge-{{delivery_status_function($receipt->delivery_status)}}">{{ __(ucfirst(str_replace('_', ' ', $receipt->delivery_status))) }}</span>
                                        @if($receipt->delivery_status == 'delay')
                                            <span class="badge badge-defualt">{{$receipt->delay_reason}}</span>
                                        @elseif($receipt->delivery_status == 'delivered')
                                            <span class="badge badge-success">{{format_Date_time($receipt->done_time)}}</span>
                                        @elseif($receipt->delivery_status == 'cancel')
                                            <span class="badge badge-danger">{{$receipt->cancel_reason}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>
                @else
                    <div class="panel" style="border-radius:15px">
                        <div class="panel-heading" style="padding: 3px">
                            <h3 class="text-center">وصلة</h3>
                        </div>
                        <div class="panel-body">
                        <a href="{{ route('profile.index') }}"><i class="demo-pli-male icon-lg icon-fw"></i> {{__('Try login to Wasla again')}}</a>
                    </div>
                @endif
            @endif

        <hr>

        <div class="panel-heading" style="padding:10px;margin-bottom:60px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.social',['confirm' => $receipt->confirm , 'receipt_type' => $receipt->receipt_type])}}"><i class="fa fa-chevron-circle-left"></i> back</a>
                <br>
                <span class="badge badge-grey">{{$receipt->order_num}}</span>
                {{__('Update Receipt')}}
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->


        <form class="form-horizontal" action="{{ route('receipt.social.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$receipt->id}}" id="">
            <div class="panel-body">

                @include('admin.partials.error_message')

                <div class="col-md-12" >

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;" >
                            <span class="badge badge-default">{{__('Client Name')}}</span>
                            <input type="text" class="form-control" name="client_name" id="client_name" value="{{$receipt->client_name}}">
                        </div>
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">{{__('Receipt Type')}}</span>
                            <select class="form-control" name="type" id="type" >
                                <option value="individual" @if($receipt->type == 'individual') selected @endif>Individual</option>
                                <option value="corporate" @if($receipt->type == 'corporate') selected @endif>Corporate</option>
                            </select>
                        </div>
                    </div>


                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">{{__('Phone Number')}}</span>
                            <input type="text" class="form-control" value="{{$receipt->phone}}"  name="phone" id="phone" >
                        </div>
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">{{__('Phone Number 2')}}</span>
                            <input type="text" class="form-control" value="{{$receipt->phone2}}" name="phone2" id="phone2" >
                        </div>
                    </div>


                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">{{__('Delivery Date')}}</span>
                            <input type="text" value="{{format_Date($receipt->deliver_date)}}"  disabled id="deliver_date_text" class="form-control">
                            <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="deliver_date" id="deliver_date" >
                        </div>
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">{{__('Date Of Receiving Order')}}</span>
                            <input type="text" value="{{format_Date($receipt->date_of_receiving_order)}}" disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;" >
                            <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order" >
                        </div>
                    </div>



                    <div class="row" style="margin-bottom: 10px">

                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">{{__('Shipping Cost')}}</span>
                            <select class="form-control demo-select2" name="shipping_country" id="shipping_country" >
                                <optgroup label="{{__('Districts')}}">
                                    @foreach($districts as $district)
                                        <option value={{$district->id}} @if($receipt->shipping_country_id == $district->id) selected @endif>{{$district->name}} - EGP{{($district->cost)}}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="{{__('Countries')}}">
                                    @foreach($countries as $country)
                                        <option value={{$country->id}} @if($receipt->shipping_country_id == $country->id) selected @endif>{{$country->name}} - EGP{{($country->cost)}}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="{{__('Metro')}}">
                                    @foreach($metro as $raw)
                                        <option value={{$raw->id}} @if($receipt->shipping_country_id == $raw->id) selected @endif>{{$raw->name}} - EGP{{($raw->cost)}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">{{__('Deposit')}}</span>
                            <input type="number" min="0" step="0.1" value="{{$receipt->deposit}}" class="form-control" required name="deposit" id="deposit" >
                        </div>
                        <div class="col-sm-12 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">سوشيال</span>
                            <select class="form-control" name="socials[]" id="socials[]" multiple>
                                @foreach($socials as $raw)
                                    <option value={{$raw->id}} @isset($receipt) @if(in_array($raw->id,$receipt->socials()->get()->pluck('id')->toArray())) selected @endif @endisset>{{$raw->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                </div>
                <div class="col-md-12">
                    <div class=" text-center">
                        <h3 class="text-center">{{__('Address')}}</h3></span><textarea  class="form-control" rows="7" name="address" >{{$receipt->address}}</textarea>
                    </div>
                    <h3 class="text-center">{{__('Note')}}</h3>
                    <textarea rows="4"  class="form-control" name="note" ><?php echo $receipt->note ?></textarea>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button style="padding: 8px 58px;font-size: 20px;margin-top:15px" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Update')}}</button>
                    </div>
                </div>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">

        $(document).ready(function(){
            $('#receipt_social_title').on('change',function(){
                let price = $(this).find(':selected').data('price');
                let id = $(this).find(':selected').data('id');
                $('#receipt_social_price').val(price);
                $('#receipt_product_id').val(id);
            })
        });
        var photo_id = 2;
        function add_more_slider_image(){
            var photoAdd =  '<div class="row">';
            photoAdd +=  '<div class="col-md-2">';
            photoAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger">حذف الصورة</button>';
            photoAdd +=  '</div>';
            photoAdd +=  '<div class="col-md-6">';
            photoAdd +=  '<input type="file" name="photos[]" id="photos-'+photo_id+'" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
            photoAdd +=  '<label for="photos-'+photo_id+'" class="mw-100 mb-3">';
            photoAdd +=  '<span></span>';
            photoAdd +=  '<strong>';
            photoAdd +=  '<i class="fa fa-upload"></i>';
            photoAdd +=  "{{__('Choose image')}}";
            photoAdd +=  '</strong>';
            photoAdd +=  '</label>';
            photoAdd +=  '</div>';
            photoAdd +=  '<div class="col-md-4">';
            photoAdd +=  '<input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">';
            photoAdd +=  '</div>';
            photoAdd +=  '</div>';
            $('#product-images').append(photoAdd);

            photo_id++;
            imageInputInitialize();
        }

        function delete_this_row(em){
            $(em).closest('.row').remove();
        }

        function imageInputInitialize(){
            $('.custom-input-file').each(function() {
                var $input = $(this),
                    $label = $input.next('label'),
                    labelVal = $label.html();

                $input.on('change', function(e) {
                    var fileName = '';

                    if (this.files && this.files.length > 1)
                        fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                    else if (e.target.value)
                        fileName = e.target.value.split('\\').pop();

                    if (fileName)
                        $label.find('span').html(fileName);
                    else
                        $label.html(labelVal);
                });

                // Firefox bug fix
                $input
                    .on('focus', function() {
                        $input.addClass('has-focus');
                    })
                    .on('blur', function() {
                        $input.removeClass('has-focus');
                    });
            });
        }
    </script>
@endsection

