<?php

namespace App\Http\Controllers\Admin\Mockup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Mockup;
use App\Models\MockupStock;
use App\Models\ProductStock;
use App\Models\Product;
use App\Models\User;
use Auth;
use App\Http\Resources\ListingResource;
use App\Support\Collection;

class ListingController extends Controller
{
    protected $view = 'admin.mockups.listings.';

    public function index(Request $request){ 
        $designers = User::where('user_type','designer')->orderBy('created_at','desc')->get();
        $designer_id = null;
        $status = null;
        $design_name = null;

        $items = Listing::with(['mockup','user','listing_images'])->where('trash',0)->orderBy('created_at','desc'); 

        if ($request->designer_id != null){
            $items = $items->where('user_id',$request->designer_id);
            $designer_id = $request->designer_id;
        }
        if ($request->status != null){
            $items = $items->where('status',$request->status);
            $status = $request->status;
        } 
        if ($request->design_name != null){
            $items = $items
                        ->where('design_name', 'like', '%'.$request->design_name.'%');
            $design_name = $request->design_name;
        }

        $items = $items->get();

        $listings_collection = collect(ListingResource::collection($items));

        $total_profit_where_not_done =  $listings_collection->sum('profit_where_not_done');
        $total_profit_where_done =  $listings_collection->sum('profit_where_done');
        $num_of_sale_where_done =  $listings_collection->sum('count_where_done');
        $num_of_sale_where_not_done =  $listings_collection->sum('count_where_not_done');

        $listings = (new Collection($listings_collection))->paginate(8); 

        return view($this->view . 'index',compact('listings','status','design_name','designers','designer_id','total_profit_where_not_done','total_profit_where_done','num_of_sale_where_done','num_of_sale_where_not_done'));
    }

    public function preview_designs(Request $request){
        $listing = Listing::find($request->id);
        return view($this->view . 'preview_designs',compact('listing'));
    }
    public function refuse($id){
        $listing = Listing::findOrFail($id);
        $listing->status = 'refused';
        $listing->save(); 
        flash(__('Design Refused!!'))->success();
        return redirect()->route('admin.listings.index');
    }
    public function trash($id){
        $listing = Listing::findOrFail($id);
        $listing->trash = 1;
        $listing->save(); 
        flash(__('تم الأخفاء من القائمة'))->success();
        return redirect()->route('admin.listings.index');
    }

    public function accept($id){
        $listing = Listing::findOrFail($id);
        $mockup = Mockup::findOrFail($listing->mockup_id);

        $product = new Product;
        $product->name = $listing->design_name;
        $product->listing_id = $listing->id;
        $product->added_by = 'designer';
        $product->user_id = $listing->user_id;
        $product->category_id = $mockup->category_id;
        $product->subcategory_id = $mockup->subcategory_id;
        $product->subsubcategory_id = $mockup->subsubcategory_id;
        $product->current_stock = 1000;

        $product->unit = '1';
        $product->description = $mockup->description;
        $product->video_provider = $mockup->video_provider;
        $product->video_link = $mockup->video_link;
        $product->unit_price = $mockup->purchase_price + $listing->profit;
        $product->purchase_price = $mockup->purchase_price;

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $listing->design_name)).'-'.str_random(5);

        $product->colors = $listing->colors;
        $product->attributes = $mockup->attributes;
        $product->choice_options = $mockup->choice_options;
        $product->save();

        $photos = array();

        $listing_images = ListingImage::where('listing_id',$listing->id)->where('preview','1')->get();
        $listing_images2 = ListingImage::where('listing_id',$listing->id)->where('preview','2')->get();
        $listing_images3 = ListingImage::where('listing_id',$listing->id)->where('preview','3')->get();

        foreach($listing_images as $image){ 
            array_push($photos,$image->image); 
        }

        foreach($listing_images2 as $image){ 
            array_push($photos,$image->image); 
        }

        foreach($listing_images3 as $image){ 
            array_push($photos,$image->image); 
        } 

        $product->photos = json_encode($photos);

        $listing_images_first = ListingImage::where('listing_id',$listing->id)->where('preview','1')->first();

        $product->thumbnail_img = $listing_images_first->image ?? ''; 
        $product->featured_img = $listing_images_first->image ?? ''; 
        $product->flash_deal_img = $listing_images_first->image ?? ''; 	
        $product->meta_img = $listing_images_first->image ?? ''; 

        $mockup_stock = MockupStock::where('mockup_id',$mockup->id)->get();
        foreach($mockup_stock as $raw){
            $product_stock = new ProductStock;
            $product_stock->product_id = $product->id;
            $product_stock->variant = $raw->variant;
            $product_stock->price = $raw->price + $listing->profit;
            $product_stock->purchase_price = $raw->price;
            $product_stock->qty = $raw->qty;
            $product_stock->save();
        }
        
        $product->save();

        $listing->status = 'accepted';
        $listing->save();

        flash(__('Design Accepted!!'))->success();
        return redirect()->route('admin.listings.index');
    }
}
