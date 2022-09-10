<?php

namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Product;
use App\Models\HomeCategory;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    protected $view = 'admin.products.categories.';

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        if($category->save()){
            return 1;
        }
        return 0;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view($this->view . 'index', compact('categories'));
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
    public function store(CategoryRequest $request)
    {
        $validated_request = $request->all();  
        if ($request->slug != null) {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        } 


        if($request->hasFile('banner')){
            $validated_request['banner'] = $request->file('banner')->store('uploads/categories/banner');
        }
        if($request->hasFile('icon')){
            $validated_request['icon'] = $request->file('icon')->store('uploads/categories/icon');
        }

        
        // $data = openJSONFile('en');
        // $data[$request->name] = $request->name;
        // saveJSONFile('en', $data);

        $category = Category::create($validated_request); 
        flash(__('Category has been inserted successfully'))->success();
        return redirect()->route('categories.index'); 
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
        $category = Category::findOrFail(decrypt($id));
        return view($this->view . 'edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $validated_request = $request->all(); 

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$category->name]);
        //     $data[$request->name] = "";
        //     saveJSONFile($language->code, $data);
        // } 
        if ($request->slug != null) {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        if($request->hasFile('banner')){
            $validated_request['banner'] = $request->file('banner')->store('uploads/categories/banner');
        }
        if($request->hasFile('icon')){
            $validated_request['icon'] = $request->file('icon')->store('uploads/categories/icon');
        }  

        $category->update($validated_request); 
        flash(__('Category has been updated successfully'))->success();
        return redirect()->route('categories.index'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        foreach ($category->subcategories as $key => $subcategory) {
            foreach ($subcategory->subsubcategories as $key => $subsubcategory) {
                $subsubcategory->delete();
            }
            $subcategory->delete();
        }

        Product::where('category_id', $category->id)->delete();
        HomeCategory::where('category_id', $category->id)->delete();

        if(Category::destroy($id)){
            // foreach (Language::all() as $key => $language) {
            //     $data = openJSONFile($language->code);
            //     unset($data[$category->name]);
            //     saveJSONFile($language->code, $data);
            // }

            flash(__('Category has been deleted successfully'))->success();
            return redirect()->route('categories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }
}
