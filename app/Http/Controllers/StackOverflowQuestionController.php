<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\StoreStackOverflowQuestions;

class StackOverflowQuestionController extends Controller
{
    public function store()
    {
        StoreStackOverflowQuestions::dispatch();
        return redirect()->route('questions.index');
    }
}
