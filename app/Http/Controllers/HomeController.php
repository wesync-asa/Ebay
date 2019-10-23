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
        $this->zipFiles(13);
    }

    public function zipFiles($qid){
        $output = public_path('/downloads/'.$qid);
        $files = array();
        if ($handle = opendir($output)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    array_push($files, public_path('/downloads/'.$qid.'/'.$entry));
                }
            }
            closedir($handle);
        }

        $output = '/downloads/'.$qid.'/';
        $size = 0;
        $limit = 25 * 1024 * 1024;
        
        $zip_idx = 1;
        $file_idx = 0;
        $keys = array_keys($files);
        $csvfile = "";
        while(true){
            if ($file_idx > count($files) - 1) break;
            mkdir(public_path($output.$zip_idx));
            while($size < $limit){
                if ($file_idx > count($files) - 1) break;
                $filename = $files[$keys[$file_idx]];
                if(strpos($filename, 'csv')){ 
                    $file_idx++;
                    $csvfile = $filename;
                    continue;
                }
                $size += filesize($filename);
                if ($size > $limit) break;
                copy($filename, public_path($output.$zip_idx.'/'.basename($filename)));
                $file_idx++;
            }
            $zip = Zip::create(public_path($output.'/'.$zip_idx.'.zip'));
            $zip->add(public_path($output.'/'.$zip_idx));
            $zip->close();

            $this->deleteDir(public_path($output.'/'.$zip_idx));

            mkdir(public_path($output.$zip_idx));
            rename(public_path($output.'/'.$zip_idx.'.zip'), public_path($output.'/'.$zip_idx.'/'.$zip_idx.'.zip'));

            $zip_idx++;
            $size = 0;
        }

        $totalZip = Zip::create(public_path($output.'/result.zip'));
        if ($csvfile != ""){
            $totalZip->add($csvfile);
        }
        for($i = 1; $i < $zip_idx; $i++){
            $totalZip->add(public_path($output.'/'.$i));
        }
        $totalZip->close();
        for($i = 1; $i < $zip_idx; $i++){
            $this->deleteDir(public_path($output.'/'.$i));
        }
    }
    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
