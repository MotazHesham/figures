<?php

namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Product; 
use App\Models\Category;
use App\Models\SubSubCategory;
use App\Http\Requests\SubSubCategoryRequest;

class SubSubCategoryController extends Controller
{
    protected $view = 'admin.products.subsubcategories.';


    public function get_subsubcategories_by_subcategory(Request $request)
    {
        $subsubcategories = SubSubCategory::where('sub_category_id', $request->subcategory_id)->get();
        return $subsubcategories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subsubcategories = SubSubCategory::orderBy('created_at', 'desc')->get();
        return view($this->view . 'index', compact('subsubcategories'));
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
    public function store(SubSubCategoryRequest $request)
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

        $subsubcategory = SubSubCategory::create($validated_request); 

        flash(__('SubSubCategory has been inserted successfully'))->success();
        return redirect()->route('subsubcategories.index');  
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
        $subsubcategory = SubSubCategory::findOrFail(decrypt($id));
        $categories = Category::all();
        return view($this->view . 'edit', compact('subsubcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubSubCategoryRequest $request, $id)
    {
        $subsubcategory = SubSubCategory::findOrFail($id);
        $validated_request = $request->all();
        
        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$subsubcategory->name]);
        //     $data[$request->name] = "";
        //     saveJSONFile($language->code, $data);
        // }

        if ($request->slug != null) {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        $subsubcategory->update($validated_request);
        flash(__('SubSubCategory has been updated successfully'))->success();
        return redirect()->route('subsubcategories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subsubcategory = SubSubCategory::findOrFail($id);
        Product::where('subsubcategory_id', $subsubcategory->id)->delete();
        if(SubSubCategory::destroy($id)){
            // foreach (Language::all() as $key => $language) {
            //     $data = openJSONFile($language->code);
            //     unset($data[$subsubcategory->name]);
            //     saveJSONFile($language->code, $data);
            // }
            flash(__('SubSubCategory has been deleted successfully'))->success();
            return redirect()->route('subsubcategories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }
}
