<?php

namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Http\Requests\BrandRequest;

class BrandController extends Controller
{
    protected $view = 'admin.products.brands.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $brands = Brand::orderBy('created_at', 'desc')->get(); 
        return view($this->view . 'index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->view . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        $validated_request = $request->all(); 
        if ($request->slug != null) {
            $validated_request['slug'] = str_replace(' ', '-', $request->slug);
        }
        else {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }
        if($request->hasFile('logo')){
            $validated_request['logo'] = $request->file('logo')->store('uploads/brands');
        }
        $brand = Brand::create($validated_request);  
        flash(__('Brand has been inserted successfully'))->success();
        return redirect()->route('brands.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail(decrypt($id));
        return view($this->view . 'edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $validated_request = $request->all(); 
        if ($request->slug != null) {
            $validated_request['slug'] = str_replace(' ', '-', $request->slug);
        }
        else {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }
        if($request->hasFile('logo')){
            $validated_request['logo'] = $request->file('logo')->store('uploads/brands');
        }

        $brand->update($validated_request); 

        flash(__('Brand has been updated successfully'))->success();
        return redirect()->route('brands.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        Product::where('brand_id', $brand->id)->delete();
        Brand::destroy($id);
        flash(__('Brand has been deleted successfully'))->success();
        return redirect()->route('brands.index'); 
    }
}
