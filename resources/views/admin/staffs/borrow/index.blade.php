@extends('layouts.app') 
@section('content')


@include('admin.partials.error_message')
                
<div class="row">
    <div class="col-sm-7">
        <div class="panel">
            <form action="{{route('borrow.store')}}" style="padding:20px" method="POST">
                @csrf
                <input type="hidden" value="{{$password}}" name="password">
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-control demo-select2" name="borrow_user_id" id="borrow_user_id" required>
                            <option value="">{{__('Choose User')}}</option> 
                            @foreach($borrow_user as $raw)
                                <option value="{{$raw->id}}">
                                    {{$raw->email}}
                                </option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" min="0" step="0.1" name="amount" placeholder="{{__('Borrow')}}" required>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-success btn-rounded" value="{{__('Add New Borrow')}}">
                    </div>
                </div>
            </form>
            <div class="text-center" style="padding:10px">
                <h3 >{{__('Borrows')}}</h3> 
            </div>
            <div class="panel-body">  
                <form action="{{route('borrow.status_all')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$password}}" name="password">
                    <input type="submit" value="سداد" class="btn btn-success btn-lg text-center" id="" style=" padding: 6px 25px; margin: 19px;">
                    <table class="table table-striped demo-dt-basic mar-no table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr class="table-tr-color">
                                <th>#</th>
                                <th>{{__('User')}}</th>
                                <th>{{__('Borrow')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Date Created')}}</th>
                                <th width="10%">{{__('Options')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrow as $key => $raw)
                                <tr>
                                    <td>
                                        @if($raw->status == 0)
                                            <input class="form-control" type="checkbox" name="borrows[]" value="{{$raw->id}}">
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-default">{{$raw->borrow_user ? $raw->borrow_user->email : ""}}</span>
                                        <br>
                                        <span class="badge badge-default">{{$raw->borrow_user ? $raw->borrow_user->name : ""}}</span>
                                    </td>
                                    <td>{{single_price($raw->amount)}}</td>
                                    <td>
                                        @if($raw->status == 1)
                                            <span class="badge badge-success">تم السداد</span>
                                        @else
                                            <span class="badge badge-warning">لم يتم السداد</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{format_Date_Time(strtotime($raw->created_at))}}
                                    </td>
                                    <td>
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                {{__('Actions')}} <i class="dropdown-caret"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{route('borrow.edit', encrypt($raw->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                                <li><a onclick="confirm_modal('{{route('borrow.destroy', $raw->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                                @if($raw->status == 0)
                                                    <li><a onclick="return confirm('{{__('Are You Sure ?')}}');" href="{{route('borrow.status', encrypt($raw->id))}}"><i class="fa fa-dollar" style="color: #2ec184"></i>{{__('Borrow Paid')}}</a></li>
                                                @endif
                                            </ul> 
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> 
                </form>
            </div>
        </div>

        <hr>

        
        <div class="panel">
            <form action="{{route('subtract.store')}}" style="padding:20px" method="POST">
                @csrf
                <input type="hidden" value="{{$password}}" name="password">
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-control demo-select2" name="subtract_user_id" id="subtract_user_id" required>
                            <option value="">{{__('Choose User')}}</option> 
                            @foreach($borrow_user as $raw)
                                <option value="{{$raw->id}}">
                                    {{$raw->email}}
                                </option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" min="0" step="0.1" name="amount" placeholder="الخصم" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="reason" placeholder="السبب" required>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-success btn-rounded" value="أضافة الخصم">
                    </div>
                </div>
            </form>
            <div class="text-center" style="padding:10px">
                <h3 >الخصومات</h3> 
            </div>
            <div class="panel-body">   
                <table class="table table-striped demo-dt-basic mar-no table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="table-tr-color">
                            <th>#</th>
                            <th>{{__('User')}}</th>
                            <th>الخصم</th>
                            <th>السبب</th>
                            <th>{{__('Date Created')}}</th>
                            <th width="10%">{{__('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subtracts as $key => $raw)
                            <tr>
                                <td> 
                                    {{ $raw->id }}
                                </td>
                                <td>
                                    <span class="badge badge-default">{{$raw->subtract_user ? $raw->subtract_user->email : ""}}</span>
                                    <br>
                                    <span class="badge badge-default">{{$raw->subtract_user ? $raw->subtract_user->name : ""}}</span>
                                </td>
                                <td>{{single_price($raw->amount)}}</td>
                                <td>
                                    {{ $raw->reason }}
                                </td>
                                <td>
                                    {{format_Date_Time(strtotime($raw->created_at))}}
                                </td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                            {{__('Actions')}} <i class="dropdown-caret"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="{{route('subtract.edit', encrypt($raw->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                            <li><a onclick="confirm_modal('{{route('subtract.destroy', $raw->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li> 
                                        </ul> 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>  
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="panel">
            <form action="{{route('borrow_user.store')}}" style="padding:20px" method="POST">
                @csrf
                <input type="hidden" value="{{$password}}" name="password">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="{{__('Name')}}" required>
                    </div>
                    <div class="col-md-3">
                        <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="{{__('Email')}}" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="phone" value="{{old('phone')}}" placeholder="{{__('Phone')}}" required>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-purple btn-rounded" value="{{__('Add')}}">
                    </div>
                </div>
            </form>
            <div class="text-center" style="padding:10px">
                <h3 >{{__('Users')}}</h3> 
            </div>
            <div class="panel-body"> 
                <table class="table table-striped demo-dt-basic mar-no table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="table-tr-color">
                            <th>#</th>
                            <th>{{__('User')}}</th>
                            <th>مطلوب السداد</th>
                            <th width="10%">{{__('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrow_user as $key => $raw)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    <span class="badge badge-default">{{__('Name')}}</span> {{$raw->name}} <br>
                                    <span class="badge badge-default">{{__('Email')}}</span> {{$raw->email}} <br>
                                    <span class="badge badge-default">{{__('phone')}}</span> {{$raw->phone}} <br>
                                </td>
                                <td>{{single_price($raw->borrow->where('status',0)->sum('amount'))}}</td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                            {{__('Actions')}} <i class="dropdown-caret"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="{{route('borrow_user.edit', encrypt($raw->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                            <li><a onclick="confirm_modal('{{route('borrow_user.destroy', $raw->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                        </ul> 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
</div>

<br>



@endsection 

