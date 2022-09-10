<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Design;
use App\Models\Category;
use App\Models\Mockup;
use App\Models\Listing;
use App\Models\Product;
use App\Models\User; 
use App\Models\UserAlert; 
use App\Http\Controllers\PushNotificationController;
use App\Models\ListingImage;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Http\Resources\ListingResource;
use App\Support\Collection;

class ListingController extends Controller
{
    public function product_images(Request $request){
        $listing_image = ListingImage::where('listing_id',$request->listing_id)->where('color',$request->color)->get();
        return view('frontend.product_details_colors',compact('listing_image'));
    }
    public function index(){  
        $items = Listing::with('listing_images')->where('user_id',Auth::id())->orderBy('created_at','desc')->get(); 
        $listings_collection = collect(ListingResource::collection($items));
        $total_profit_where_not_done =  $listings_collection->sum('profit_where_not_done');
        $total_profit_where_done =  $listings_collection->sum('profit_where_done');
        $num_of_sale =  $listings_collection->sum('count_where_done') + $listings_collection->sum('count_where_not_done');;
        $listings = (new Collection($listings_collection))->paginate(8); 

        return view('frontend.listings.index',compact('listings','total_profit_where_not_done','total_profit_where_done','num_of_sale'));
    }

    public function edit($id){
        $listing = Listing::findOrFail(decrypt($id));
        $mockup = Mockup::findOrFail($listing->mockup_id);
        $designs = Design::where('user_id',Auth::id())->get();
        return view('frontend.listings.edit',compact('listing','mockup','designs'));
    }

    public function store(Request $request){
        $i = 1;

        $listing = new Listing;
        $listing->dataset1 = json_encode($request->dataset1);
        $listing->dataset2 = json_encode($request->dataset2);
        $listing->dataset3 = json_encode($request->dataset3);
        $listing->user_id = Auth::id();
        $listing->design_name = $request->design_name;
        $listing->profit = $request->profit;
        $listing->mockup_id = $request->mockup_id; 
        $listing->colors = json_encode($request->colors);
        $listing->save();

        foreach($request->images as $key => $image){
            $image = explode(";",$image)[1];
            $image = explode(",",$image)[1];
            $image = str_replace(" ","+",$image);
            $image = base64_decode($image);
            $path = 'uploads/designers/'
                                .auth()->user()->store_name
                                .'/collections/'
                                .strtotime(date('Y-m-d H:i:s'))
                                .'-'
                                .$request->design_name
                                .'-'
                                .$i
                                .'.png';
            file_put_contents('public/'.$path,$image);
            $listing_image = new ListingImage;
            $listing_image->image = $path;
            $listing_image->listing_id = $listing->id;
            $explode = explode("-",$key);
            $listing_image->color = $explode[0];
            $listing_image->preview = $explode[1];
            $listing_image->save();
            $i++;
        }
        
        $title = Auth::user()->name;
        $body = 'تصميم جديد';
        UserAlert::create([
            'alert_text' => $title . ' ' . $body . ' من ',
            'alert_link' => route('mockups.index'),
            'type' => 'register',
            'user_id' => 0 ,
        ]);  

        $tokens = User::whereNotNull('device_token')->whereIn('user_type',['staff','admin'])->where(function ($query) {
                                                        $query->where('notification_show',1)
                                                                ->orWhere('user_type','admin');
                                                    })->pluck('device_token')->all(); 
        $push_controller = new PushNotificationController();
        $push_controller->sendNotification($title, $body, $tokens,route('mockups.index')); 

        return 1;
    }

    public function update(Request $request, $id){
        $i = 1;

        $listing = Listing::findOrFail($id);
        $listing->dataset1 = json_encode($request->dataset1);
        $listing->dataset2 = json_encode($request->dataset2);
        $listing->dataset3 = json_encode($request->dataset3);
        $listing->design_name = $request->design_name;
        $listing->profit = $request->profit;
        $listing->colors = json_encode($request->colors);
        $listing->save();

        $old_listing_image = ListingImage::where('listing_id',$listing->id)->get();
        foreach($old_listing_image as $raw){
            Storage::delete($raw->image);
            $raw->delete();
        }

        foreach($request->images as $key => $image){
            $image = explode(";",$image)[1];
            $image = explode(",",$image)[1];
            $image = str_replace(" ","+",$image);
            $image = base64_decode($image);
            $path = 'uploads/designers/'
                                .auth()->user()->store_name
                                .'/collections/'
                                .strtotime(date('Y-m-d H:i:s'))
                                .'-'
                                .$request->design_name
                                .'-'
                                .$i
                                .'.png';
            file_put_contents('public/'.$path,$image);
            $listing_image = new ListingImage;
            $listing_image->image = $path;
            $listing_image->listing_id = $listing->id;
            $explode = explode("-",$key);
            $listing_image->color = $explode[0];
            $listing_image->preview = $explode[1];
            $listing_image->save();
            $i++;
        }
        return 1;
    }

    public function destroy($id){
        $listing = Listing::findOrFail($id);
        $old_listing_image = ListingImage::where('listing_id',$listing->id)->get();
        foreach($old_listing_image as $raw){
            Storage::delete($raw->image);
            $raw->delete();
        }
        $listing->delete();
        flash(__('Success Deleted!'))->success();
        return redirect()->route('listings.index');
    }

}
