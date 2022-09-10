<table>

    <thead>
        <tr>
            <th>
                رقم الأوردر
            </th>
            <th>
                اسم العميل
            </th>
            <th>
                رقم الهاتف
            </th>
            <th>
                العربون
            </th>
            <th>
                الخصم
            </th>
            <th>
                الأجمالي
            </th>
            <th>
                نسبة الربح
            </th>
            <th>
                محتوي الطلب
            </th>
            <th>
                بواسطة
            </th>
            <th>
                تاريخ
            </th>
        </tr>
    </thead>


    @php
        $sum = 0;
        $sum2 = 0;
    @endphp

    <tbody>

        @foreach($receipts as $receipt)
            @php
                $sum += $receipt->total + $receipt->extra_commission + $receipt->shipping_country_cost - $receipt->deposit ;
                $sum2 += ($receipt->commission + $receipt->extra_commission) ;
                $description = '';
                foreach($receipt->receipt_social_products as $key => $product){
                    $description .= $product->title . " - [ (" . $product->cost . "x" . $product->quantity . ") = " . $product->total . "] ";
                    $description .= '<br> --------- <br>';
                }
            @endphp

            <tr>
                <td>{{ $receipt->order_num }}</td>
                <td>{{ $receipt->client_name }}</td>
                <td>{{ $receipt->phone }}</td>
                <td>{{ $receipt->deposit }}</td>
                <td>{{ $receipt->discount }}%</td>
                <td>{{ $receipt->total + $receipt->extra_commission + $receipt->shipping_country_cost - $receipt->deposit }}</td>
                <td>{{ $receipt->commission + $receipt->extra_commission }}</td>
                <td><?php echo nl2br($description ?? ''); ?></td>
                <td>{{ $receipt->Staff ? $receipt->Staff->email : '' }}</td>
                <td>{{ format_date(strtotime($receipt->created_at)) }}</td>
            </tr>
        @endforeach

        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>المجموع : {{ $sum }}</td>
            <td>المجموع : {{ $sum2 }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </tbody>
</table>
