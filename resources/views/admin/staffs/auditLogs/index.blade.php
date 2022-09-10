@extends('layouts.app')

@section('content')


<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <form class="" id="sort_audit_logs" action="" method="GET">
        <div class="box-inline pad-rgt pull-right">
            <div class="select" style="min-width: 200px;">
                <select class="form-control demo-select2" name="sort_logs"  id="sort_audit_logs_select" >
                    <option value="">Choose Model</option>
                    <option value="App\Models\ReceiptCompany">Receipt Company</option>
                    <option value="App\Models\Receipt_client">Receipt Client</option>
                    <option value="App\Models\Receipt_social">Receipt Client</option>
                    <option value="App\Models\Receipt_outgoings">Receipt Outgoings</option>
                    <option value="App\Models\Order">Orders</option>
                    <option value="App\Models\SubSubCategory">SubSubCategory</option>
                    <option value="App\Models\SubCategory">SubCategory</option>
                    <option value="App\Models\Category">Category</option>
                    <option value="App\Models\Product">Product</option>
                    <option value="App\Models\User">User</option>
                    <option value="App\Models\FlashDeal">FlashDeal</option>
                    <option value="App\Models\Task">Task</option>
                </select>
            </div>
        </div>
    </form>
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Audit Log List')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Description')}}</th>
                    <th>{{__('Subject ID')}}</th>
                    <th>{{__('Subject Type')}}</th>
                    <th>{{__('User ID')}}</th>
                    <th>{{__('Host')}}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($audits as $key => $audit)
                    <tr>
                        <td>{{$audit->id}}</td>
                        <td>{{$audit->description}}</td>
                        <td>{{$audit->subject_id}}</td>
                        <td>{{$audit->subject_type}}</td>
                        <td>{{$audit->user->email ?? ''}}</td>
                        <td>{{$audit->host}}</td>
                        <td><a href="{{route('auditlogs.show',$audit->id)}}" class="btn btn-purple">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $audits->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    $(document).ready(function(){
        $('#sort_audit_logs_select').on('change',function(){
            $('#sort_audit_logs').submit();
        });
    });
</script>

@endsection