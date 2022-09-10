@extends('layouts.app') 
@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('brands.create')}}" class="btn btn-rounded btn-lg btn-info pull-right">{{__('Add New Brand')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="text-center" style="padding:10px">
        <h3 >{{__('Brands')}}</h3> 
    </div>
    <div class="panel-body">
        <table class="table table-striped demo-dt-basic mar-no table-hover" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Logo')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $key => $brand)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$brand->name}}</td>
                        <td><img loading="lazy"  class="img-md" src="{{ asset($brand->logo) }}" alt="Logo"></td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('brands.edit', encrypt($brand->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('brands.destroy', $brand->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
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
