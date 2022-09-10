@php
    if(auth()->user()->user_type == 'delivery_man'){
        $extend = 'delivery_man.app';
    }else{
        $extend = 'layouts.app';
    }
@endphp

@extends($extend)  

@section('content')

    <div class="col-lg-6 ">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('Profile')}}</h3>
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PATCH">
            	@csrf
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="name">{{__('Name')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="{{__('Name')}}" name="name" value="{{ Auth::user()->name }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="name">{{__('Email')}}</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" placeholder="{{__('Email')}}" name="email" value="{{ Auth::user()->email }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="new_password">{{__('New Password')}}</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" placeholder="{{__('New Password')}}" name="new_password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="confirm_password">{{__('Confirm Password')}}</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" placeholder="{{__('Confirm Password')}}" name="confirm_password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="avatar">{{__('Avatar')}} <small>(120x80)</small></label>
                        <div class="col-sm-10">
                            <input type="file" id="avatar" name="avatar" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                </div>
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>
    <div class="col-lg-6 ">
        <div class="panel">
            <div class="panel-heading">
                @if(auth()->user()->wasla_token == null)
                    <h3 class="panel-title">{{__('تسجبل الدخول لوصلة')}}</h3>
                @else 
                    <h3 class="panel-title">{{__('بيانات حساب وصلة')}}</h3>
                @endif
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            
            <form action="{{route('wasla.login')}}" method="POST">
                @csrf   
                <div class="panel-body">
                    <div class="row">
                        
                        @if(auth()->user()->wasla_token == null)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="{{__('Email')}}" id=""> 
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}" id=""> 
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="تسجيل الدخول" class="btn btn-info" name="" id=""> 
                                </div>
                            </div>
                        @else
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{$data['logo'] ?? ''}}" alt="" width="80" height="80" style="border-radius: 50%;box-shadow: 1px 1px 18px #b9b0b0;">
                                        <br><br>
                                        <a href="{{route('wasla.logout')}}" class="btn btn-danger btn-rounded">تسجيل الخروج</a>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="badge badge-default">اسم الشركة</span> 
                                        {{$data['company_name'] ?? ''}} 

                                        <br> <br>
                                        
                                        <span class="badge badge-default">البريد الألكتروني</span> 
                                        {{$data['email'] ?? ''}} 

                                        <br> <br>
                                        
                                        <span class="badge badge-default">المجال</span> 
                                        {{$data['work_type'] ?? ''}} 

                                        <br> <br>
                                        
                                        <span class="badge badge-default">رقم الهاتف</span> 
                                        {{$data['phone'] ?? ''}} 

                                        <br> <br>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span class="badge badge-default">التخفيض</span> 
                                                <br> 
                                                {{$data['discount'] ?? ''}} 
                                            </div>
                                            <div class="col-md-4"> 
                                                <span class="badge badge-default">سعر المرتجعات</span> 
                                                <br> 
                                                {{$data['refund_price'] ?? ''}} 
                                            </div>
                                            <div class="col-md-4">
                                                <span class="badge badge-default">سعر المهمة</span> 
                                                <br> 
                                                {{$data['mission_price'] ?? ''}} 
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="height: 400px; overflow-x: hidden; overflow-y: scroll;"> 
                                <h5>سعر توصيل المحافظات</h5>
                                <table class="mt-3 table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>الأسم</th>
                                            <th>السعر</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($data)
                                        @if($data['countries'])
                                            @foreach($data['countries'] as $key => $row) 
                                                <tr>
                                                    <td>{{$row['name']}}</td>
                                                    <td>{{$row['cost']}}</td> 
                                                </tr>
                                            @endforeach
                                        @endif
                                        @endif
                                    </tbody>
                                </table>  
                            </div>
                        @endif
                    </div>
                </div> 
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>

@endsection
