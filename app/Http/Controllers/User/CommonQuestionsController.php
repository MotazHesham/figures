<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommonQuestions;

class CommonQuestionsController extends Controller
{
    

    public function index(){ 
        $common_questions = CommonQuestions::orderBy('created_at', 'desc')->get(); 
        return view('frontend.common_questions', compact('common_questions'));
    }
}
