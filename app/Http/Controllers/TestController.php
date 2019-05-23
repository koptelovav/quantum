<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

/**
 * Class TestController
 *
 * @package App\Http\Controllers
 */
class TestController extends BaseController
{
    /**
     * Application home page
     *
     * @return View
     */
    public function index(): View
    {
        return view('test');
    }
}
