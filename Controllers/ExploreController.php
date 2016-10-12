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
        $data = ['description' => 'This is the explore page.'];

        return array_merge($data, ['is_logged' => Session::isLogged()]);
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
        $data = ['description' => 'This is the explore page. The id is: '.$id];

        return array_merge($data, ['is_logged' => Session::isLogged()]);
    }

    /*
     |----------------------------------------------------------------------
     | Internal methods
     |----------------------------------------------------------------------
     |
    */
    //...
}