<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

use App\Models\Query;
use App\Models\Condition;
class ApiController extends Controller
{
    //
    public function getProductCount(Request $req){
        $response = $this->ebaySearch($req, 1);
        
        return response()->json([
            'totalEntries' => $response->paginationOutput->totalEntries,
        ]);
    }

    public function process(Request $req){
        $response = $this->ebaySearch($req, 1);

        $query = new Query();
        $query->count = $response->paginationOutput->totalEntries;
        $query->status = "init";
        $query->csv_progress = 0;
        $query->csv_progress_status = "init";
        $query->img_progress = 0;
        $query->img_progress_status = "init";
        $query->save();

        $condition = new Condition();
        $condition->query_id = $query->id;
        $condition->site = $req->site;
        $condition->keyword = $req->keyword;
        $condition->seller = $req->seller;
        $condition->proType1 = $req->proType1;
        $condition->proType2 = $req->proType2;
        $condition->proType3 = $req->proType3;
        $condition->price_from = $req->price_from;
        $condition->price_to = $req->price_to;
        $condition->qty_from = $req->qty_from;
        $condition->qty_to = $req->qty_to;
        $condition->worldwide = $req->worldwide;
        $condition->japan = $req->japan;
        // $condition->category = $req->category;
        $condition->diff = $req->diff;
        $condition->multiply = $req->multiply;
        $condition->exrate = $req->exrate;
        $condition->unit = $req->unit;
        $condition->image_limit = $req->image_limit;
        $condition->ref_point = $req->ref_point;
        $condition->off_x = $req->off_x;
        $condition->off_y = $req->off_y;
        $condition->scale = $req->scale;
        $condition->addon_pos = $req->addon_pos;
        $condition->image_loc = $req->image_loc;
        $condition->save();

        // $items = array($response->searchResult->item);

        // for($i = 2; $i <= $pageCt; $i++){
        //     // $response = $this->ebaySearch($req, $i);
        //     // array_push($items, $response->searchResult->item);
        // }

        // $current_timestamp = Carbon::now()->timestamp;
        // $csvfile = fopen($current_timestamp.'.csv', 'w');
        // $headers = array('商品ID', '商品名', '出品者ID', '価格（日本円）', 'メイン画像パス', 
        //     'サブ画像URL1', 'サブ画像URL2', 'サブ画像URL3', 'サブ画像URL4',
        //     'サブ画像URL5', 'サブ画像URL6', 'サブ画像URL7', 'サブ画像URL8');
        // fputcsv($csvfile, $headers);
        // foreach($items as $page){
        //     foreach($page as $item){
        //         $line = array(
        //             $item->itemId,
        //             $item->title,
        //             $item->sellerInfo->sellerUserName,
        //             $item->sellingStatus->currentPrice->value,
        //         );
        //         fputcsv($csvfile, $line);
        //     }
        // }
        // fclose($csvfile);

        // return response()->json([
        //     'pageCt' => count($items)
        // ]);
    }

    public function getSingleItemImages(/*$item*/){
        $config = Config::get('ebay.production');

        $service = new \DTS\eBaySDK\Shopping\Services\ShoppingService([
            'credentials' => $config['credentials'],
        ]);
        
        $request = new \DTS\eBaySDK\Shopping\Types\GetSingleItemRequestType();
        // $request->ItemID = $item->itemId;
        $request->ItemID = '303176405302';
        // $request->IncludeSelector = 'ItemSpecifics,Variations,Compatibility,Details';

        $response = $service->getSingleItem($request);
        dd($response);
    }

    public function ebaySearch($req, $page){
        $config = Config::get('ebay.production');

        $service = new Services\FindingService([
            'credentials' => $config['credentials'],
            'globalId'    => $req->site
        ]);

        $request = new Types\FindItemsAdvancedRequest();
        $request->keywords = $req->keyword;

        $arr_condition = [];
        if ($req->proType1) array_push($arr_condition, 'New');
        if ($req->proType2) array_push($arr_condition, 'Used');
        if ($req->proType3) array_push($arr_condition, 'Unspecified');

        if ($req->proType1 || $req->proType2 || $req->proType3){
            $request->itemFilter[] = new Types\ItemFilter([
                'name' => 'Condition',
                'value' => $arr_condition
            ]);
        }

        if ($req->price_from) {
            $request->itemFilter[] = new Types\ItemFilter([
                'name' => 'MinPrice',
                'value' => [$req->price_from]
            ]);
        }

        if ($req->price_to) {
            $request->itemFilter[] = new Types\ItemFilter([
                'name' => 'MaxPrice',
                'value' => [$req->price_to]
            ]);
        }
        if ($req->qty_from) {
            $request->itemFilter[] = new Types\ItemFilter([
                'name' => 'MinQuantity',
                'value' => [$req->qty_from]
            ]);
        }
        if ($req->qty_to){
            $request->itemFilter[] = new Types\ItemFilter([
                'name' => 'MaxQuantity',
                'value' => [$req->qty_to]
            ]);
        }
        if ($req->worldwide) {
            $request->itemFilter[] = new Types\ItemFilter([
                'name' => 'LocatedIn',
                'value' => ['WorldWide']
            ]);
        }
        if ($req->japan) {
            $request->itemFilter[] = new Types\ItemFilter([
                'name' => 'AvailableTo',
                'value' => ['JP']
            ]);
        }
        if ($req->seller) {
            $request->itemFilter[] = new Types\ItemFilter([
                'name' => 'Seller',
                'value' => [$req->seller]
            ]);
        }
        
        $request->itemFilter[] = new Types\ItemFilter([
            'name' => 'Currency',
            'value' => ['USD']
        ]);
        $request->sortOrder = 'CurrentPriceHighest';
        
        $request->paginationInput = new Types\PaginationInput();
        $request->paginationInput->entriesPerPage = 100;
        $request->paginationInput->pageNumber = $page;

        // $request->outputSelector = ['SellerInfo', 'GalleryInfo'];

        $response = $service->findItemsAdvanced($request);
        return $response;
    }
}
