<?php

namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Product; 
use App\Models\Category;
use App\Models\SubSubCategory;
use App\Models\SubCategory;
use App\Http\Requests\SubCategoryRequest;

class SubCategoryController extends Controller
{
    protected $view = 'admin.products.subcategories.';


    public function get_subcategories_by_category(Request $request)
    {
        $subcategories = SubCategory::where('category_id', $request->category_id)->get();
        return $subcategories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subcategories = SubCategory::orderBy('created_at', 'desc')->get(); 
        return view($this->view . 'index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view($this->view . 'create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubCategoryRequest $request)
    {
        $validated_request = $request->all();
        if ($request->slug != null) {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        // $data = openJSONFile('en');
        // $data[$request->name] = $request->name;
        // saveJSONFile('en', $data);

        $subcategory = SubCategory::create($validated_request);

        flash(__('Subcategory has been inserted successfully'))->success();
        return redirect()->route('subcategories.index');
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
        $subcategory = SubCategory::findOrFail(decrypt($id));
        $categories = Category::all();
        return view($this->view . 'edit', compact('categories', 'subcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubCategoryRequest $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $validated_request = $request->all(); 

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$subcategory->name]);
        //     $data[$request->name] = "";
        //     saveJSONFile($language->code, $data);
        // }

        if ($request->slug != null) {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        $subcategory->update($validated_request);

        flash(__('Subcategory has been updated successfully'))->success();
        return redirect()->route('subcategories.index'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        foreach ($subcategory->subsubcategories as $key => $subsubcategory) {
            $subsubcategory->delete();
        }
        Product::where('subcategory_id', $subcategory->id)->delete();
        if(SubCategory::destroy($id)){
            // foreach (Language::all() as $key => $language) {
            //     $data = openJSONFile($language->code);
            //     unset($data[$subcategory->name]);
            //     saveJSONFile($language->code, $data);
            // }
            flash(__('Subcategory has been deleted successfully'))->success();
            return redirect()->route('subcategories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

}