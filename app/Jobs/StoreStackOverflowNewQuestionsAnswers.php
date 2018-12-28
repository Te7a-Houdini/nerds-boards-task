<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Answer;
use App\StackOverflow\AnswersApi;

class StoreStackOverflowNewQuestionsAnswers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $questions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($questions)
    {
        $this->questions = $questions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $questionsIds = $this->questions->implode('question_id', ';');
        
        $answers = (new AnswersApi)->get(['questionsIds' => $questionsIds]);

        if ($answers) {
            Answer::insert(
                $answers
                    ->map(function ($answer) {
                        return [
                            'question_id' => $this->questions->firstWhere('question_id', $answer->question_id)->id,
                            'answer_id' => $answer->answer_id,
                        ];
                    })->all()
            );
        }
    }
}
