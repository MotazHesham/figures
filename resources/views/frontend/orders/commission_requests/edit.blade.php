@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Edit Commission Request')}}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        

                        @include('admin.partials.error_message')
                
                        <form class="form-horizontal" action="{{ route('orders.request_commission.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$commission_request->id}}"> 
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class=" control-label" for="payment_method">{{__('Payment Method')}}</label>
                                        <div class="">
                                            <div style=" margin-bottom: 10px">
                                                <select class="form-control demo-select2" name="payment_method"> 
                                                    <option value="in_company" @if($commission_request->payment_method == 'in_company') selected @endif>في الشركة</option> 
                                                    <option value="bank_account" @if($commission_request->payment_method == 'bank_account') selected @endif>حساب بنكي</option> 
                                                    <option value="vodafon_cache" @if($commission_request->payment_method == 'vodafon_cache') selected @endif>فودافون كاش</option> 
                                                </select>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label" for="transfer_number">{{__('Transfer Number')}}</label>
                                        <div>
                                            <input type="text"  id="transfer_number" name="transfer_number" class="form-control" required value="{{ $commission_request->transfer_number }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section> 

@endsection 
