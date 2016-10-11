<?php

namespace Controller;

use App\App;
use App\Request;
use App\Response;
use Helpers\Api;
use Helpers\Cache;
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
        return ['description' => 'This is the explore page.'];
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
        return ['description' => 'This is the explore page. The id is: '.$id];
    }

    /*
     |----------------------------------------------------------------------
     | Internal methods
     |----------------------------------------------------------------------
     |
    */
    //...
}