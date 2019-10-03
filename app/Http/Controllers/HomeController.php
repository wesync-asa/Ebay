<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;

use Illuminate\Http\Request;
// use \DTS\eBaySDK\OAuth\Services;
// use \DTS\eBaySDK\OAuth\Types;
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

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

    public function testEbay()
    {
        $config = Config::get('ebay.production');

        $service = new Services\FindingService([
            'credentials' => $config['credentials'],
            'globalId'    => Constants\GlobalIds::US
        ]);
        $request = new Types\FindItemsAdvancedRequest();
        $request->keywords = 'Harry Potter';
        /**
         * Filter results to only include auction items or auctions with buy it now.
         */
        $itemFilter = new Types\ItemFilter();
        $itemFilter->name = 'ListingType';
        $itemFilter->value[] = 'Auction';
        $itemFilter->value[] = 'AuctionWithBIN';
        $request->itemFilter[] = $itemFilter;
        /**
         * Add additional filters to only include items that fall in the price range of $1 to $10.
         *
         * Notice that we can take advantage of the fact that the SDK allows object properties to be assigned via the class constructor.
         */
        $request->itemFilter[] = new Types\ItemFilter([
            'name' => 'MinPrice',
            'value' => ['1.00']
        ]);
        $request->itemFilter[] = new Types\ItemFilter([
            'name' => 'MaxPrice',
            'value' => ['10.00']
        ]);
        /**
         * Sort the results by current price.
         */
        $request->sortOrder = 'CurrentPriceHighest';
        /**
         * Limit the results to 10 items per page and start at page 1.
         */
        $request->paginationInput = new Types\PaginationInput();
        $request->paginationInput->entriesPerPage = 10;
        $request->paginationInput->pageNumber = 1;
        /**
         * Send the request.
         */
        
        $response = $service->findItemsAdvanced($request);
        if (isset($response->errorMessage)) {
            foreach ($response->errorMessage->error as $error) {
                printf(
                    "%s: %s\n\n",
                    $error->severity=== Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
                    $error->message
                );
            }
        }
        /**
         * Output the result of the search.
         */
        printf(
            "%s items found over %s pages.\n\n",
            $response->paginationOutput->totalEntries,
            $response->paginationOutput->totalPages
        );
        echo "==================\nResults for page 1\n==================\n";
        if ($response->ack !== 'Failure') {
            foreach ($response->searchResult->item as $item) {
                printf(
                    "(%s) %s: %s %.2f\n",
                    $item->itemId,
                    $item->title,
                    $item->sellingStatus->currentPrice->currencyId,
                    $item->sellingStatus->currentPrice->value
                );
            }
        }
        /**
         * Paginate through 2 more pages worth of results.
         */
        $limit = min($response->paginationOutput->totalPages, 3);
        for ($pageNum = 2; $pageNum <= $limit; $pageNum++) {
            $request->paginationInput->pageNumber = $pageNum;
            $response = $service->findItemsAdvanced($request);
            echo "==================\nResults for page $pageNum\n==================\n";
            if ($response->ack !== 'Failure') {
                foreach ($response->searchResult->item as $item) {
                    printf(
                        "(%s) %s: %s %.2f\n",
                        $item->itemId,
                        $item->title,
                        $item->sellingStatus->currentPrice->currencyId,
                        $item->sellingStatus->currentPrice->value
                    );
                }
            }
        }
    }
}
