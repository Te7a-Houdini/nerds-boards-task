<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\StoreStackOverflowQuestionsWithAnswers;

class StackOverflowQuestionController extends Controller
{
    public function store()
    {
        StoreStackOverflowQuestionsWithAnswers::dispatch();
        return redirect()->route('questions.index');
    }
}
