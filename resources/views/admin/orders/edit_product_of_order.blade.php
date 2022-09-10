@extends('layouts.app')

@section('content')

<div class="panel">
    <div class="panel-body text-center"> 
        <h3 style="margin-bottom: 35px;">
            <a  target="_blanc" 
                href="{{ route('admin.orders.print', $order->id) }}" 
                style=" box-shadow: 1px 2px 14px #80808091; border-radius: 33px;" 
                class="btn btn-default badge badge-default">

                <i class="demo-pli-printer icon-lg"></i>
            </a>
            {{$order->code}} 
        </h3> 
                    
                    
        @include('admin.partials.error_message')
        @include('frontend.orders.partials.edit_product')

    </div>
</div> 

@endsection

@section('script')
    <script type="text/javascript">

        function delete_row(em){
            $(em).closest('.row').remove();
            update_sku();
        }

        var photo_id = 2;
        function add_more_slider_image(){
            var photoAdd =  '<div class="row">';
            photoAdd +=  '<div class="col-2">';
            photoAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>';
            photoAdd +=  '</div>';
            photoAdd +=  '<div class="col-6">';
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
