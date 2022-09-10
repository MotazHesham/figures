<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductsExport implements FromView, ShouldAutoSize
{
    public function collection()
    {
        return Product::all();
    }

    public function view(): View
    { 

        return view('excel_exports.products', [
            'products' => Product::with(['category','subcategory','subsubcategory','brand'])->get(),
        ]); 
    } 
    
}
