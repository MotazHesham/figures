@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('mockups.create')}}" class="btn btn-rounded btn-info btn-lg pull-right">{{__('Add New Mockup')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading" style="padding:10px">
        <h3 class="text-center">{{__('Mockups')}}</h3>
    </div>
    <div class="panel-body"> 
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>{{__('ID')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Image')}}</th>
                    <th>{{__('Colors')}}</th>
                    <th>{{__('Attributes')}}</th>
                    <th>{{__('Category')}}</th>
                    <th>{{__('Price')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mockups as $key => $mockup)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>{{$mockup->name}}</td>
                        <td>
                            @if($mockup->preview_1 != null)
                                @php
                                    $prev_1 = json_decode($mockup->preview_1);
                                    $image_1 = $prev_1 ? $prev_1->image  : ''; 
                                @endphp
                                <img src="{{asset($image_1)}}" alt="" class="img-thumbnail" height="70" width="70">
                            @endif
                                                
                        </td>
                        <td>
                            @if ($mockup->colors != null)
                                <ul class="list-inline checkbox-color">
                                    @foreach (json_decode($mockup->colors) as $key => $color)
                                        <li style="width: 30px;height:30px;border-radius:50%;background:{{$color}}"></li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td>
                            @if ($mockup->choice_options != null)
                                @foreach (json_decode($mockup->choice_options) as $key => $choice)
                                    <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2">
                                        @foreach ($choice->values as $key => $value)
                                            <li> 
                                                <label>{{ $value }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            @endif
                        </td>
                        <td>
                            {{$mockup->category ? $mockup->category->name : ''}} <br>
                            {{$mockup->subcategory ? $mockup->subcategory->name : ''}} <br>
                            {{$mockup->subsubcategory ? $mockup->subsubcategory->name : ''}}
                        </td>
                        <td>{{single_price($mockup->purchase_price)}}</td>
                        <td> 
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('mockups.edit', encrypt($mockup->id))}}">{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('mockups.destroy', $mockup->id)}}');">{{__('Delete')}}</a></li>
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
