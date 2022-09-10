@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('common_questions.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Question')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Common Questions')}}</h3> 
    </div>
    <div class="panel-body">
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Question')}}</th>
                    <th>{{__('Answer')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($common_questions as $key => $row)
                    <tr>
                        <td>{{ ($key+1) + ($common_questions->currentPage() - 1)*$common_questions->perPage() }}</td>
                        <td>{{$row->question}}</td> 
                        <td><?php echo $row->answer; ?></td> 
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('common_questions.edit', encrypt($row->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('common_questions.destroy', $row->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                </ul> 
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $common_questions->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection 
