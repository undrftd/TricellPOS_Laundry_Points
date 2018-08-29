<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use App\User;
use App\Guest;
use App\Product;
use App\Discount;
use App\Sale;
use App\Sales_details;
use Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class QueueController extends Controller
{
    public function index()
    {
        $now = Carbon::now()->format('Y-m-d');

        $queues = Sale::whereHas('salesdetails',function($query) {
             $query->where('product_id', '<=', 24);
        })->orderBy('transaction_date', 'asc')->where('finished_ind', 'N')->paginate(7);
        $skipped = ($queues->currentPage() * $queues->perPage()) - $queues->perPage();
        return view('admin.queue')->with(['queues' => $queues, 'skipped' => $skipped]);
    }

    public function viewstatus()
    {
        $washers = Product::where('product_name', 'LIKE', 'Washer%')->get();
        $dryers = Product::where('product_name', 'LIKE', 'Dryer%')->get();
        return view('admin.queuestatus')->with(['washers' => $washers, 'dryers' => $dryers]);
    }

    public function showdetails(Request $request)
    {
        $sales_id = $request->sales_id;
        $washers = Sales_details::with('product')->where('sales_id', $sales_id)->where('product_id', '<=', 12)->orderBy('product_id', 'asc')->get();
        $dryers = Sales_details::with('product')->where('sales_id', $sales_id)->where('product_id', '>', 12)->where('product_id', '<=', 24)->orderBy('product_id', 'asc')->get();
        $expire = Carbon::now()->addMinutes(10)->toDateTimeString();
        
       return view('admin.queuedetails')->with(['washers' => $washers, 'dryers' => $dryers, 'expire'=> $expire ]);
    }

    public function switch(Request $request)
    {
        $details = Sales_details::where('id', $request->id)->where('sales_id', $request->sales_id)->first();
        $profile = DB::table('profile')->select('*')->where('id', 1)->first();

        $countrow = 0;
        $detailrows = Sales_details::where('sales_id', $request->sales_id)->where('product_id', '<=', 24)->get();
        $detailcount = count($detailrows);

        if($details->product->switch == 0)
        {
            $details->product->switch = 1;
            $details->product->used_by = $request->sales_id;
            $details->switch = 1;  
            $details->used = $details->used +1;
            
            if($details->product_id <= 12)
            {
                $details->product->finish_date = Carbon::now()->addMinutes($profile->washer_timer);
            }
            else
            {
                $details->product->finish_date = Carbon::now()->addMinutes($profile->dryer_timer);
            }
            
            //python script
            $id =  $request->product_id;
            passthru("sudo python /var/www/html/machine{$id}.py");
        }
        else if($details->product->switch == 1)
        {
            $details->product->switch = 0; 
            $details->product->used_by = 0; 
            $details->switch = 0; 

            if($details->used >= $details->quantity) 
            {
                $details->isUsed = 1;
            }

            foreach($detailrows as $row)
            {
                if($row->quantity <= $row->used)
                {
                    $countrow++;
                }
            }
        }
        
        $details->save();
        $details->product->save();

        $sumswitch = Sales_details::selectRaw('SUM(switch)')->where('sales_id', $request->sales_id)->value('SUM(switch)');

        if(($countrow == $detailcount) && $sumswitch == 0)
        {
            $details->sale->finished_ind = 'Y'; 
        }
        
        $details->sale->save();  


        return Response::json(array('used' => $details->used, 'quantity' => $details->quantity, 'isUsed' => $details->isUsed, 'detailcount' => $detailcount, 'countrow' => $countrow, 'sumswitch' => $sumswitch));
    }
}
