<?php

namespace Controller;

use App\Config;
use App\App;
use App\Request;
use App\Response;
use Helpers\Cache;
use Helpers\Curl;
use Helpers\Log;
use Helpers\Mailer;
use Helpers\Model;
use Helpers\Mysql;
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
        $output = ['description' => 'This is the contact page.'];

        return Model::prepareData($output);
    }

    /*
     |----------------------------------------------------------------------
     | Internal methods
     |----------------------------------------------------------------------
     |
    */
    //...
}