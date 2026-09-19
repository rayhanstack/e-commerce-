<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;

class DevController extends Controller
{
    /**
     * Display the component gallery for developers.
     */
    public function components()
    {
        // Only allow in local environment
        if (! app()->environment('local')) {
            abort(404);
        }

        return view('core::dev.components');
    }
}
