<?php

namespace App\Http\Controllers;

use App\Responses\GoogleJsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Request helper
     * @var Response
     */
    protected $response;

    /**
     * Base Controller constructor
     * @param GoogleJsonResponse $response
     */
    public function __construct(GoogleJsonResponse $response)
    {
        $this->response = $response;
    }
}
