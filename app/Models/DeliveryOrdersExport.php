<?php

namespace App\Models;

use App\Models\DeliveryManOrders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeliveryOrdersExport implements FromCollection, WithMapping, WithHeadings
{

    protected $from;
    protected $to;
    protected $id;

    function __construct( $from , $to , $id ) {
        $this->from = $from;
        $this->to = $to;
        $this->id = $id;
    }

    public function collection()
    {
        return DeliveryManOrders::where('delivery_man_id',$this->id)->where('date','>=',$this->from)->where('date','<=',$this->to)->orderBy('date','asc')->get();
    }

    public function headings(): array
    {
        return [
            'رقم الأوردر',
            'اسم العميل',
            'رقم التيليفون',
            'ميعاد التوصيل',
            'عنوان',
            'حساب الأوردر',
            'تكلفة الشحن',
            'العربون',
            'أجمالي المطلوب دفعه',
            'وصف',
            'حالة التوصيل',
            'تاريخ',
        ];
    }

    /**
    * @var DeliveryManOrders $order
    */
    public function map($order): array
    {
        $description = strip_tags($order->description);
        return [
            $order->order_num,
            $order->client_name,
            $order->phone,
            date('Y-m-d',$order->deliver_date),
            $order->address,
            $order->order_cost,
            $order->shipping_cost,
            $order->deposit,
            ($order->order_cost + $order->shipping_cost - $order->deposit),
            str_replace('&nbsp;', ' ', $description),
            $order->delivery_status,
            date('Y-m-d',$order->date),
        ];
    }
}
