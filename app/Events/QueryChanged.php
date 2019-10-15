<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use App\Models\Query;
class QueryChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $queries;
    public function __construct()
    {
        //
        $arr_query = Query::all();
        $result = array();
        foreach($arr_query as $q){
            $item = $q->toArray();
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
            array_push($result, $item);
        }
        $this->queries = $result;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('queries');
    }
}
