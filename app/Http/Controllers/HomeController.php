<?php

namespace App\Http\Controllers;
use Zip;
use \DTS\eBaySDK\Shopping\Services;
use \DTS\eBaySDK\Shopping\Types;
use Illuminate\Support\Facades\Config;
USE App\Components\EBayApi;

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
        $ebay = new EbayApi();
        dd($ebay->getCategoryInfo(-1));

        // $img = Image::make('img/custom/113708159402_0.jpg');
        // // $img = $img->widen($img->width() / 2);
        // $img->insert('img/custom/images.png', 'top-left', 10, 10);
        // $img->save('img/custom/result.jpg');
        
        // echo $img->response();

        // $a = array(1, 2, 3);
        // $b = 4;
        // array_splice($a, 8, 0, $b);
        // dd($a);
        // return view('test');
        // $output = "file";
        // $files = array('1.mp3', '2.mp3', '3.mp3', '4.mp3', '5.mp3', '6.mp3', '7.mp3', '8.mp3', '9.mp3', '10.mp3');
        // $this->zipFiles($files, $output);
    }

    // public function zipFiles($files, $output){
    //     $size = 0;
    //     $limit = 25 * 1024 * 1024;
        
    //     $zip_idx = 0;
    //     $file_idx = 0;
    //     while(true){
    //         if ($file_idx > count($files) - 1) break;
    //         $zip = Zip::create($output.'_'.$zip_idx.'.zip');
    //         while($size < $limit){
    //             if ($file_idx > count($files) - 1) break;
    //             $size += filesize($files[$file_idx]);
    //             if ($size > $limit) break;
    //             $zip->add($files[$file_idx]);
    //             $file_idx++;
    //         }
    //         $zip->close();
    //         $zip_idx++;
    //         $size = 0;
    //     }
    // }
}
