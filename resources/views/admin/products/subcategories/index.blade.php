@extends('layouts.app') 
@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('subcategories.create')}}" class="btn btn-rounded btn-info btn-lg pull-right">{{__('Add New Subcategory')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading " style="padding:10px">
        <h3 class="text-center">{{__('Sub-Categories')}}</h3> 
    </div>
    <div class="panel-body">
        <table class="table table-striped demo-dt-basic mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Subcategory')}}</th>
                    <th>{{__('Category')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subcategories as $key => $subcategory)
                    @if ($subcategory->category != null)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{__($subcategory->name)}}</td>
                            <td>{{$subcategory->category->name}}</td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{route('subcategories.edit', encrypt($subcategory->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('subcategories.destroy', $subcategory->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                    </ul> 
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table> 
    </div>
</div>

@endsection
