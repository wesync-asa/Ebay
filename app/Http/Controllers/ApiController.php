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
use App\Jobs\ProcessEbay;
use App\Events\QueryChanged;

use App\Components\EBayApi;

class ApiController extends Controller
{
    //
    public function getProductCount(Request $req){
        $ebay = new EBayApi();
        $response = $ebay->findItemsAdvanced($req, 1);
        
        return response()->json([
            'totalEntries' => $response->paginationOutput->totalEntries,
        ]);
    }

    public function process(Request $req){
        $query = new Query();
        $query->keyword = $req->keyword;
        $query->seller = $req->seller;
        $query->count = $req->productCt;
        $query->status = "init";
        if ($req->addon_file || $req->insert_file){
            $query->image_edit = 1;
        }
        $query->image_loc = $req->image_loc;
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

        if ($req->addon_file){
            $req->addon_file->move(public_path('img/custom'), $query->id."_addon.jpg");
            $condition->addon_file = 'img/custom/'. $query->id."_addon.jpg";
        }
        if ($req->insert_file){
            $req->insert_file->move(public_path('img/custom'), $query->id."_insert.jpg");
            $condition->insert_file = 'img/custom/'. $query->id."_insert.jpg";
        }

        $condition->save();

        $this->dispatch(new ProcessEbay($query));

        return response()->json(['id' => $query->id]);
    }

    public function getHistory(){
        return response()->json(['history' => Query::all()]);
    }

    public function removeHistory(Request $req){
        foreach($req->ids as $id){
            $query = Query::find($id);
            $this->deleteDir(public_path('/downloads/'.$query->id));
            if ($query != null){
                $query->condition->delete();
                $query->delete();
            }
        }
        event(new QueryChanged());
        return response()->json(['data' => $req->ids]);
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
