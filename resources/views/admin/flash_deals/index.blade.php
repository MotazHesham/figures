@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('flash_deals.create')}}" class="btn btn-rounded btn-info btn-lg pull-right">{{__('Add New Flash Deal Products')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading" style="padding:10px">
        <h3 class="text-center">{{__('Flash Deals')}}</h3> 
    </div>
    <div class="panel-body">
        <table class="table demo-dt-basic table-striped  mar-no" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th>{{__('Title')}}</th>
                    <th>{{ __('Banner') }}</th>
                    <th>{{ __('Start Date') }}</th>
                    <th>{{ __('End Date') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Featured') }}</th>
                    <th>{{ __('Page Link') }}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flash_deals as $key => $flash_deal)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>{{$flash_deal->title}}</td>
                        <td><img class="img-md" src="{{ asset($flash_deal->banner) }}" alt="banner"></td>
                        <td>{{ date('d-m-Y', $flash_deal->start_date) }}</td>
                        <td>{{ date('d-m-Y', $flash_deal->end_date) }}</td>
                        <td><label class="switch">
                            <input onchange="update_flash_deal_status(this)" value="{{ $flash_deal->id }}" type="checkbox" <?php if($flash_deal->status == 1) echo "checked";?> >
                            <span class="slider round"></span></label></td>
                        <td><label class="switch">
                            <input onchange="update_flash_deal_feature(this)" value="{{ $flash_deal->id }}" type="checkbox" <?php if($flash_deal->featured == 1) echo "checked";?> >
                            <span class="slider round"></span></label></td>
                        <td>{{ url('flash-deal/'.$flash_deal->slug) }}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('flash_deals.edit', encrypt($flash_deal->id))}}">{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('flash_deals.destroy', $flash_deal->id)}}');">{{__('Delete')}}</a></li>
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
        function update_flash_deal_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('flash_deals.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }
        function update_flash_deal_feature(el){
            if(el.checked){
                var featured = 1;
            }
            else{
                var featured = 0;
            }
            $.post('{{ route('flash_deals.update_featured') }}', {_token:'{{ csrf_token() }}', id:el.value, featured:featured}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
