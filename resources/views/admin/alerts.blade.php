@extends('layouts.app')

@section('content') 

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading" style="padding:10px">
        <h3 class="text-center"> </h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Content')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alerts as $key => $alert)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>
                            <span @if($alert->seen  == 0 ) style="color:green" @endif>
                                {{$alert->alert_text}}
                            </span>    
                        </td>
                        <td> 
                            @if($alert->type == 'history')
                                <a style="cursor: pointer;background:#45B39D"  onclick="show_details('{{$alert->alert_link}}')" title="{{__('Order Details')}}">أظهار الصور</a> 
                            @else 
                                <a onclick="notification_seen({{$alert->id}})" href="{{ $alert->alert_link}}" class="btn btn-info btn-rounded"> {{__('عرض')}}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>


<div class="modal fade" id="playlist_modal" tabindex="-1" role="dialog" aria-labelledby="playlist_modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">  
        function show_details(order_num){ 
            $.post('{{ route('playlist.show') }}', {_token:'{{ csrf_token() }}', order_num:order_num}, function(data){
                $('#playlist_modal').modal('show');
                $('#playlist_modal #modal-content').html(data);
            });
        } 
        
    </script>
@endsection