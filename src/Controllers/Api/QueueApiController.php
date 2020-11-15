<?php

namespace App\Controllers\Api;

use App\Views\ApiJsonView;

class QueueApiController extends ApiController
{
    public string $apiName = 'queue';
    protected ApiJsonView $ApiJsonView;

    protected string $wrongResponse = 'You need to use POST method with body to add message to the queue';

    public function __construct()
    {
        parent::__construct();
        $this->ApiJsonView = new ApiJsonView();
    }

    protected function indexAction()
    {
        $this->ApiJsonView->response($this->wrongResponse, 405);
    }

    protected function createAction()
    {
        $this->ApiJsonView->response($this->wrongResponse, 405);
    }

    protected function updateAction()
    {
        //кидать в очередь body
    }

    protected function deleteAction()
    {
        $this->ApiJsonView->response($this->wrongResponse, 405);
    }
}