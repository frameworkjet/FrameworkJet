<?php

namespace Controller;

use App\App;
use App\Request;
use App\Response;
use Helpers\Api;
use Helpers\Cache;
use Helpers\Log;

class Home extends \App\BaseController
{
    // Variables
    // ...

    /*
     |----------------------------------------------------------------------
     | External methods
     |----------------------------------------------------------------------
     |
    */
    /**
     * @desc Test
     * @return array
     */
    public function getHome()
    {
        // ----------------- Initial Steps ----------------- //
        // ...

        // ----------------- Actions ----------------- //
        // ...

        // Return data
        return ['description' => 'This is the home page.'];
    }

    /*
     |----------------------------------------------------------------------
     | Internal methods
     |----------------------------------------------------------------------
     |
    */
    //...
}