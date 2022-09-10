<?php

namespace App\Models;

use App\Models\Calender;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CalenderExport implements FromCollection, WithMapping, WithHeadings
{
    
    protected $from;
    protected $to;

    function __construct($from , $to ) {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return Calender::with('user')->where('date','>=',$this->from)->where('date','<=',$this->to)->orderBy('date','asc')->get();
    }

    public function headings(): array
    {
        return [
            'اسم',
            'رقم تليفون',
            'عنوان',
            'البريد الألكتروني',
            'اسم المناسبة',
            'وصف المناسبة',
            'التاريح'
        ];
    }

    /**
    * @var Calender $receipt
    */
    public function map($calender): array
    {
        return [
            $calender->user ? $calender->user->name : '',
            $calender->user ? $calender->user->phone_number : '',
            $calender->user ? $calender->user->address : '',
            $calender->user ? $calender->user->email : '',
            $calender->title,
            $calender->description,
            format_date($calender->date),
        ];
    }
}
