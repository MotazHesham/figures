@extends('layouts.app') 
@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('subsubcategories.create')}}" class="btn btn-rounded btn-info btn-lg pull-right">{{__('Add New Sub Subcategory')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading " style="padding: 10px">
        <h3 class="text-center">{{__('Sub-Sub-categories')}}</h3>  
    </div>
    <div class="panel-body">
        <table class="table table-striped demo-dt-basic mar-no" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th>{{__('Sub Subcategory')}}</th>
                    <th>{{__('Subcategory')}}</th>
                    <th>{{__('Category')}}</th> 
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subsubcategories as $key => $subsubcategory)
                    @if ($subsubcategory->subcategory != null && $subsubcategory->subcategory->category != null)
                        <tr>
                            <td>{{ ($key+1)  }}</td>
                            <td>{{__($subsubcategory->name)}}</td>
                            <td>{{$subsubcategory->subcategory->name}}</td>
                            <td>{{$subsubcategory->subcategory->category->name}}</td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{route('subsubcategories.edit', encrypt($subsubcategory->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('subsubcategories.destroy', $subsubcategory->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
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
