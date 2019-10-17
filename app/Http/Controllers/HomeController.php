<?php

namespace App\Http\Controllers;
use Zip;
use \DTS\eBaySDK\Shopping\Services;
use \DTS\eBaySDK\Shopping\Types;
use Illuminate\Support\Facades\Config;
USE App\Components\EBayApi;
use App\Models\Query;
use Image;

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
        $img = Image::make('https://i.ebayimg.com/00/s/MTIwMFgxNjAw/z/h-QAAOSwsB9WCYHh/$_1.JPG?set_id=880000500F');
    }
}
