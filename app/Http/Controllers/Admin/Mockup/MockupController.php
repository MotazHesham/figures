<?php

namespace App\Http\Controllers\Admin\Mockup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Attribute;
use App\Models\Mockup;
use App\Models\MockupStock;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;

class MockupController extends Controller
{
    protected $view = 'admin.mockups.mockups.';

    public function sku_combination(Request $request)
    {
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $mockup_name = $request->name;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('admin.mockups.mockups.sku_combinations', compact('combinations', 'unit_price' , 'colors_active', 'mockup_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $mockup = Mockup::findOrFail($request->id);

        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $mockup_name = $request->name;
        $unit_price = $request->unit_price;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('admin.mockups.mockups.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'mockup_name', 'mockup'));
    }

    public function index(){
        $mockups = Mockup::orderBy('created_at','desc')->get();
        return view($this->view . 'index',compact('mockups'));
    }

    public function create()
    {
        $categories = Category::all();
        $colors = Color::orderBy('name', 'asc')->get();
        $attributes = Attribute::all();
        return view($this->view . 'create', compact('categories','colors','attributes'));
    }

    public function store(Request $request){
        
        if($request->hasFile('preview_1')){
            $preview_1 = $request->preview_1->store('uploads/mockups');
        }else{
            $preview_1 = null;
        }
        
        if($request->hasFile('preview_2')){
            $preview_2 = $request->preview_2->store('uploads/mockups');
        }else{
            $preview_2 = null;
        }
        
        if($request->hasFile('preview_3')){
            $preview_3 = $request->preview_3->store('uploads/mockups');
        }else{
            $preview_3 = null;
        }

        $prev_1 = [
            'image' => $preview_1,
            'left' => $request->left_preview_1,
            'top' => $request->top_preview_1,
            'height' => $request->height_preview_1,
            'width' => $request->width_preview_1,
            'name' => $request->name_preview_1,
        ];
        $prev_2 = [
            'image' => $preview_2,
            'left' => $request->left_preview_2,
            'top' => $request->top_preview_2,
            'height' => $request->height_preview_2,
            'width' => $request->width_preview_2,
            'name' => $request->name_preview_2,
        ];
        $prev_3 = [
            'image' => $preview_3,
            'left' => $request->left_preview_3,
            'top' => $request->top_preview_3,
            'height' => $request->height_preview_3,
            'width' => $request->width_preview_3,
            'name' => $request->name_preview_3,
        ];

        $category =  Category::find($request->category_id);
        if($category){
            $category->design = 1;
            $category->save();
        }
        $subcategory =  SubCategory::find($request->subcategory_id);
        if($subcategory){
            $subcategory->design = 1;
            $subcategory->save();
        }
        if($request->subsubcategory_id != null){
            $subsubcategory =  SubSubCategory::find($request->subsubcategory_id);
            if($subsubcategory){
                $subsubcategory->design = 1;
                $subsubcategory->save();
            }
        }
        
        $mockup = new Mockup;
        $mockup->preview_1 = json_encode($prev_1); 
        $mockup->preview_2 = $preview_2 ? json_encode($prev_2) : null; 
        $mockup->preview_3 = $preview_3 ? json_encode($prev_3) : null;
        $mockup->category_id = $request->category_id;
        $mockup->subcategory_id = $request->subcategory_id;
        $mockup->subsubcategory_id = $request->subsubcategory_id;
        $mockup->name = $request->name;
        $mockup->description = $request->description;
        $mockup->video_provider = $request->video_provider;
        $mockup->video_link = $request->video_link;
        $mockup->purchase_price = $request->unit_price;
        
        $options = array();
        $choice_options = array();

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $mockup->colors = json_encode($request->colors); 
            array_push($options, $request->colors);
        }else {
            $colors = array();
            $mockup->colors = json_encode($colors);
            array_push($options, $request->colors);
        }


        if (!empty($request->choice_no)) {
            $mockup->attributes = json_encode($request->choice_no);
        }else{
            $mockup->attributes = json_encode(array());
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);

                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $mockup->choice_options = json_encode($choice_options);
        $mockup->save();

        //Generates the combinations of customer choice options
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }else{
                        $color_name = \App\Models\Color::where('code', $item)->first()->name;
                        $str .= $color_name;
                    }
                }
                
                
                $mockup_stock = new MockupStock;
                $mockup_stock->mockup_id = $mockup->id;
                $mockup_stock->variant = $str;
                $mockup_stock->price = $request['price_'.str_replace('.', '_', $str)];
                $mockup_stock->qty = $request['qty_'.str_replace('.', '_', $str)];
                $mockup_stock->save();
            }
        }
        //combinations end

        flash(__('Mockup added successfully'))->success();
        return redirect()->route('mockups.index');
    }

    public function edit($id){
        $mockup = Mockup::findOrFail(decrypt($id));
        $categories = Category::all();
        $colors = Color::orderBy('name', 'asc')->get();
        $attributes = Attribute::all();
        return view($this->view . 'edit', compact('categories','colors','attributes','mockup'));
    }

    public function update(Request $request,$id){

        $mockup = Mockup::findOrFail($id);
        
        if($request->hasFile('preview_1')){
            $preview_1 = $request->preview_1->store('uploads/mockups');
        }else{
            if($mockup->preview_1 != null){
                $preview_1 = json_decode($mockup->preview_1)->image;
            }else{
                $preview_1 = null;
            }
        }
        
        if($request->hasFile('preview_2')){
            $preview_2 = $request->preview_2->store('uploads/mockups');
        }else{
            if($mockup->preview_2 != null){
                $preview_2 = json_decode($mockup->preview_2)->image;
            }else{
                $preview_2 = null;
            }
        }
        
        if($request->hasFile('preview_3')){
            $preview_3 = $request->preview_3->store('uploads/mockups');
        }else{
            if($mockup->preview_3 != null){
                $preview_3 = json_decode($mockup->preview_3)->image;
            }else{
                $preview_3 = null;
            }
        }

        $prev_1 = [
            'image' => $preview_1,
            'left' => $request->left_preview_1,
            'top' => $request->top_preview_1,
            'height' => $request->height_preview_1,
            'width' => $request->width_preview_1,
            'name' => $request->name_preview_1,
        ];
        $prev_2 = [
            'image' => $preview_2,
            'left' => $request->left_preview_2,
            'top' => $request->top_preview_2,
            'height' => $request->height_preview_2,
            'width' => $request->width_preview_2,
            'name' => $request->name_preview_2,
        ];
        $prev_3 = [
            'image' => $preview_3,
            'left' => $request->left_preview_3,
            'top' => $request->top_preview_3,
            'height' => $request->height_preview_3,
            'width' => $request->width_preview_3,
            'name' => $request->name_preview_3,
        ];

        $mockup->preview_1 = json_encode($prev_1); 
        $mockup->preview_2 = $preview_2 ? json_encode($prev_2) : null; 
        $mockup->preview_3 = $preview_3 ? json_encode($prev_3) : null;
        $mockup->category_id = $request->category_id;
        $mockup->subcategory_id = $request->subcategory_id;
        $mockup->subsubcategory_id = $request->subsubcategory_id;
        $mockup->name = $request->name;
        $mockup->description = $request->description;
        $mockup->video_provider = $request->video_provider;
        $mockup->video_link = $request->video_link;
        $mockup->purchase_price = $request->unit_price;

        $category =  Category::find($request->category_id);
        if($category){
            $category->design = 1;
            $category->save();
        }
        $subcategory =  SubCategory::find($request->subcategory_id);
        if($subcategory){
            $subcategory->design = 1;
            $subcategory->save();
        }
        if($request->subsubcategory_id != null){
            $subsubcategory =  SubSubCategory::find($request->subsubcategory_id);
            if($subsubcategory){
                $subsubcategory->design = 1;
                $subsubcategory->save();
            }
        }

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $mockup->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $mockup->colors = json_encode($colors);
        }

        $choice_options = array();

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if($mockup->attributes != json_encode($request->choice_attributes)){
            foreach ($mockup->mockupstocks as $key => $stock) {
                $stock->delete();
            }
        }

        if (!empty($request->choice_no)) {
            $mockup->attributes = json_encode($request->choice_no);
        }
        else {
            $mockup->attributes = json_encode(array());
        }

        $mockup->choice_options = json_encode($choice_options);

        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        if(count($combinations[0]) > 0){ 
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Models\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }

                $mockup_stock = MockupStock::where('mockup_id', $mockup->id)->where('variant', $str)->first();
                if($mockup_stock == null){
                    $mockup_stock = new MockupStock;
                    $mockup_stock->mockup_id = $mockup->id;
                } 

                $mockup_stock->variant = $str;
                $mockup_stock->price = $request['price_'.str_replace('.', '_', $str)]; 
                $mockup_stock->qty = $request['qty_'.str_replace('.', '_', $str)];

                $mockup_stock->save();
            }
        }

        $mockup->save();

        flash(__('Mockup Uodated successfully'))->success();
        return redirect()->route('mockups.index');
    }

    public function destroy($id)
    { 
        if(Mockup::destroy($id)){
            flash(__('Mockup has been deleted successfully'))->success();
            return redirect()->route('mockups.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return redirect()->route('mockups.index');
        }
    }
}
