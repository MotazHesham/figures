
    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px">
            <h3 class="text-center">{{$receipt_social->order_num}} {{__('Products Of Receipt')}} </h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>{{__('#')}}</th>
                        <th>{{__('Product Name')}}</th>
                        <th>{{__('Description')}}</th>
                        <th>{{__('Cost')}}</th>
                        <th>{{__('Extra Commission')}}</th>
                        <th>{{__('Quantity')}}</th>
                        <th>{{__('Total')}}</th>
                        <th>{{__('Commission')}}</th>
                        <th>{{__('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt_social->receipt_social_products as $key => $product)
                        <tr>
                            <td>{{ $product ->id }}</td>
                            <td>{{ $product ->title }}</td>
                            <td><?php echo $product->description; ?></td>
                            <td>{{ single_price($product ->cost) }}</td>
                            <td>{{single_price($product ->extra_commission)}}</td>
                            <td>{{ $product ->quantity }}</td>
                            <td>{{ single_price($product ->total + ($product ->extra_commission * $product->quantity)) }}</td>
                            <td>
                                {{ single_price($product ->commission) }} 
                            </td>
                            <td>
                                <a class="btn btn-info btn-rounded" href="{{route('receipt.social.edit_product', $product->id)}}">{{__('Edit')}}</a>
                                <a class="btn btn-danger btn-rounded" onclick="confirm_modal('{{route('receipt.social.product.destroy', $product->id)}}');">{{__('Delete')}}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>
    </div>