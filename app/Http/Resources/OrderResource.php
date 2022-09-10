<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $description = '';
        foreach($this->OrderDetails as $key => $order_detail){
            $description .= $order_detail->product->name . "(" . $order_detail->quantity .  ") <br> " . $order_detail->description ;
            $description .= '<hr>';
        }

        return [
            'id' => $this->id,
            'type' => 'order',
            'order_num' => $this->code,
            'delivery_man_id' => $this->delivery_man,
            'client_name' => $this->client_name,
            'phone' => $this->phone_number . ', ' . $this->phone_number2,
            'address' => $this->shipping_country_name . ' , ' . $this->shipping_address,
            'deposit' => $this->deposit_amount ?? 0,
            'shipping_cost' => $this->shipping_country_cost,
            'shipping_country_id' => $this->shipping_country_id,
            'order_cost' => $this->required_to_pay + $this->shipping_country_cost,
            'total' => $this->required_to_pay + $this->shipping_country_cost - $this->deposit_amount,
            'description' => $description,
            'note' => $this->note,
            'route' => 'admin.orders',
            'delivery_status' => $this->delivery_status,
            'payment_status' => $this->payment_status,
            'deliver_date' => $this->excepected_deliverd_date,
            'done_time' => $this->done_time,
            'calling' => $this->calling,
            'quickly' => 0,
            'supplied' => $this->supplied,
            'delay_reason' => $this->delay_reason,
            'cancel_reason' => $this->cancel_reason,
            'photos' => $this->photos ?? null,
            'send_to_deliveryman_date' => $this->send_to_deliveryman_date,
            'send_to_playlist_date' => $this->send_to_playlist_date,
            'added_by' => $this->user->email ?? '',
            'created_at' => $this->created_at,
            'designer_id' => $this->designer_id,
            'preparer_id' => $this->preparer_id,
            'manifacturer_id' => $this->manifacturer_id,
        ];
    }
}
