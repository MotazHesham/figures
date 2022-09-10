@extends('layouts.app')

@section('content')

    <div class="col-lg-8 col-lg-offset-1">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('General Settings')}}</h3>
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('generalsettings.update',$generalsetting->id ) }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{__('Site Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="name" name="name" value="{{ $generalsetting->site_name }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="address">{{__('Address')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="address" name="address" value="{{ $generalsetting->address }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{__('Footer Text')}}</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="4" name="description" required>{{$generalsetting->description}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="phone">{{__('Phone')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="phone" name="phone" value="{{ $generalsetting->phone }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="email">{{__('Email')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="email" name="email" value="{{ $generalsetting->email }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="facebook">{{__('Facebook')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="facebook" name="facebook" value="{{ $generalsetting->facebook }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="instagram">{{__('Instagram')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="instagram" name="instagram" value="{{ $generalsetting->instagram }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="youtube">{{__('Youtube')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="youtube" name="youtube" value="{{ $generalsetting->youtube }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="telegram">{{__('Telegram')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="telegram" name="telegram" value="{{ $generalsetting->telegram }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="whatsapp">{{__('Whatsapp')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="whatsapp" name="whatsapp" value="{{ $generalsetting->whatsapp }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="linkedin">{{__('LinkedIn')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="linkedin" name="linkedin" value="{{ $generalsetting->linkedin }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="video_instructions">{{__('Video Instructions Link')}}</label>
                        <div class="col-sm-9">
                            <input type="text" id="video_instructions" name="video_instructions" value="{{ $generalsetting->video_instructions }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="delivery_system">سستيم الشحن</label>
                        <div class="col-sm-3 select" style="min-width: 200px;">
                            <select class="form-control demo-select2" name="delivery_system">
                                <option @if($generalsetting->delivery_system == "wasla") selected @endif value="wasla" >Wasla</option>
                                <option @if($generalsetting->delivery_system == "ebtekar") selected @endif value="ebtekar" >Figures</option>
                            </select>
                        </div>

                        <label class="col-sm-3 control-label" for="admin_sidenav">Admin SideNavbar</label>
                        <div class="col-sm-3 select" style="min-width: 200px;">
                            <select class="form-control demo-select2" name="admin_sidenav">
                                <option @if($generalsetting->admin_sidenav == "sm") selected @endif value="sm" >Alwayes closed</option>
                                <option @if($generalsetting->admin_sidenav == "lg") selected @endif value="lg" >Alwayes open</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label" >{{__('Date && Time Formats')}}</label>
                        <div class="col-sm-3 select" style="min-width: 200px;">
                            <select class="form-control demo-select2" name="date">
                                <option @if($generalsetting->date == "F j, Y") selected @endif value="F j, Y" >February 13, 2021</option>
                                <option @if($generalsetting->date == "Y-m-d") selected @endif  value="Y-m-d" >2021-02-13</option>
                                <option @if($generalsetting->date == "m/d/y") selected @endif  value="m/d/y" >02/13/21</option>
                                <option @if($generalsetting->date == "d-m-Y") selected @endif  value="d-m-Y" >13-02-2021</option>
                                <option @if($generalsetting->date == "d/m/Y") selected @endif  value="d/m/Y" >13/02/2021</option>
                                <option @if($generalsetting->date == "d.m.Y") selected @endif   value="d.m.Y" >13.02.2021</option>
                            </select>
                        </div>
                        <div class="col-sm-3 select" style="min-width: 200px;">
                            <select class="form-control demo-select2" name="time">
                                <option @if($generalsetting->time == "g:i a") selected @endif value="g:i a" >5:16 pm</option>
                                <option @if($generalsetting->time == "H:i:s") selected @endif value="H:i:s" >17:16:18</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" >قوائم التشغيل</label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <span>&nbsp;</span>
                                    <div class="" style="min-width: 160px;margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="designer_id" id="designer_id" required>
                                            <option value="">أختر الديزاينر</option>
                                            @foreach($staffs as $staff)
                                            <option value="{{$staff->id}}" @if($generalsetting->designer_id == $staff->id) selected @endif>
                                                        {{$staff->email}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <span>&nbsp;</span>
                                    <div class="" style="min-width: 160px;margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="manifacturer_id" id="manifacturer_id" required>
                                            <option value="">اختر المصنع</option>
                                            @foreach($staffs as $staff)
                                                <option value="{{$staff->id}}" @if($generalsetting->manifacturer_id == $staff->id) selected @endif>
                                                        {{$staff->email}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <span>&nbsp;</span>
                                    <div class="" style="min-width: 160px;margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="preparer_id" id="preparer_id" required>
                                            <option value="">اختر المجهز</option>
                                            @foreach($staffs as $staff)
                                                <option value="{{$staff->id}}" @if($generalsetting->preparer_id == $staff->id) selected @endif>
                                                        {{$staff->email}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="welcome_message">{{__('Welcome Message For Seller')}}</label>
                        <div class="col-sm-9">
                            <textarea name="welcome_message" class="form-control" id="welcome_message" cols="55" rows="5">{{ $generalsetting->welcome_message }}</textarea>
                        </div>
                    </div>


                    <div class="panel">
                        <div class="panel-heading bord-btm">
                            <h3 class="panel-title text-center">أراء العملاء</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-lg-7">
                                    <div id="photos">
                                        @if(is_array(json_decode($generalsetting->photos)))
                                            @foreach (json_decode($generalsetting->photos) as $key => $photo)
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
                    </div>


                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple btn-block" type="submit">{{__('Save')}}</button>
                </div>
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>

@endsection

@section('script')
<script>
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
