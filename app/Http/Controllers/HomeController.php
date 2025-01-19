<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $homeService;

    public function __construct(HomeService $homeService) {
        $this->homeService = $homeService;
    }
    
    function index(Request $request)
    {
        return $this->homeService->getHome($request->latitude, $request->longitude);
    }
}
