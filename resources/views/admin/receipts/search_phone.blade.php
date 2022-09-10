<div class="row">
    <div class="col-xs-2">
        <h5>{{__('Social Receipt')}}</h5>
        <b> {{ $receipt_social }} </b>
    </div>
    <div class="col-xs-2">
        <h5>{{__('Figures Receipt')}}</h5>
        <b> {{ $receipt_figures }} </b>
    </div>
    <div class="col-xs-2">
        <h5>{{__('Company Receipts')}}</h5>
        <b> {{ $receipt_company }} </b>
    </div>
    <div class="col-xs-2">
        <h5>{{__('Clients Receipt')}}</h5>
        <b> {{ $receipt_client }} </b>
    </div>
    <div class="col-xs-2">
        <h5>{{__('Customers Orders')}}</h5>
        <b> {{ $customers_orders }} </b>
    </div>
    <div class="col-xs-2">
        <h5>{{__('Sellers Orders')}}</h5>
        <b> {{ $sellers_orders }} </b>
    </div>
    @if($banned_phones)
        <div class="col-xs-12">
            <h3 class="text-danger">الرقم محظور</h3>
            <h5>{{__('Reason')}}</h5>
            <b> {{ $banned_phones->reason }} </b>
        </div>
    @endif
</div>
