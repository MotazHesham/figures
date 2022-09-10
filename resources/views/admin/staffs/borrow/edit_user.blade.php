@extends('layouts.app')

@section('content')

<div class="col-lg-12">

        <div class="row">
            <div class="col-md-4">
                <div class="panel" style="padding: 10px">
                    <div class="panel-heading text-center">
                        <h3 class="panel-title">{{__('Update')}}</h3>
                    </div>
                    <!--Horizontal Form-->
                    <!--===================================================-->
                    <form class="form-horizontal" action="{{ route('borrow_user.update', $borrow_user->id) }}" method="POST" enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PATCH">
                        @csrf
                        <div class="panel-body">
            
                            @include('admin.partials.error_message')
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">{{__('Name')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" id="name" name="name" class="form-control" required value="{{ $borrow_user->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="email">{{__('Email')}}</label>
                                <div class="col-sm-10">
                                    <input type="email" id="email" name="email" class="form-control" required value="{{ $borrow_user->email }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="phone">{{__('Phone Number')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" id="phone" name="phone" class="form-control" required value="{{ $borrow_user->phone }}">
                                </div>
                            </div>
                            
                        </div>
                        <div class="panel-footer text-right">
                            <button class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button>
                        </div>
                    </form>
                    <!--===================================================-->
                    <!--End Horizontal Form-->
                </div> 
            </div>
            <div class="panel" style="padding: 10px">
                <div class="panel-body">
                    <div class="col-md-8"> 
                        <form action="{{route('borrow.status_all')}}" method="POST">
                            @csrf
                            <input type="submit" value="سداد" class="btn btn-success btn-lg text-center" id="" style=" padding: 6px 25px; margin: 19px;">
                            <table class="table table-striped demo-dt-basic mar-no table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="table-tr-color">
                                        <th>#</th> 
                                        <th>{{__('Borrow')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Date Created')}}</th>
                                        <th width="10%">{{__('Options')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrow_user->borrow as $key => $raw)
                                        <tr>
                                            <td> 
                                                @if($raw->status == 0)
                                                    <input class="form-control" type="checkbox" name="borrows[]" value="{{$raw->id}}">
                                                @endif
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
            </div>
    </div>
</div>

@endsection
