@extends('layouts.app')

@section('content')

<div class="col-sm-12">
    <div class="panel mb-5">
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.product.update_photos') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$product->id}}" id="">
            <div class="panel-body">  
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"> 
                            <input type="text" class="form-control" name="name" value="{{ $product ->name }}">
                        </div>

                        <div class="form-group"> 
                            <input type="number" class="form-control" min="0" step="0.1" name="price" value="{{ $product ->price }}">
                        </div>

                        <div class="form-group"> 
                            <input type="number" class="form-control" min="0" step="0.1" name="commission" value="{{ $product ->commission }}">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group"> 
                            <div id="photos">
                                @if(is_array(json_decode($product->photos)))
                                    @foreach (json_decode($product->photos) as $key => $photo)
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <div class="img-upload-preview">
                                                <img loading="lazy"  src="{{ asset($photo) }}" alt="" class="img-responsive">
                                                <input type="hidden" name="previous_photos[]" value="{{ $photo }}">
                                                <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
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
        $("#photos").spartanMultiImagePicker({
            fieldName:        'photos[]',
            maxCount:         10,
            rowHeight:        '200px',
            groupClassName:   'col-md-4 col-sm-4 col-xs-6',
            maxFileSize:      '',
            dropFileLabel : "Drop Here",
            onExtensionErr : function(index, file){
                console.log(index, file,  'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr : function(index, file){
                console.log(index, file,  'file size too big');
                alert('File size too big');
            }
        }); 
		$('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
    });

</script>

@endsection
