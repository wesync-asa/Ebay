<?php

namespace App\Http\Controllers;
use Zip;
use \DTS\eBaySDK\Shopping\Services;
use \DTS\eBaySDK\Shopping\Types;
use Illuminate\Support\Facades\Config;
USE App\Components\EBayApi;
use App\Models\Query;
use App\Models\Condition;
use Image;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function test(){
        dd(ceil(69832.4 / 10 / 10) * 10 * 10);
    }
}
