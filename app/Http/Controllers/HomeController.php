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

    public function test($qid){
        $output = public_path('/downloads/'.$qid);
        $files = array();
        $csvfile = "";
        $prefix = "";
        if ($handle = opendir($output)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    if(strpos($entry, '.csv')){
                        $csvfile = public_path('/downloads/'.$qid.'/'.$entry);
                        continue;
                    }
                    $p = substr($entry, 0, 15);
                    if ($prefix != $p){
                        $files[$p] = array();
                        $prefix = $p;
                    }
                    array_push($files[$prefix], public_path('/downloads/'.$qid.'/'.$entry));
                }
            }
            closedir($handle);
        }
        dd($files);
    }
}
