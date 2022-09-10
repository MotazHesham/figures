@extends('frontend.layouts.app')

@section('content')

@if(count($errors)>0)
@foreach($errors->all() as $error)
    <div class="alert alert-danger">
        {{$error}}
    </div>
@endforeach
@endif

@if(session('success'))
<div class="alert alert-success">
    {{session('success')}}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{session('error')}}
</div>
@endif
<section class="gry-bg py-4 profile">
    <div class="container">
        <div class=" cols-xs-space cols-sm-space cols-md-space">
            
                <div class="row">
                    <div class="col-8">
                        <div class="card no-border mb-5 mt-5">
                            <div class="card-header py-3">
                                <h4 class="mb-0 h6">Events</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Description') }}</th>
                                            <th>{{__('Date')}}</th>
                                            <th>{{__('Options')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($calender2) > 0)
                                            @foreach ($calender2 as $raw)
                                                <tr>
                                                    <td>{{ $raw->title }}</td>
                                                    <td>{{ $raw->description}}</td>
                                                    <td>{{format_date($raw->date)}}</td>
                                                    <td>
                                                        <a href="{{route('calender.delete',$raw->id)}}" class="btn btn-outline-danger" style="color:red">delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center pt-5 h4" colspan="100%">
                                                    <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                                <span class="d-block">{{ __('No events found.') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{$calender2->links()}}
                        </div>
                    </div>

                    <div class="col-4 mb-5 mt-5" data-toggle="modal" data-target="#exampleModalCenter">
                        <div class="dashboard-widget text-center plus-widget mt-4 c-pointer" >
                            <i class="la la-plus"></i>
                            <span class="d-block title heading-6 strong-400 c-base-1">{{ __('Add Event') }}</span>
                        </div>
                    </div>
                    
                    
                </div> 

            <div id="calendar"></div>
            <!-- Button trigger modal -->

            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="" action="{{ route('calender.addevent') }}" method="post">
                            @csrf
                            <div class="modal-body gry-bg px-3 pt-3">
                                <input type="hidden" name="id" value="{{$id}}"> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>{{__('Occasion Title')}} <span class="required-star">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control mb-3" name="title"  required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>{{__('Occasion Description')}} <span class="required-star">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control mb-3" name="description"  required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>{{__('Date')}} <span class="required-star">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control mb-3" name="date"  required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-base-1">{{__('Add Occasion')}}</button>
                            </div>
                        </form>

                        

                    </div>
                    </div>
                </div>
            </div> 
        </div>
    </div> 
</section>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        var myEvents = {!! json_encode($myEvents) !!};
        $('#calendar').evoCalendar({
            format: 'dd/MM/yyyy',
            theme: 'Royal Navy',
            calendarEvents: myEvents
        })
        
        
        $('#show_add_event').on('click',function(){
            $('#add_event').modal('show');
        });
    })
</script>
@endsection