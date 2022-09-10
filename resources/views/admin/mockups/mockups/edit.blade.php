@extends('layouts.app')

@section('content')

<div>
    <h1 class=" text-center">{{ __('Edit Mockup') }}</h1>
</div>
<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<form class="form form-horizontal mar-top" action="{{route('mockups.update',$mockup->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
            <input name="_method" type="hidden" value="PATCH">
			<input type="hidden" name="id" value="{{ $mockup->id }}">
			@csrf
			<div class="panel">
				<div class="panel-heading bord-btm">
					<h3 class="panel-title">{{__('Mockup Information')}}</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-lg-2 control-label">{{__('Mockup Name')}}</label>
						<div class="col-lg-7">
							<input type="text" class="form-control" name="name" value="{{$mockup->name}}" required>
						</div>
					</div>
                    <div class="form-group" id="category">
                        <label class="col-lg-2 control-label">{{__('Category')}}</label>
                        <div class="col-lg-7">
                            <select class="form-control demo-select2-placeholder" name="category_id" id="category_id" required>
								<option>Select an option</option>
								@foreach($categories as $category)
									<option value="{{$category->id}}" <?php if($mockup->category_id == $category->id) echo "selected"; ?> >{{__($category->name)}}</option>
								@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="subcategory">
                        <label class="col-lg-2 control-label">{{__('Subcategory')}}</label>
                        <div class="col-lg-7">
                            <select class="form-control demo-select2-placeholder" name="subcategory_id" id="subcategory_id" required>

                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="subsubcategory">
                        <label class="col-lg-2 control-label">{{__('Sub Subcategory')}}</label>
                        <div class="col-lg-7">
                            <select class="form-control demo-select2-placeholder" name="subsubcategory_id" id="subsubcategory_id">

                            </select>
                        </div>
                    </div>
					
				</div>
			</div>
			<div class="panel">
				<div class="panel-heading bord-btm">
					<h3 class="panel-title">{{__('Product Images')}}</h3>
				</div>
				<div class="panel-body">
					@for($i = 1; $i <= 3; $i++)
						<div class="alert alert-danger" id="preview_error_{{$i}}" style="display:none">Height and Width must be (450x550).</div>

						<div class="form-group">
							<div class="col-lg-4 text-center">
								@php
									$optional = '';
									$required = '';
								@endphp
								
								@if ($i != 1)
									@php
										$optional = 'Optional';
									@endphp
								@else 
									@php
										$required = 'required';
									@endphp
								@endif
								
                                
                                @php
                                    if($i == 1){
                                        $p = $mockup->preview_1;
                                    }elseif($i == 2){
                                        $p = $mockup->preview_2;
                                    }elseif($i == 3){
                                        $p = $mockup->preview_3;
                                    }
                                    if($p != null){
                                        $prev = json_decode($p);
                                        $width = $prev ? $prev->width : ''; 
                                        $height = $prev ? $prev->height : ''; 
                                        $top = $prev ? $prev->top : ''; 
                                        $left = $prev ? $prev->left : ''; 
                                        $name = $prev ? $prev->name : ''; 
                                    }
                                @endphp
								<div>
									{{__('Preview '.$i)}}  <small>({{$optional}})</small><br>
									<small>max(450x550)</small>
								</div>
								<div style="margin-top:10px">
									<input type="file" class="form-control" name="preview_{{$i}}" id="input_preview_{{$i}}" onchange="image_preview(this,{{$i}})"> 
									<table>
										<tr>
											<td>left</td>
											<td> <input {{$required}} type="number" class="form-control" name="left_preview_{{$i}}" value="{{$left}}" id="left_preview_{{$i}}" placeholder="left" onmousewheel="preview_drawing_area({{$i}})" onkeyup="preview_drawing_area({{$i}})"></td>
										</tr>
										<tr>
											<td>top</td>
											<td><input {{$required}} type="number" class="form-control" name="top_preview_{{$i}}" value="{{$top}}" id="top_preview_{{$i}}" placeholder="top" onmousewheel="preview_drawing_area({{$i}})" onkeyup="preview_drawing_area({{$i}})"></td>
										</tr>
										<tr>
											<td>height</td>
											<td><input {{$required}} type="number" class="form-control" name="height_preview_{{$i}}" value="{{$height}}" id="height_preview_{{$i}}" placeholder="height" onmousewheel="preview_drawing_area({{$i}})" onkeyup="preview_drawing_area({{$i}})"></td>
										</tr>
										<tr>
											<td>width</td>
											<td><input {{$required}} type="number" class="form-control" name="width_preview_{{$i}}" value="{{$width}}" id="width_preview_{{$i}}" placeholder="width" onmousewheel="preview_drawing_area({{$i}})" onkeyup="preview_drawing_area({{$i}})"></td>
										</tr>
										<tr>
											<td>Preview Name</td>
											<td><input {{$required}} type="text" class="form-control" name="name_preview_{{$i}}"  value="{{$name}}" id="name_preview_{{$i}}" placeholder="name"></td>
										</tr>
									</table>  
								</div> 
							</div> 
							<div class="col-lg-8" id="image_preview_{{$i}}">
								<div style="position: relative">
                                    @if($p != null)
                                        @php
                                            $prev = json_decode($p);
                                            $image = $prev ? $prev->image : ''; 
                                            $width = $prev ? $prev->width .'px' : ''; 
                                            $height = $prev ? $prev->height .'px' : ''; 
                                            $top = $prev ? $prev->top .'px' : ''; 
                                            $left = $prev ? $prev->left .'px' : ''; 
                                        @endphp 
                                        <img src="{{asset($image)}}" alt=""  style="background: white;">
                                        <div class="drawing-area" style="width: {{$width}};height:{{$height}};top: {{$top}}; left: {{$left}};position: absolute; z-index: 10;border:1px dotted purple">	
                                        </div>
                                    @else  
                                        <div class="drawing-area" style="width: 200px;height:400px;top: 60px; left: 122px;position: absolute; z-index: 10;border:1px dotted purple;display:none">	
                                        </div>
                                        <img src="" alt=""  style="background: white;">
                                    @endif
								</div>
							</div>
						</div>
						<hr>
					@endfor
				</div>
			</div>
			<div class="panel">
				<div class="panel-heading bord-btm">
					<h3 class="panel-title">{{__('Product Videos')}}</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-lg-2 control-label">{{__('Video Provider')}}</label>
						<div class="col-lg-7">
							<select class="form-control demo-select2-placeholder" name="video_provider" id="video_provider">
								<option value="youtube" <?php if($mockup->video_provider == 'youtube') echo "selected";?> >{{__('Youtube')}}</option>
								<option value="dailymotion" <?php if($mockup->video_provider == 'dailymotion') echo "selected";?> >{{__('Dailymotion')}}</option>
								<option value="vimeo" <?php if($mockup->video_provider == 'vimeo') echo "selected";?> >{{__('Vimeo')}}</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">{{__('Video Link')}}</label>
						<div class="col-lg-7">
							<input type="text" class="form-control" name="video_link" value="{{$mockup->video_link}}">
						</div>
					</div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-heading bord-btm">
					<h3 class="panel-title">{{__('Product Variation')}}</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="col-lg-2">
							<input type="text" class="form-control" value="{{__('Colors')}}" >
						</div>
						<div class="col-lg-7">
							<select class="form-control color-var-select" name="colors[]" id="colors" multiple required>
								@foreach ($colors as $key => $color)
									<option value="{{ $color->code }}" <?php if(in_array($color->code, json_decode($mockup->colors))) echo 'selected'?> >{{ $color->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-2">
							<label class="switch" style="margin-top:5px;">
								<input value="1" type="checkbox" name="colors_active" <?php if(count(json_decode($mockup->colors)) > 0) echo "checked";?> >
								<span class="slider round"></span>
							</label>
						</div>
					</div>

					<div class="form-group">
						<div class="col-lg-2">
							<input type="text" class="form-control" value="{{__('Attributes')}}" disabled>
						</div>
	                    <div class="col-lg-7">
	                        <select name="choice_attributes[]" id="choice_attributes" class="form-control demo-select2" multiple data-placeholder="Choose Attributes">
								@foreach ($attributes as $key => $attribute)
									<option value="{{ $attribute->id }}" @if($mockup->attributes != null && in_array($attribute->id, json_decode($mockup->attributes, true))) selected @endif>{{ $attribute->name }}</option>
								@endforeach
	                        </select>
	                    </div>
	                </div>

					<div>
						<p>Choose the attributes of this product and then input values of each attribute</p>
						<br>
					</div>

					<div class="customer_choice_options" id="customer_choice_options">
						@foreach (json_decode($mockup->choice_options) as $key => $choice_option)
							<div class="form-group">
								<div class="col-lg-2">
									<input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
									<input type="text" class="form-control" name="choice[]" value="{{ \App\Models\Attribute::find($choice_option->attribute_id)->name }}" placeholder="Choice Title" disabled>
								</div>
								<div class="col-lg-7">
									<input type="text" class="form-control" name="choice_options_{{ $choice_option->attribute_id }}[]" placeholder="Enter choice values" value="{{ implode(',', $choice_option->values) }}" data-role="tagsinput" onchange="update_sku()">
								</div>
								<div class="col-lg-2">
									<button onclick="delete_row(this)" class="btn btn-danger btn-icon"><i class="demo-psi-recycling icon-lg"></i></button>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-heading bord-btm">
					<h3 class="panel-title">{{__('Product price + stock')}}</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-lg-2 control-label">{{__('Price')}}</label>
						<div class="col-lg-7">
							<input type="number" min="0" value="{{$mockup->purchase_price}}" step="0.01" name="unit_price" class="form-control" required>
						</div>
					</div>
					<br>
					<div class="sku_combination" id="sku_combination">

					</div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-heading bord-btm">
					<h3 class="panel-title">{{__('Product Description')}}</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-lg-2 control-label">{{__('Description')}}</label>
						<div class="col-lg-9">
							<textarea class="editor" name="description">{{$mockup->description}}</textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="mar-all text-right">
				<button type="submit" name="button" id="submit-button" class="btn btn-info btn-rounded btn-block">{{ __('Update Mockup') }}</button>
			</div>
		</form>
	</div>
</div>

@endsection


@section('script')

<script type="text/javascript">
	function add_more_customer_choice_option(i, name){
		$('#customer_choice_options').append('<div class="form-group"><div class="col-lg-2"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="Choice Title" readonly></div><div class="col-lg-7"><input type="text" class="form-control" name="choice_options_'+i+'[]" placeholder="Enter choice values" data-role="tagsinput" onchange="update_sku()"></div></div>');

		$("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
	}

	function preview_drawing_area(i){
		var left = $('#left_preview_'+i+'').val();
		var top = $('#top_preview_'+i+'').val();
		var width = $('#width_preview_'+i+'').val();
		var height = $('#height_preview_'+i+'').val();
		$('#image_preview_'+i+' .drawing-area').css({
													'top':top+'px',
													'left':left+'px',
													'width':width+'px',
													'height':height+'px'
												});
		
	}

	function image_preview(image_input,i){
		var input = image_input;
		var url = $(image_input).val();
		var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
		if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
			{
			var reader = new FileReader();
			reader.onload = function (e) {
				//Initiate the JavaScript Image object.
				var image = new Image();
				//Set the Base64 string return from FileReader as source.
				image.src = e.target.result;
				image.onload = function () {
					var height = this.height;
					var width = this.width;
					if ( width != 450 || height != 550) { 
						$('#preview_error_'+i).css('display','block');  
						$('#submit-button').attr('disabled',true);
						$(image_input).val(null);
						return false;
					}

					$('#preview_error_'+i).css('display','none');  
					$('#image_preview_'+i+' img').attr('src', e.target.result);  
					$('#image_preview_'+i+' .drawing-area').css('display','block')
						$('#submit-button').attr('disabled',false);
					return true;
				};
			}
			reader.readAsDataURL(input.files[0]);
		}
	} 
	
	$('input[name="colors_active"]').on('change', function() {
	    if(!$('input[name="colors_active"]').is(':checked')){
			$('#colors').prop('disabled', true);
		}
		else{
			$('#colors').prop('disabled', false);
		}
		update_sku();
	});

	$('#colors').on('change', function() {
	    update_sku();
	});

	$('input[name="unit_price"]').on('keyup', function() {
	    update_sku();
	});
	$('input[name="purchase_price"]').on('keyup', function() {
	    update_sku();
	});

	$('input[name="name"]').on('keyup', function() {
	    update_sku();
	});

	function delete_row(em){
		$(em).closest('.form-group').remove();
		update_sku();
	}

	function update_sku(){
		$.ajax({
			type:"POST",
			url:'{{ route('mockups.sku_combination_edit') }}',
			data:$('#choice_form').serialize(),
			success: function(data){
				$('#sku_combination').html(data);
				if (data.length > 1) {
					$('#quantity').hide();
				}
				else {
					$('#quantity').show();
				}
			}
		});
	}

    $(document).ready(function(){
	    get_subcategories_by_category();
        update_sku();
    });
	function get_subcategories_by_category(){
		var category_id = $('#category_id').val();
		$.post('{{ route('subcategories.get_subcategories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
		    $('#subcategory_id').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#subcategory_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		        $('.demo-select2').select2();
		    }
		    get_subsubcategories_by_subcategory();
		});
	}

	function get_subsubcategories_by_subcategory(){
		var subcategory_id = $('#subcategory_id').val();
		$.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){
		    $('#subsubcategory_id').html(null);
			$('#subsubcategory_id').append($('<option>', {
				value: null,
				text: null
			}));
		    for (var i = 0; i < data.length; i++) {
		        $('#subsubcategory_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		        $('.demo-select2').select2();
		    }
		    //get_brands_by_subsubcategory();
			//get_attributes_by_subsubcategory();
		});
	}

	function get_attributes_by_subsubcategory(){
		var subsubcategory_id = $('#subsubcategory_id').val();
		$.post('{{ route('subsubcategories.get_attributes_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
		    $('#choice_attributes').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#choice_attributes').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		    }
			$('.demo-select2').select2();
		});
	} 
	
	$('#category_id').on('change', function() {
	    get_subcategories_by_category();
	});

	$('#subcategory_id').on('change', function() {
	    get_subsubcategories_by_subcategory();
	});

	$('#subsubcategory_id').on('change', function() {
	    // get_brands_by_subsubcategory();
		//get_attributes_by_subsubcategory();
	});

	$('#choice_attributes').on('change', function() {
		$('#customer_choice_options').html(null);
		$.each($("#choice_attributes option:selected"), function(){
			//console.log($(this).val());
            add_more_customer_choice_option($(this).val(), $(this).text());
        });
		update_sku();
	});


</script>

@endsection