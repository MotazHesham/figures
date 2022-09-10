<!DOCTYPE html>
<html lang="ar">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ertgal</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src=" {{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

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

        body{
            font-family:monospace;
            font-weight: bolder
        }
        .table-bordered{
            border:2px solid black
        }

    </style>
</head>

<body>

    @php
        $generalsetting = \App\Models\GeneralSetting::first();
        $description = '';
    @endphp

    @foreach ($receipts as $key => $receipt_social)

        @if ($receipt_social->receipt_social_products != null)
            @foreach ($receipt_social->receipt_social_products as $key => $product)
                @php
                    $description .= ' ( ' . $product->title . ' - ' . $product->description;
                    $description .= ' ) ';
                @endphp
            @endforeach
        @endif


        <div style="page-break-after: always;">
            <div style="padding: 1.5rem;position:relative">
                <table style="position: absolute;top:80px;left:150px" class="text-center">
                    <tr>
                        <td class="gry-color small">{{ $generalsetting->address }}</td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        <td class="gry-color small">Email: {{ $generalsetting->email }}</td>
                    </tr>
                    <tr>
                        <td class="gry-color small">Phone: {{ $generalsetting->phone }}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>

                            @if($generalsetting->logo != null)
                                <img loading="lazy"  src="{{ asset($generalsetting->logo) }}" height="100" style="display:inline-block;">
                            @else
                                <img loading="lazy"  src="{{ asset('receipt_logo.png') }}" height="40" style="display:inline-block;">
                            @endif
                        </td>
                    </tr>
                </table>
                <div style="position: absolute;right:120px;top:65px;font-size:40px">
                    Receipt
                    <div class="text-center" style="line-height: 0;">
                        @foreach($receipt_social->socials as $social)
                            <img src="{{asset($social->photo)}}" height="20" width="20" alt="">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="text-center" style="margin-bottom: 10px;color:red !important">
                {{ $receipt_social->order_num }}
                <span style="color:red;">:رقم الأوردر</span>
            </div>
            <div style="position:relative">
                <table class="table table-bordered text-center" style="width:380px;position:absolute;right:10px;top:0" id="price-{{$receipt_social->id}}">
                    <thead>
                        <td>عدد القطع</td>
                        <td>المطلوب دفعه</td>
                        <td>العربون</td>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $receipt_social->receipt_social_products()->sum('quantity') }}</td>
                            <td>
                                <span style="color:red !important">
                                    {{$receipt_social->total + $receipt_social->extra_commission + $receipt_social->shipping_country_cost - $receipt_social->deposit}} EGP
                                </span>
                            </td>
                            <td>{{ $receipt_social->deposit }} EGP</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered text-center" style="width:380px;position:absolute;left:10px;top:0" id="description-{{$receipt_social->id}}">
                    <thead>
                        <td>تفاصيل الأوردر</td>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $description; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered text-center" style="width:380px;position:absolute;top:100px;right:10px" id="client_info-{{$receipt_social->id}}">
                    <tbody>
                        <tr>
                            <td>{{ $receipt_social->client_name }}</td>
                            <td>اسم العميل</td>
                        </tr>
                        <tr>
                            <td>{{ $receipt_social->phone }} @if ($receipt_social->phone2) - {{ $receipt_social->phone2 }} @endif</td>
                            <td>رقم التليفون</td>
                        </tr>
                        <tr>
                            <td>{{ $receipt_social->shipping_country_name }} </td>
                            <td>المنطقة</td>
                        </tr>
                        <tr>
                            <td>{{ $receipt_social->address }}</td>

                            <td>مكان التوصيل</td>
                        </tr>
                        <tr>
                            <td>
                                {{ $receipt_social->note }}
                            </td>
                            <td>ملاحطات</td>
                        </tr>
                    </tbody>
                </table>

            </div>


            <div id="second_part-{{$receipt_social->id}}" style="position: relative; width:90%;left:40px">
                <hr style="border-top: 2px dashed red">

                <div class="text-center" style="margin-bottom: 10px;color:red !important">
                    {{ $receipt_social->order_num }}
                    <span style="color:red;">:رقم الأوردر</span>
                </div>
                <table class="table table-bordered text-center">
                    <tbody>
                        <tr>
                            <td>
                                {{format_Date_Time(strtotime($receipt_social->created_at))}}
                            </td>
                            <td colspan="2">
                                تحرير في
                            </td>
                            <td>
                                {{$receipt_social->Staff ? $receipt_social->Staff->name : ''}}
                            </td>
                            <td colspan="2">
                                الموظف
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">{{$receipt_social->client_name}}</td>
                            <td colspan="2">اسم العميل</td>
                        </tr>
                        <tr>
                            <td>
                                {{format_Date($receipt_social->date_of_receiving_order)}}
                            </td>
                            <td colspan="2">
                                تاريخ استلام
                            </td>
                            <td>
                                {{$receipt_social->phone}} @if($receipt_social->phone2) - {{$receipt_social->phone2}} @endif
                            </td>
                            <td colspan="2">
                                رقم التليفون
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                {{$receipt_social->shipping_country_name}}
                            </td>
                            <td colspan="4">المنطقة</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                {{$receipt_social->address}}
                            </td>
                            <td colspan="2">مكان التوصيل</td>
                        </tr>
                        <tr>
                            <td>
                                {{$receipt_social->shipping_country_cost}} EGP
                            </td>
                            <td colspan="2">
                                مصاريف الشحن
                            </td>
                            <td>
                                {{$receipt_social->total + $receipt_social->extra_commission}} EGP
                            </td>
                            <td colspan="2">
                                حساب الأوردر
                            </td>
                        </tr>
                        </tr>
                        <tr>
                            <td>
                                {{$receipt_social->total + $receipt_social->extra_commission  - $receipt_social->deposit}} EGP
                            </td>
                            <td>
                                حساب المندوب
                            </td>
                            <td>
                                <span style="color:red !important">
                                    {{$receipt_social->total + $receipt_social->extra_commission + $receipt_social->shipping_country_cost - $receipt_social->deposit}} EGP
                                </span>
                            </td>
                            <td>
                                المطلوب دفعه
                            </td>
                            <td>
                                {{$receipt_social->deposit}} EGP
                            </td>
                            <td>
                                العربون
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table text-center table-borderless" style="width: 270px;float:right">
                    <tr>
                        <td>تأجيل ليوم</td>
                        <td>
                            <div style=" border: black 1px solid; display:inline;padding:0px 16px"></div>
                        </td>
                        <td>تسليم</td>
                        <td>
                            <div style=" border: black 1px solid; display:inline;padding:0px 16px"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>التشغيل</td>
                        <td>
                            <div style=" border: black 1px solid; display:inline;padding:0px 16px"></div>
                        </td>
                        <td>الخزينة</td>
                        <td>
                            <div style=" border: black 1px solid; display:inline;padding:0px 16px"></div>
                        </td>
                    </tr>
                </table>

                <table class="table table-bordered text-center" style="width: 400px;float:left">
                    <tbody>
                        <tr>
                            <td>{{ $receipt_social->designer->name ?? '' }}</td>
                            <td>ديزاينر</td>
                        </tr>
                        <tr>
                            <td>{{ $receipt_social->manifacturer->name ?? '' }}</td>
                            <td>تصنيع</td>
                        </tr>
                        <tr>
                            <td>{{ $receipt_social->preparer->name ?? '' }}</td>
                            <td>تجهيز</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <script>
        window.print()

        @foreach ($receipts as $key => $receipt_social)
            var price_and_client_info = $('#price-{{$receipt_social->id}}').height() + $('#client_info-{{$receipt_social->id}}').height() + 50;
            var description = $('#description-{{$receipt_social->id}}').height() + 50;
            if (price_and_client_info > description) {
                var first_part = price_and_client_info;
            } else {
                var first_part = description;
            }
            $('#second_part-{{$receipt_social->id}}').css('top', first_part + 'px');
        @endforeach
    </script>


</body>

</html>
