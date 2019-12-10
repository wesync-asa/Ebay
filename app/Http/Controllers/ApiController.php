<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Query;
use App\Models\Condition;
use App\Jobs\ProcessEbay;
use App\Events\QueryChanged;

use App\Components\EBayApi;
use Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //
    public function getProductCount(Request $req){
        $ebay = new EBayApi();
        $response = $ebay->findItemsAdvanced($req, 1, 100);

        if ($response->ack === "Failure"){
            $err = array();
            foreach ($response->errorMessage->error as $error){
                array_push($err, $error->message);
            }
            return response()->json([
                'success' => false,
                'msg' => $err
            ]);
        } else {
            return response()->json([
                'success' => true,
                'totalEntries' => $response->paginationOutput->totalEntries,
                'data' => $req->all()
            ]);
        }
    }

    public function process(Request $req){
        // exec('nohup /usr/local/php/7.1/bin/php artisan queue:listen --timeout=0 > my.log 2>&1 & echo $! > save_pid.txt &');
        $size_per_file = 125 * 1024;
        $wantedSize = $req->productCt * $req->image_limit * $size_per_file * 4;
        $diskSize = disk_free_space("/");
        if ($wantedSize > $diskSize){
            return response()->json(['success' => false,'message' => "Low disk space"]);
        }
        $query = new Query();
        $query->keyword = $req->keyword;
        $query->seller = $req->seller;
        $query->count = $req->productCt;
        $query->status = "init";
        if ($req->addon_file || $req->insert_file){
            $query->image_edit = 1;
        }
        $query->image_loc = $req->image_loc;
        // $query->user_id = Auth::id();
        $query->save();

        $condition = new Condition();
        $condition->query_id = $query->id;
        $condition->site = $req->site;
        $condition->site_name = $req->site_name;
        $condition->keyword = $req->keyword;
        $condition->seller = $req->seller;
        $condition->proType1 = $req->proType1;
        $condition->proType2 = $req->proType2;
        $condition->proType3 = $req->proType3;
        $condition->aucType1 = $req->aucType1;
        $condition->aucType2 = $req->aucType2;
        $condition->aucType3 = $req->aucType3;
        $condition->price_from = $req->price_from;
        $condition->price_to = $req->price_to;
        $condition->qty_from = $req->qty_from;
        $condition->qty_to = $req->qty_to;
        $condition->worldwide = $req->worldwide;
        $condition->japan = $req->japan;
        $condition->category = $req->category;
        $condition->category_name = $req->category_name;
        $condition->diff = $req->diff;
        $condition->multiply = $req->multiply;
        $condition->exrate = $req->exrate;
        $condition->unit = $req->unit;
        $condition->image_limit = $req->image_limit;
        $condition->ref_point = $req->ref_point;
        $condition->ref_point_name = $req->ref_point_name;
        $condition->off_x = $req->off_x;
        $condition->off_y = $req->off_y;
        $condition->scale = $req->scale;
        $condition->addon_pos = $req->addon_pos;
        $condition->image_loc = $req->image_loc;

        if ($req->hasFile("addon_file")){
            $req->addon_file->move(public_path('img/custom'), $query->id."_addon.jpg");
            $condition->addon_file = 'img/custom/'. $query->id."_addon.jpg";
        }
        if ($req->hasFile("insert_file")){
            $req->insert_file->move(public_path('img/custom'), $query->id."_insert.jpg");
            $condition->insert_file = 'img/custom/'. $query->id."_insert.jpg";
        }

        $condition->save();

        $this->dispatch(new ProcessEbay($query));

        return response()->json(['success' => true,'id' => $query->id]);
    }

    public function getHistory(){
        $arr_query = Query::all();
        $result = array();
        foreach($arr_query as $q){
            $item = $q->toArray();
            if ($q->condition != null){
                $item['condition'] = $q->condition->toArray();

                $arr_condition = [];
                if ($q->condition->proType1 == "true") array_push($arr_condition, '新品');
                if ($q->condition->proType2 == "true") array_push($arr_condition, '中古');
                if ($q->condition->proType3 == "true") array_push($arr_condition, '未指定商品');
                $item['condition']['proType1'] = implode(',', $arr_condition);

                $arr_auction = [];
                if ($q->condition->aucType1 == "true") array_push($arr_auction, 'Auction');
                if ($q->condition->aucType2 == "true") array_push($arr_auction, 'AuctionWithBIN');
                if ($q->condition->aucType3 == "true") array_push($arr_auction, 'FixedPrice');
                $item['condition']['aucType1'] = implode(',', $arr_auction);

                $item['condition']['addon_file'] = asset($item['condition']['addon_file']);
                $item['condition']['insert_file'] = asset($item['condition']['insert_file']);
            } else {
                $item['condition'] = array();
            }
            array_push($result, $item);
        }
        return response()->json(['history' => $result]);
    }

    public function removeHistory(Request $req){
        foreach($req->ids as $id){
            $query = Query::find($id);
            if ($query != null){
                if (is_dir(public_path('/downloads/'.$query->id))){
                    $this->deleteDir(public_path('/downloads/'.$query->id));
                }
                $query->condition->delete();
                $query->delete();
            }
        }
        // event(new QueryChanged());
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

    public function getCategory(Request $req){
        $id = $req->id;
        if ($id == "") {
            return array();
        }
        $ebay = new EbayApi();
        $response = $ebay->getCategoryInfo($id);
        $result = array();

        foreach($response as $cat){
            array_push($result, ['id' => $cat->CategoryID, 'name' => $cat->CategoryName]);
        }
        array_shift($result);
        return response()->json(['cats' => $result]);
    }

    public function download(Request $req){
        $id = request()->id;
        $query = Query::find($id);
        if ($query->image_loc == "0") {
            return response()->json(['files' => [asset("downloads/".$id."/".$id.".csv")]]);
        } else {
            return response()->json(['files' => [asset("downloads/".$id."/result.zip")]]);
        }
    }

    public function reset(){
        DB::delete('delete from jobs');
        DB::delete('delete from failed_jobs');
        Query::where('status', 'init')->orWhere('status', 'process')->update(['status' => 'failure']);
        return response()->json(['message' => 'Reset']);
    }
}
