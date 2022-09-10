@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 pull-right">
        <a href="{{ route('customer.create')}}" class="btn btn-rounded btn-lg btn-info pull-right">{{__('Add Customer')}}</a>
    </div>
</div>

<br>
    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="panel">
        <div class="text-center" style="padding:10px">
            <h3>{{__('Customer List')}}</h3> 
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover mar-no demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                <tr class="table-tr-color">
                    <th>#</th> 
                    <th>{{__('Name')}}</th> 
                    <th>{{__('Phone Number')}}</th>
                    <th>{{__('Address')}}</th> 
                    <th>{{__('Email')}}</th>     
                    <th>{{__('Orders')}}</th>
                    <th>{{__('Date Created')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $customer)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$customer->name}}</td> 
                            <td>{{$customer->phone}}</td>
                            <td>{{$customer->address}}</td> 
                            <td>{{$customer->email}}</td> 
                            <td>{{$customer->orders ? count($customer->orders) : ''}} </td> 
                            <td>{{$customer->created_at}}</td> 
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{route('deliveryman.edit', $customer->id)}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('deliveryman.delete', $customer->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                    </ul>
                                </div> 
                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection 
