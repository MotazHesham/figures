<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product; 
use App\Models\OrderDetail;

class ListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    { 
        $count_where_not_done = 0;
        $count_where_done = 0; 

        $product = Product::where('listing_id',$this->id)->first();
        if($product){
            $order_details = OrderDetail::with('order')->where('product_id',$product->id)->get();

            foreach($order_details as $raw){
                if($raw->order->delivery_status != 'cancel'){
                    if($raw->order->delivery_status == 'delivered'){
                        $count_where_done += $raw->quantity;  
                    }else{
                        $count_where_not_done += $raw->quantity;  
                    }
                }
            }
        }
        
        return [
            'id' => $this->id,
            'design_name' => $this->design_name,
            'profit' => $this->profit,
            'colors' => $this->colors,
            'status' => $this->status,
            'listing_images' => $this->listing_images,
            'user' => $this->user->email ?? '',
            'mockup' => $this->mockup->name ?? '',
            'count_where_not_done' => $count_where_not_done,
            'profit_where_not_done' => $count_where_not_done * $this->profit,
            'count_where_done' => $count_where_done, 
            'profit_where_done' => $count_where_done * $this->profit, 
        ];
    }
}
