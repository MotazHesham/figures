@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('countries.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Country')}}</a>
    </div>
</div>

<br>
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Countries')}}</h3>
            <div class="pull-right">
                <form action="" method="GET" id="sort_by_type">
                    <div class="row">
                        <div class="col-md-6">
                            <select name="type" id="" class="form-control" onchange="sort_by_type()">
                                <option value="">Select Type</option>
                                <option value="countries" @isset($type) @if($type == 'countries') selected @endif @endisset>{{__('Countries')}}</option>
                                <option value="districts" @isset($type) @if($type == 'districts') selected @endif @endisset>{{__('Districts')}}</option>
                                <option value="metro" @isset($type) @if($type == 'metro') selected @endif @endisset> {{__('Metro')}}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="الأسم" value="{{ $search }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Type')}}</th>
                        <th>{{__('Cost')}}</th>
                        <th>{{__('Status')}}</th>
                        <th width="10%">{{__('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $key => $country)
                        <tr>
                            <td>{{ ($key+1) + ($countries->currentPage() - 1)*$countries->perPage() }}</td>
                            <td>{{ $country->name }}</td>
                            <td>{{ __(ucfirst($country->type))}}</td>
                            <td>{{ $country->cost }}</td>
                            <td>
                            <label class="switch">
                                <input onchange="update_status(this)" value="{{ $country->id }}" type="checkbox" <?php if($country->status == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                            </td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{route('countries.edit', encrypt($country->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('countries.destroy', $country->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $countries->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('countries.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Country status updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function sort_by_type(){
            $('#sort_by_type').submit();
        }

    </script>
@endsection
