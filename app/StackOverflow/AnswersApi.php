<?php

namespace App\StackOverflow;

class AnswersApi extends BaseApi
{
    protected function call($params = [])
    {
        return $this->client->get('questions/' . $params['questionsIds'] .'/answers');
    }
}
