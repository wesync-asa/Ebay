<?php
namespace App\Components;

use Illuminate\Support\Facades\Config;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;

class EBayApi {
    public function findItemsAdvanced($req, $page){
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

        $request->outputSelector = ['SellerInfo'];

        $response = $service->findItemsAdvanced($request);
        return $response;
    }

    public function getSingleItem($item){
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