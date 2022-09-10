<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptCompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'company',
            'order_num' => $this->order_num,
            'delivery_man_id' => $this->DeliveryMan->email ?? '',
            'client_name' => $this->client_name,
            'phone' => $this->phone . ', ' . $this->phone2,
            'address' => $this->shipping_country_name . ' , ' . $this->address,
            'deposit' => $this->deposit ?? 0,
            'shipping_cost' => $this->shipping_country_cost,
            'shipping_country_id' => $this->shipping_country_id,
            'order_cost' => $this->order_cost + $this->shipping_country_cost,
            'total' => $this->order_cost + $this->shipping_country_cost - $this->deposit,
            'description' => $this->description,
            'note' => $this->note,
            'route' => 'receipt.company',
            'delivery_status' => $this->delivery_status,
            'payment_status' => $this->payment_status,
            'deliver_date' => $this->deliver_date,
            'done_time' => $this->done_time,
            'calling' => $this->calling,
            'quickly' => $this->quickly,
            'supplied' => $this->supplied,
            'delay_reason' => $this->delay_reason,
            'cancel_reason' => $this->cancel_reason,
            'photos' => $this->photos,
            'send_to_deliveryman_date' => $this->send_to_deliveryman_date,
            'send_to_playlist_date' => $this->send_to_playlist_date,
            'added_by' => $this->Staff->email ?? '',
            'created_at' => $this->created_at,
            'designer_id' => $this->designer_id,
            'preparer_id' => $this->preparer_id,
            'manifacturer_id' => $this->manifacturer_id,
        ];
    }
}
