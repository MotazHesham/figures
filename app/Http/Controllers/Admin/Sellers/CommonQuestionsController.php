<?php

namespace App\Http\Controllers\Admin\Sellers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommonQuestions;

class CommonQuestionsController extends Controller
{
    protected $view = 'admin.sellers.common_questions.';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $common_questions = CommonQuestions::orderBy('created_at', 'desc')->paginate(15); 
        return view($this->view . 'index', compact('common_questions'));
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
    public function store(Request $request)
    {
        $common_questions = new CommonQuestions;
        $common_questions->question = $request->question;
        $common_questions->answer = $request->answer; 

        if($common_questions->save()){
            flash(__('Question has been inserted successfully'))->success();
            return redirect()->route('common_questions.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
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
        $common_questions = CommonQuestions::findOrFail(decrypt($id));
        return view($this->view . 'edit', compact('common_questions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $common_questions = CommonQuestions::findOrFail($id);
        $common_questions->question = $request->question;
        $common_questions->answer = $request->answer;
        $common_questions->save();

        if($common_questions->save()){
            flash(__('Question has been updated successfully'))->success();
            return redirect()->route('common_questions.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        if(CommonQuestions::destroy($id)){ 
            flash(__('Question has been deleted successfully'))->success();
            return redirect()->route('common_questions.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }
}
