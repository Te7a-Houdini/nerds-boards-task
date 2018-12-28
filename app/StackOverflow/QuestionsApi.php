<?php

namespace App\StackOverflow;

class QuestionsApi extends BaseApi
{
    protected function call($params = [])
    {
        return $this->client->get('questions', [
            'query' => array_merge($this->client->getConfig('query'), [
                'tagged' => config('nerds-boards.tag'),
            ])
        ]);
    }
}
