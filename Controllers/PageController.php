<?php

namespace Controller;

use App\Config;
use App\App;
use App\Request;
use App\Response;
use Helpers\Cache;
use Helpers\Curl;
use Helpers\Log;
use Helpers\Session;

class Page extends \App\BaseController
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
    public function getContactUs()
    {
        // ----------------- Initial Steps ----------------- //
        // ...

        // ----------------- Actions ----------------- //
        // ...

        // Return data
        $data = ['description' => 'This is the contact page.'];

        return Model::prepareData($data);
    }

    /*
     |----------------------------------------------------------------------
     | Internal methods
     |----------------------------------------------------------------------
     |
    */
    //...
}