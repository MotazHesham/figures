<table>

    <thead>
        <tr>
            <th>
                نوع الشحن
            </th>
            <th>
                الفرع
            </th>
            <th>
                رقم موبايل المرسل اليه
            </th>
            <th>
                رقم موبايل بديل
            </th>
            <th>
                اسم العميل المرسل اليه
            </th>
            <th>
                المحافظة
            </th>
            <th>
                المنطقة
            </th>
            <th>
                اسم الشارع
            </th>
            <td>
                علامة مميزة
            </td>
            <td>
                رقم العمارة
            </td>
            <td>
                رقم الدور
            </td>
            <td>
                رقم الشقة
            </td>
            <td>
                ملاحظات
            </td>
            <th>
                محتوي الشحنة
            </th>
            <th>
                وزن الشحنة
            </th>
            <th>
                طريقة التحصيل
            </th>
            <th>
                المبلغ المحصل
            </th>
            <th>
                سعر الشحن
            </th>
            <th>
                مسموح الفتح؟
            </th>
            <th>
                قابل للكسر
            </th>
            <th>
                ملاحظات
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
                $description = '';
                foreach($receipt->receipt_social_products as $key => $product){
                    $description .= $product->title . " - [" . $product->quantity . "]";
                    $description .= '<br> --------- <br>';
                }
            @endphp

            <tr>
                <td>شحن الى باب البيت</td>
                <td></td>
                <td>{{ $receipt->phone }}</td>
                <td>{{ $receipt->phone2 }}</td>
                <td>{{ $receipt->client_name }}</td>
                <td>{{ $receipt->shipping_country_name }}</td>
                <td></td>
                <td>{{ $receipt->address }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo nl2br($description ?? ''); ?></td>
                <td>طرد مقابل مبلغ</td>
                <td>1</td>
                <td>كاش</td>
                <td>{{ $receipt->total + $receipt->extra_commission + $receipt->shipping_country_cost - $receipt->deposit }}</td>
                <td>يضاف ثمن الشحنة الى الراسل</td>
                <td>نعم</td>
                <td>نعم</td>
                <td>طرد</td>
            </tr>
        @endforeach

    </tbody>
</table>
