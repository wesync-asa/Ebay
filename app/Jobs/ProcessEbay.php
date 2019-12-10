<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use App\Components\EBayApi;

use App\Models\Query;
use App\Models\Condition;
use App\Events\QueryChanged;
use Image;
use Zip;
use Log;

class ProcessEbay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $query;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Query $query)
    {
        //
        $this->query = $query;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function failed()
    {
        $this->query->status = "failure";
        $this->query->save();
        event(new QueryChanged($this->query));
    }

    public function handle()
    {
        try{
            $this->query->status = "process";
            $this->query->save();
            event(new QueryChanged($this->query));

            $condition = $this->query->condition;
            $limit = (int)$condition->image_limit;

            $ebay = new EBayApi();
            $response = $ebay->findItemsAdvanced($condition, 1, 100);

            $allitems = array($response->searchResult->item);
            $pageCt = $response->paginationOutput->totalPages;
            for($i = 2; $i <= $pageCt; $i++){
                $response = $ebay->findItemsAdvanced($condition, $i, 100);
                array_push($allitems, $response->searchResult->item);
            }
            if (!is_dir(public_path('/downloads/'.$this->query->id))){
                mkdir(public_path('/downloads/'.$this->query->id));
                chmod(public_path('/downloads/'.$this->query->id), 0777);
            } else {
                $this->deleteDir(public_path('/downloads/'.$this->query->id));
                mkdir(public_path('/downloads/'.$this->query->id));
                chmod(public_path('/downloads/'.$this->query->id), 0777);
            }

            $csvPath = '/downloads/'.$this->query->id.'/'.$this->query->id.'.csv';
            $csvfile = fopen(public_path($csvPath), 'w');

            $headers = array('商品ID', '商品名', '出品者ID', '価格（日本円）', 'メイン画像パス');

            for($i = 1; $i <= $limit; $i++){
                array_push($headers, 'サブ画像URL'.$i);
            }

            if ($condition->addon_file){
                array_push($headers, 'サブ画像URL'.($limit + 1));
            }
            // fputcsv($csvfile, $headers);
            fwrite($csvfile, chr(255).chr(254).mb_convert_encoding(implode("\t",$headers)."\r\n", 'UTF-16LE', 'UTF-8'));

            $insert_img = null;
            if ($condition->insert_file){
                $insert_img = Image::make(public_path($condition->insert_file));
                $insert_img = $insert_img->widen($insert_img->width() / 100 * $condition->scale);
            }

            $file_array = array();

            foreach($allitems as $items){
                $files = array();
                $itemIds = array();
                foreach($items as $item) {
                    //calc price by csv setting
                    $price = ($item->sellingStatus->currentPrice->value + $condition->diff) * $condition->multiply * $condition->exrate;
                    if (!$condition->unit) $condition->unit = 1;
                    $price = round($price / $condition->unit, 1, PHP_ROUND_HALF_UP) * $condition->unit;
                    //make a csv row array
                    $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $permitted_numbers = '0123456789';
                    $id = substr(str_shuffle($permitted_chars), 0, 6).substr(str_shuffle($permitted_numbers), 0, 10);
                    $line = array($id, $item->title, $item->sellerInfo->sellerUserName, $price);
                    $lines[$item->itemId] = $line;
                    //make item id arrays for multi get
                    array_push($itemIds, $item->itemId);
                }
                //process image
                for($imgI = 0; $imgI < count($items) / 20; $imgI++){
                    $sub_itemIds = array_slice($itemIds, $imgI * 20, 20);
                    $multiItems = $ebay->getMultiItems($sub_itemIds);
                    foreach($multiItems as $eachItem) {
                        $images = $eachItem->PictureURL;
                        $arr_imgs = array();
                        $line = $lines[$eachItem->ItemID];
                        while($images->current()){
                            if ($images->key() > $limit) break;
                            array_push($arr_imgs, $images->current());
                            $images->next();
                        }
                        $addon_str = "";
                        if ($condition->addon_file){
                            array_splice($arr_imgs, $condition->addon_pos, 0, asset($condition->addon_file));
                            $addon_str = asset($condition->addon_file);
                        }
                        foreach($arr_imgs as $key => $img){
                            $image_path = '/downloads/'.$this->query->id.'/'. $line[0].'_'.$key.'.jpg';
                            if ($key == "0") {
                                $image_path = '/downloads/'.$this->query->id.'/'. $line[0].'.jpg';
                            }
                            $orgImg = null;
                            $flag = true;
                            $try = 1;
                            $orgImg = Image::make($img);
                            if ($orgImg != null){
                                if ($condition->insert_file && $img != $addon_str){
                                    $orgImg->insert($insert_img, $condition->ref_point, $condition->off_x, $condition->off_y);
                                }
                                if ($img == $addon_str) {
                                    copy(public_path($condition->addon_file), public_path($image_path));
                                } else {
                                    $orgImg->save(public_path($image_path));
                                }
                                array_push($line, asset($image_path));
                                array_push($files, $image_path);
                            }
                        }
                        array_push($file_array, $files);
                        fwrite($csvfile, mb_convert_encoding(implode("\t",$line)."\r\n", 'UTF-16LE', 'UTF-8'));
                        $lines[$eachItem->ItemID] = $line;
                    }
                }
            }

            fclose($csvfile);

            if ($condition->image_loc == "1") {
                $this->zipFiles($this->query->id);
            }

            $this->query->status = "finish";
            $this->query->save();
            event(new QueryChanged($this->query));
        } catch (Exception $e){
            $this->query->status = "failure";
            $this->query->save();
            event(new QueryChanged($this->query));
        }
    }

    public function zipFiles($qid){
        $output = public_path('/downloads/'.$qid);
        $files = array();
        $csvfile = "";
        if ($handle = opendir($output)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    if(strpos($entry, '.csv')){
                        $csvfile = public_path('/downloads/'.$qid.'/'.$entry);
                        continue;
                    }
                    $p = substr($entry, 0, 15);
                    if (!array_key_exists($p, $files)){
                        $files[$p] = array();
                    }
                    array_push($files[$p], public_path('/downloads/'.$qid.'/'.$entry));
                }
            }
            closedir($handle);
        }

        $output = '/downloads/'.$qid.'/';
        $size = 0;
        $limit = 24 * 1024 * 1024;

        $zip_idx = 1;
        $file_idx = 0;
        $file_array_for_zip = array();

        foreach($files as $p){
            if ($size > $limit){
                $this->subzip($output, $zip_idx, $file_array_for_zip);
                $zip_idx++;
                $size = 0;
                $file_array_for_zip = array();
            }
            foreach($p as $f){
                $size += filesize($f);
                array_push($file_array_for_zip, $f);
            }
        }

        $this->subzip($output, $zip_idx, $file_array_for_zip);

        $totalZip = Zip::create(public_path($output.'/result.zip'));
        if ($csvfile != ""){
            $totalZip->add($csvfile);
        }
        for($i = 1; $i <= $zip_idx; $i++){
            $totalZip->add(public_path($output.'/'.$i));
        }
        $totalZip->close();
        for($i = 1; $i <= $zip_idx; $i++){
            $this->deleteDir(public_path($output.'/'.$i));
        }
        rename(public_path($output.'/result.zip'), public_path($output.'/'.$qid.'.zip'));
    }

    public function subzip($output, $zip_idx, $file_array_for_zip){
        mkdir(public_path($output.$zip_idx));
        foreach($file_array_for_zip as $fz){
            copy($fz, public_path($output.$zip_idx.'/'.basename($fz)));
        }
        $zip = Zip::create(public_path($output.'/'.$zip_idx.'.zip'));
        $zip->add(public_path($output.'/'.$zip_idx));
        $zip->close();

        $this->deleteDir(public_path($output.'/'.$zip_idx));
        mkdir(public_path($output.$zip_idx));
        rename(public_path($output.'/'.$zip_idx.'.zip'), public_path($output.'/'.$zip_idx.'/'.$zip_idx.'.zip'));
    }

    public function getSingleItemImages($item){
        $config = Config::get('ebay.production');

        $service = new \DTS\eBaySDK\Shopping\Services\ShoppingService([
            'credentials' => $config['credentials'],
        ]);

        $request = new \DTS\eBaySDK\Shopping\Types\GetSingleItemRequestType();
        $request->ItemID = $item->itemId;

        $response = $service->getSingleItem($request);
        return $response->Item->PictureURL;
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
