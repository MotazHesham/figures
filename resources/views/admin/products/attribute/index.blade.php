@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('attributes.create')}}" class="btn btn-rounded btn-info btn-lg pull-right">{{__('Add New Attribute')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading" style="padding:10px">
        <h3 class="text-center">{{__('Attributes')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Name')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attributes as $key => $attribute)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$attribute->name}}</td>
                        <td>
                            @if($attribute->name != 'unit')
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{route('attributes.edit', encrypt($attribute->id))}}">{{__('Edit')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('attributes.destroy', $attribute->id)}}');">{{__('Delete')}}</a></li>
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection
