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

class Explore extends \App\BaseController
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
    public function getExplore()
    {
        // ----------------- Initial Steps ----------------- //
        // ...

        // ----------------- Actions ----------------- //
        // ...

        // Return data
        $output = ['description' => 'This is the explore page.'];
        
        return Model::prepareData($output);
    }

    /**
     * @desc Test
     * @return array
     */
    public function getExploreId($id)
    {
        // ----------------- Initial Steps ----------------- //
        // ...

        // ----------------- Actions ----------------- //
        // ...

        // Return data
        $output = ['description' => 'This is the explore page. The id is: '.$id];

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