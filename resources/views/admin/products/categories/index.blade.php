@extends('layouts.app') 
@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('categories.create')}}" class="btn btn-rounded btn-info btn-lg pull-right">{{__('Add New Category')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="text-center" style="padding:10px">
        <h3>{{__('Categories')}}</h3> 
    </div>
    <div class="panel-body">
        <table class="table table-striped demo-dt-basic mar-no" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Banner')}}</th>
                    <th>{{__('Icon')}}</th>
                    <th>{{__('Featured')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>{{__($category->name)}}</td>
                        <td><img loading="lazy"  class="img-md" src="{{ asset($category->banner) }}" alt="{{__('banner')}}"></td>
                        <td><img loading="lazy"  class="img-xs" src="{{ asset($category->icon) }}" alt="{{__('icon')}}"></td>
                        <td><label class="switch">
                            <input onchange="update_featured(this)" value="{{ $category->id }}" type="checkbox" <?php if($category->featured == 1) echo "checked";?> >
                            <span class="slider round"></span></label></td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('categories.edit', encrypt($category->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('categories.destroy', $category->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
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

@section('script')
    <script type="text/javascript">
        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('categories.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Featured categories updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
