@extends('layouts.app')

@section('content')

<div class="col-sm-12">
    <div class="panel mb-5">
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.social.update_product') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$receipt->id}}" id="">
            <div class="panel-body">
                <h3 class="text-center">
                    <a class="badge badge-default" href="{{route('receipt.social.edit',$receipt->receipt_social_id)}}"><i class="fa fa-chevron-circle-left"></i> back</a>
                    <br>
                    <span class="badge badge-grey">{{$receipt->receipt_social->order_num}}</span>
                    {{__('Update Receipt')}}
                </h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="product">{{__('Product')}}</label>
                            <div class="col-sm-8">
                                <select class="form-control demo-select2" data-size="7" data-live-search="true" data-title="Select Product..." data-width="100%" name="title" id="receipt_social_title" required>
                                    <option value="">{{ ('Select Product') }}</option>
                                    @foreach(\App\Models\ReceiptProduct::where('type','social')->get() as $product)
                                        <option value="{{$product->name}}" @if($product->name == $receipt->title) selected @endif data-price="{{$product->price}}" data-id="{{$product->id}}">{{ $product->name }} - {{single_price($product->price)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p style="color: black;padding:8px" id="receipt_social_price2"></p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="quantity">{{__('Quantity')}}</label>
                            <div class="col-sm-8">
                                <input type="number"  min="0" step="1" class="form-control" value="{{$receipt->quantity}}" name="quantity" id="quantity" required>
                            </div>

                            <input type="hidden" name="cost" id="receipt_social_price" value="{{$receipt->cost}}">
                            <input type="hidden" name="product_id" id="receipt_product_id" value="{{$receipt->receipt_product_id}}">
                        </div>
                        @if(Auth::user()->user_type == 'admin')
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="extra_commission">{{__('Extra Commission')}}</label>
                                <div class="col-sm-8">
                                    <input type="number"  min="0" step="1" class="form-control" value="{{$receipt->extra_commission}}" name="extra_commission" id="extra_commission" required>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <div class="col-md-2">
                                <label>{{__('PDF')}}</label>
                            </div>

                            <div class="col-md-6">
                                <input type="file" name="pdf" id="file-6" value="{{$receipt['pdf']}}" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="pdf/*" />
                                <label for="file-6" class="mw-100 mb-3">
                                    <span></span>
                                    <strong>
                                        <i class="fa fa-upload"></i>
                                        {{__('Choose PDF')}}
                                    </strong>
                                </label>
                            </div>
                            <div class="col-md-4">
                                @if($receipt->pdf)
                                    <a href="{{ asset($receipt->pdf) }}" target="_blanc" class="btn btn-info">show pdf</a>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h3 class="text-center">{{__('Description')}}</h3>
                            <textarea  class="editor" name="description" > <?php echo $receipt->description; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div id="product-images">

                            @if(is_array(json_decode($receipt->photos)) && count(json_decode($receipt->photos)) > 0)
                                @foreach (json_decode($receipt->photos) as $key => $photo)
                                    <div class="row">
                                        <div class="col-md-2">
                                            <button type="button" onclick="delete_this_row(this)" class="btn btn-danger">حذف الصورة</button>
                                        </div>
                                        <input type="hidden" name="previous_photos[]"  value="{{$photo}}">
                                        <div class="col-md-2">
                                            <a href="{{asset($photo)}}" target="_blanc">
                                                <img src="{{asset($photo)}}" width="50" height="50" alt="">
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" value="{{json_decode($receipt->photos_note)[$key]?? ''}}" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" onclick="delete_this_row(this)" class="btn btn-danger">حذف الصورة</button>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="photos[]" id="photos-1" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*"/>
                                    <label for="photos-1" class="mw-100 mb-3">
                                        <span></span>
                                        <strong>
                                            <i class="fa fa-upload"></i>
                                            {{__('Choose image')}}
                                        </strong>
                                    </label>
                                </div>

                                <div class="col-md-4">
                                    <input type="text"  name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{ __('Add More') }}</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
						<label class="col-sm-2 control-label" for="description"></label>
                        <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple" type="submit">{{__('Update')}}</button>
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


        function delete_row(em){
            $(em).closest('.row').remove();
            update_sku();
        }

        var photo_id = 2;
        function add_more_slider_image(){
            var photoAdd =  '<div class="row">';
            photoAdd +=  '<div class="col-md-2">';
            photoAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-danger">حذف الصورة</button>';
            photoAdd +=  '</div>';
            photoAdd +=  '<div class="col-md-4">';
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
    </script>
@endsection
