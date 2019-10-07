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
    public function handle()
    {
        try{
            $this->query->status = "process";
            $this->query->save();
            event(new QueryChanged());

            $condition = $this->query->condition;
            $limit = (int)$condition->image_limit;

            $ebay = new EBayApi();
            $response = $ebay->findItemsAdvanced($condition, 1);

            $allitems = array($response->searchResult->item);
            $pageCt = $response->paginationOutput->totalPages;
            for($i = 2; $i <= $pageCt; $i++){
                $response = $ebay->findItemsAdvanced($condition, $i);
                array_push($allitems, $response->searchResult->item);
            }
            if (!file_exists(public_path('/downloads/'.$this->query->id))){
                mkdir(public_path('/downloads/'.$this->query->id));
            }

            $zipArray = array();

            $csvPath = '/downloads/'.$this->query->id.'/'.$this->query->id.'.csv';
            $csvfile = fopen(public_path($csvPath), 'w');
            array_push($zipArray, public_path($csvPath));
            $zipArray['result.csv'] = public_path($csvPath);


            $headers = array('商品ID', '商品名', '出品者ID', '価格（日本円）', 'メイン画像パス');
            
            for($i = 1; $i <= $limit; $i++){
                array_push($headers, 'サブ画像URL'.$i);
            }

            if ($condition->addon_file){
                array_push($headers, 'サブ画像URL'.($limit + 1));
            }
            fputcsv($csvfile, $headers);
            // fwrite($csvfile, chr(255).chr(254).mb_convert_encoding(implode("\t",$headers)."\r\n", 'UTF-16LE', 'UTF-8'));
            
            foreach($allitems as $items){
                foreach($items as $item){
                    $price = ($item->sellingStatus->currentPrice->value + $condition->diff) * $condition->multiply * $condition->exrate;
                    $price = round($price / $condition->unit, 1, PHP_ROUND_HALF_UP) * $condition->unit;
                    $line = array(
                        $item->itemId,
                        $item->title,
                        $item->sellerInfo->sellerUserName,
                        $price,
                    );
                    $images = $ebay->getSingleItem($item);
                    $arr_imgs = array();
                    while($images->current()){
                        if ($images->key() > $limit) break;
                        array_push($arr_imgs, $images->current());
                        $images->next();
                    }
                    if ($condition->addon_file){
                        array_splice($arr_imgs, $condition->addon_pos, 0, asset($condition->addon_file));
                    }
                    foreach($arr_imgs as $key => $img){
                        $image_path = '/downloads/'.$this->query->id.'/'. $item->itemId.'_'.$key.'.jpg';
                        $orgImg = Image::make($img);
                        if ($condition->insert_file){
                            $insert_img = Image::make(public_path($condition->insert_file));
                            $insert_img = $insert_img->widen($insert_img->width() / 100 * $condition->scale);
                            $orgImg->insert($insert_img, $condition->ref_point, $condition->off_x, $condition->off_y);
                        }
                        $orgImg->save(public_path($image_path));
                        array_push($zipArray, public_path($image_path));
                        array_push($line, asset($image_path));
                    }
                    fputcsv($csvfile, $line);
                    // fwrite($csvfile, mb_convert_encoding(implode("\t",$line)."\r\n", 'UTF-16LE', 'UTF-8'));
                }
            }

            fclose($csvfile);

            if ($condition->image_loc == "1") {
                $output = public_path('/downloads/'.$this->query->id.'/'.$this->query->id);
                $this->zipFiles($zipArray, $output);
            }

            $this->query->status = "finish";
            $this->query->save();
            event(new QueryChanged());
        } catch (Exception $e){
            $this->query->status = "failure";
            event(new QueryChanged());
        }
    }

    public function zipFiles($files, $output){
        $size = 0;
        $limit = 25 * 1024 * 1024;
        
        $zip_idx = 0;
        $file_idx = 0;
        $keys = array_keys($files);
        while(true){
            if ($file_idx > count($files) - 1) break;
            $zip = Zip::create($output.'_'.$zip_idx.'.zip');
            while($size < $limit){
                if ($file_idx > count($files) - 1) break;
                $size += filesize($files[$keys[$file_idx]]);
                if ($size > $limit) break;
                $zip->add($files[$keys[$file_idx]]);
                $file_idx++;
            }
            $zip->close();
            $zip_idx++;
            $size = 0;
        }
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
}
