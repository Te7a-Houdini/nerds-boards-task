<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use App\Question;
use App\StackOverflow\QuestionsApi;

class StoreStackOverflowQuestions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $questions = (new QuestionsApi)->get();

        if ($questions) {
            $newQuestions = $questions->whereNotIn('question_id', Question::pluck('question_id'))
                ->map(function ($question) {
                    return [
                        'question_id' => $question->question_id,
                        'title' => $question->title,
                        'link' => $question->link,
                    ];
                });

            $inserted = Question::insert($newQuestions->all());

            if ($inserted && $newQuestions->isNotEmpty()) {
                StoreStackOverflowNewQuestionsAnswers::dispatch(
                    Question::whereIn('question_id', $newQuestions->pluck('question_id'))->get()
                );
            }
        }
    }
}
