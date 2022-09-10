<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ebtekar</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style media="all">
        @font-face {
            font-family: 'DINNextLTArabic-Medium';
            src: url("{{ asset('css/DINNextLTArabic-Medium.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0;
            /* this affects the margin in the printer settings */
        }

        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            color: #333542;
        }

        body {
            font-size: .875rem;
            font-weight: bolder;
            font-family: 'DINNextLTArabic-Medium';
        }

        .gry-color *,
        .gry-color {
            color: #878f9c;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .5rem .7rem;
        }

        table.padding td {
            padding: .7rem;
        }

        table.sm-padding td {
            padding: .2rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: .85rem;
        }

        .currency {}
    </style>
</head>

<body>
    <div>

        @php
            $generalsetting = \App\Models\GeneralSetting::first();
        @endphp

        <div style="background: #eceff4;padding: 1.5rem;">
            <table>
                <tr>
                    <td>
                        @if ($generalsetting->logo != null)
                            <img loading="lazy" src="{{ asset($generalsetting->logo) }}" height="100"
                                style="display:inline-block;">
                        @else
                            <img loading="lazy" src="{{ asset('frontend/images/logo/logo.png') }}" height="70"
                                style="display:inline-block;">
                        @endif
                    </td>
                </tr>
            </table>
            <h2 style="text-align: center">عرض سعر</h2>
        </div>

        <div style="padding: 1.5rem;">

            <table style="padding: 1.5rem;float: right;position: relative;">
                <img src="{{ asset($generalsetting->logo) }}" alt=""
                    style="position: absolute;opacity:0.1;top:180px;width:100%">
                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;">
                        <span>{{ format_Date_Time(strtotime($price_view->created_at)) }}</span>
                        <span class="gry-color strong" style="float:right">: التاريخ </span>
                    </td>
                    <td class="text-right small" style="font-size: 1.2rem;">
                        <span>{{ $price_view->client_name }}</span>
                        <span class="gry-color strong" style="float:right">: إلي السيد </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;">
                        <span>{{ $price_view->supply_duration }}</span>
                        <span class="gry-color strong" style="float:right">: مدة التوريد </span>
                    </td>
                    <td class="text-right small" style="font-size: 1.2rem;">
                        <span>{{ $price_view->relate_duration }}</span>
                        <span class="gry-color strong" style="float:right">: مدة الأرتباط </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;">
                        <span>{{ $price_view->place }}</span>
                        <span class="gry-color strong" style="float:right">: مكان التسليم </span>
                    </td>
                    <td class="text-right small" style="font-size: 1.2rem;">
                        <span>{{ $price_view->payment }}</span>
                        <span class="gry-color strong" style="float:right">: الدفع</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right small" style="font-size: 1.2rem;">
                        <span>{{ $price_view->phone }}</span>
                        <span class="gry-color strong" style="float:right">: تليفون </span>
                    </td>
                    <td class="text-right small" style="font-size: 1.2rem;">
                        <span>{{ $price_view->added_value ? 'شامل' : 'غير شامل' }}</span>
                        <span class="gry-color strong" style="float:right">: شامل / غير شامل %14</span>
                    </td>
                </tr>
            </table>

            <table class="padding text-left small border-bottom">

                <img src="{{ asset($generalsetting->logo) }}" alt=""
                    style="position: absolute;opacity:0.1;top:180px;width:100%">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="35%">الصنف</th>
                        <th width="15%">الكمية</th>
                        <th width="10%">السعر</th>
                        <th width="15%" class="text-right">الأجمالي</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    @foreach ($price_view->receipt_price_view_products as $key => $product)
                        @if ($price_view->receipt_price_view_products != null)
                            <tr class="">
                                <td>{{ $product->description }}</td>
                                <td class="gry-color">{{ $product->quantity }}</td>
                                <td class="gry-color currency">{{ $product->cost }} @if ($product->cost != null)
                                        EGP
                                    @endif
                                </td>
                                <td class="text-right currency">{{ $product->total }} @if ($product->total != null)
                                        EGP
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>


        <div style="padding:0 1.5rem;">
            <table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
                <tbody>
                    @php
                        if($price_view->added_value == 1){
                            $extra_value = ($price_view->total * 14) / 100;
                        }else{
                            $extra_value = 0;
                        }
                    @endphp
                    @if($price_view->added_value == 1)
                        <tr>
                            <td class="currency">{{ $price_view->total }} EGP
                            </td>
                            <th width="35%" class="gry-color text-right">الأجمالي</th>
                        </tr>
                        <tr>

                            <td class="currency">{{ $extra_value }} EGP
                            </td>
                            <th width="35%" class="gry-color text-right">(قيمة %14)</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="currency">{{ $price_view->total + $extra_value }} EGP
                        </td>
                        <th width="35%" class="gry-color text-right">المجموع</th>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        window.print()
    </script>

</body>

</html>
