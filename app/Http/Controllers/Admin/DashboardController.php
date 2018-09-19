<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use App\User;
use App\Reload_sale;
use App\Sale;
use App\Sales_details;
use App\Product;
use Carbon\Carbon;
use Response;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function index()	
    {	
        //panels
        $newmembers = User::where('role', 'member')->where('created_at', '>', Carbon::now()->subDays(7))->count();
        $reloadsales = Reload_sale::selectRaw('ROUND(SUM(amount_due),2)')->where('transaction_date', '>', Carbon::now()->subDays(30))->pluck('ROUND(SUM(amount_due),2)')->toArray();
        $reloadsales = implode($reloadsales);
        $sales = Sale::selectRaw('ROUND(SUM(amount_due),2)')->where('transaction_date', '>', Carbon::now()->subDays(30))->pluck('ROUND(SUM(amount_due),2)')->toArray();
        $sales = implode($sales);
        $stock_ind = DB::table('profile')->select('low_stock')->where('id', 1)->first();
        $lowstock = Product::where('product_id', '>', 24)->where('product_qty', '<=', $stock_ind->low_stock)->where('product_qty', '>', 0)->count();
        

        //sales for the year
        $yearnow = Carbon::now()->format('Y');

        $jan = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $jan = array_column($jan, 'ROUND(SUM(amount_due),2)');
        $feb = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $feb = array_column($feb, 'ROUND(SUM(amount_due),2)');
        $mar = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $mar = array_column($mar, 'ROUND(SUM(amount_due),2)');
        $apr = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $apr = array_column($apr, 'ROUND(SUM(amount_due),2)');
        $may = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $may = array_column($may, 'ROUND(SUM(amount_due),2)');
        $jun = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $jun = array_column($jun, 'ROUND(SUM(amount_due),2)');
        $jul = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $jul = array_column($jul, 'ROUND(SUM(amount_due),2)');
        $aug = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->pluck('ROUND(SUM(amount_due),2)')->toArray();
        $aug = array_column($aug, 'ROUND(SUM(amount_due),2)');
        $sep = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $sep = array_column($sep, 'ROUND(SUM(amount_due),2)');
        $oct = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $oct = array_column($oct, 'ROUND(SUM(amount_due),2)');
        $nov = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $nov = array_column($nov, 'ROUND(SUM(amount_due),2)');
        $dec = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
        $dec = array_column($dec, 'ROUND(SUM(amount_due),2)');
       
        $yearsales = array($jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec);

        //machine stats washer one
        $washerOneStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');
        $washerOneStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 1)->pluck('SUM(used)');

        $washerOneStats = array($washerOneStats_jan, $washerOneStats_feb, $washerOneStats_mar, $washerOneStats_apr, $washerOneStats_may, $washerOneStats_jun, $washerOneStats_jul, $washerOneStats_aug, $washerOneStats_sep, $washerOneStats_oct, $washerOneStats_nov, $washerOneStats_dec);

        //machine stats washer two
        $washerTwoStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');
        $washerTwoStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 2)->pluck('SUM(used)');

        $washerTwoStats = array($washerTwoStats_jan, $washerTwoStats_feb, $washerTwoStats_mar, $washerTwoStats_apr, $washerTwoStats_may, $washerTwoStats_jun, $washerTwoStats_jul, $washerTwoStats_aug, $washerTwoStats_sep, $washerTwoStats_oct, $washerTwoStats_nov, $washerTwoStats_dec);

        //machine stats washer three
        $washerThreeStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');
        $washerThreeStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 3)->pluck('SUM(used)');

        $washerThreeStats = array($washerThreeStats_jan, $washerThreeStats_feb, $washerThreeStats_mar, $washerThreeStats_apr, $washerThreeStats_may, $washerThreeStats_jun, $washerThreeStats_jul, $washerThreeStats_aug, $washerThreeStats_sep, $washerThreeStats_oct, $washerThreeStats_nov, $washerThreeStats_dec);

        //machine stats washer four
        $washerFourStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');
        $washerFourStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 4)->pluck('SUM(used)');

        $washerFourStats = array($washerFourStats_jan, $washerFourStats_feb, $washerFourStats_mar, $washerFourStats_apr, $washerFourStats_may, $washerFourStats_jun, $washerFourStats_jul, $washerFourStats_aug, $washerFourStats_sep, $washerFourStats_oct, $washerFourStats_nov, $washerFourStats_dec);

        //machine stats washer five
        $washerFiveStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');
        $washerFiveStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 5)->pluck('SUM(used)');

        $washerFiveStats = array($washerFiveStats_jan, $washerFiveStats_feb, $washerFiveStats_mar, $washerFiveStats_apr, $washerFiveStats_may, $washerFiveStats_jun, $washerFiveStats_jul, $washerFiveStats_aug, $washerFiveStats_sep, $washerFiveStats_oct, $washerFiveStats_nov, $washerFiveStats_dec);

        //machine stats washer six
        $washerSixStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');
        $washerSixStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 6)->pluck('SUM(used)');

        $washerSixStats = array($washerSixStats_jan, $washerSixStats_feb, $washerSixStats_mar, $washerSixStats_apr, $washerSixStats_may, $washerSixStats_jun, $washerSixStats_jul, $washerSixStats_aug, $washerSixStats_sep, $washerSixStats_oct, $washerSixStats_nov, $washerSixStats_dec);

        //machine stats washer Seven
        $washerSevenStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');
        $washerSevenStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 7)->pluck('SUM(used)');

        $washerSevenStats = array($washerSevenStats_jan, $washerSevenStats_feb, $washerSevenStats_mar, $washerSevenStats_apr, $washerSevenStats_may, $washerSevenStats_jun, $washerSevenStats_jul, $washerSevenStats_aug, $washerSevenStats_sep, $washerSevenStats_oct, $washerSevenStats_nov, $washerSevenStats_dec);

        //machine stats washer Eight
        $washerEightStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');
        $washerEightStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 8)->pluck('SUM(used)');

        $washerEightStats = array($washerEightStats_jan, $washerEightStats_feb, $washerEightStats_mar, $washerEightStats_apr, $washerEightStats_may, $washerEightStats_jun, $washerEightStats_jul, $washerEightStats_aug, $washerEightStats_sep, $washerEightStats_oct, $washerEightStats_nov, $washerEightStats_dec);

        //machine stats washer Nine
        $washerNineStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');
        $washerNineStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 9)->pluck('SUM(used)');

        $washerNineStats = array($washerNineStats_jan, $washerNineStats_feb, $washerNineStats_mar, $washerNineStats_apr, $washerNineStats_may, $washerNineStats_jun, $washerNineStats_jul, $washerNineStats_aug, $washerNineStats_sep, $washerNineStats_oct, $washerNineStats_nov, $washerNineStats_dec);

        //machine stats washer Ten
        $washerTenStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');
        $washerTenStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 10)->pluck('SUM(used)');

        $washerTenStats = array($washerTenStats_jan, $washerTenStats_feb, $washerTenStats_mar, $washerTenStats_apr, $washerTenStats_may, $washerTenStats_jun, $washerTenStats_jul, $washerTenStats_aug, $washerTenStats_sep, $washerTenStats_oct, $washerTenStats_nov, $washerTenStats_dec);

        //machine stats washer Eleven
        $washerElevenStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');
        $washerElevenStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 11)->pluck('SUM(used)');

        $washerElevenStats = array($washerElevenStats_jan, $washerElevenStats_feb, $washerElevenStats_mar, $washerElevenStats_apr, $washerElevenStats_may, $washerElevenStats_jun, $washerElevenStats_jul, $washerElevenStats_aug, $washerElevenStats_sep, $washerElevenStats_oct, $washerElevenStats_nov, $washerElevenStats_dec);

        //machine stats washer Twelve
        $washerTwelveStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');
        $washerTwelveStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 12)->pluck('SUM(used)');

        $washerTwelveStats = array($washerTwelveStats_jan, $washerTwelveStats_feb, $washerTwelveStats_mar, $washerTwelveStats_apr, $washerTwelveStats_may, $washerTwelveStats_jun, $washerTwelveStats_jul, $washerTwelveStats_aug, $washerTwelveStats_sep, $washerTwelveStats_oct, $washerTwelveStats_nov, $washerTwelveStats_dec);

        //machine stats dryer One
        $dryerOneStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');
        $dryerOneStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 13)->pluck('SUM(used)');

        $dryerOneStats = array($dryerOneStats_jan, $dryerOneStats_feb, $dryerOneStats_mar, $dryerOneStats_apr, $dryerOneStats_may, $dryerOneStats_jun, $dryerOneStats_jul, $dryerOneStats_aug, $dryerOneStats_sep, $dryerOneStats_oct, $dryerOneStats_nov, $dryerOneStats_dec);

        //machine stats dryer Two
        $dryerTwoStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');
        $dryerTwoStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 14)->pluck('SUM(used)');

        $dryerTwoStats = array($dryerTwoStats_jan, $dryerTwoStats_feb, $dryerTwoStats_mar, $dryerTwoStats_apr, $dryerTwoStats_may, $dryerTwoStats_jun, $dryerTwoStats_jul, $dryerTwoStats_aug, $dryerTwoStats_sep, $dryerTwoStats_oct, $dryerTwoStats_nov, $dryerTwoStats_dec);

        //machine stats dryer Three
        $dryerThreeStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');
        $dryerThreeStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 15)->pluck('SUM(used)');

        $dryerThreeStats = array($dryerThreeStats_jan, $dryerThreeStats_feb, $dryerThreeStats_mar, $dryerThreeStats_apr, $dryerThreeStats_may, $dryerThreeStats_jun, $dryerThreeStats_jul, $dryerThreeStats_aug, $dryerThreeStats_sep, $dryerThreeStats_oct, $dryerThreeStats_nov, $dryerThreeStats_dec);

        //machine stats dryer Four
        $dryerFourStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');
        $dryerFourStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 16)->pluck('SUM(used)');

        $dryerFourStats = array($dryerFourStats_jan, $dryerFourStats_feb, $dryerFourStats_mar, $dryerFourStats_apr, $dryerFourStats_may, $dryerFourStats_jun, $dryerFourStats_jul, $dryerFourStats_aug, $dryerFourStats_sep, $dryerFourStats_oct, $dryerFourStats_nov, $dryerFourStats_dec);

        //machine stats dryer Five
        $dryerFiveStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');
        $dryerFiveStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 17)->pluck('SUM(used)');

        $dryerFiveStats = array($dryerFiveStats_jan, $dryerFiveStats_feb, $dryerFiveStats_mar, $dryerFiveStats_apr, $dryerFiveStats_may, $dryerFiveStats_jun, $dryerFiveStats_jul, $dryerFiveStats_aug, $dryerFiveStats_sep, $dryerFiveStats_oct, $dryerFiveStats_nov, $dryerFiveStats_dec);

        //machine stats dryer Six
        $dryerSixStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');
        $dryerSixStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 18)->pluck('SUM(used)');

        $dryerSixStats = array($dryerSixStats_jan, $dryerSixStats_feb, $dryerSixStats_mar, $dryerSixStats_apr, $dryerSixStats_may, $dryerSixStats_jun, $dryerSixStats_jul, $dryerSixStats_aug, $dryerSixStats_sep, $dryerSixStats_oct, $dryerSixStats_nov, $dryerSixStats_dec);

        //machine stats dryer Seven
        $dryerSevenStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');
        $dryerSevenStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 19)->pluck('SUM(used)');

        $dryerSevenStats = array($dryerSevenStats_jan, $dryerSevenStats_feb, $dryerSevenStats_mar, $dryerSevenStats_apr, $dryerSevenStats_may, $dryerSevenStats_jun, $dryerSevenStats_jul, $dryerSevenStats_aug, $dryerSevenStats_sep, $dryerSevenStats_oct, $dryerSevenStats_nov, $dryerSevenStats_dec);

        //machine stats dryer Eight
        $dryerEightStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');
        $dryerEightStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 20)->pluck('SUM(used)');

        $dryerEightStats = array($dryerEightStats_jan, $dryerEightStats_feb, $dryerEightStats_mar, $dryerEightStats_apr, $dryerEightStats_may, $dryerEightStats_jun, $dryerEightStats_jul, $dryerEightStats_aug, $dryerEightStats_sep, $dryerEightStats_oct, $dryerEightStats_nov, $dryerEightStats_dec);

        //machine stats dryer Nine
        $dryerNineStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');
        $dryerNineStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 21)->pluck('SUM(used)');

        $dryerNineStats = array($dryerNineStats_jan, $dryerNineStats_feb, $dryerNineStats_mar, $dryerNineStats_apr, $dryerNineStats_may, $dryerNineStats_jun, $dryerNineStats_jul, $dryerNineStats_aug, $dryerNineStats_sep, $dryerNineStats_oct, $dryerNineStats_nov, $dryerNineStats_dec);

        //machine stats dryer Ten
        $dryerTenStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');
        $dryerTenStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 22)->pluck('SUM(used)');

        $dryerTenStats = array($dryerTenStats_jan, $dryerTenStats_feb, $dryerTenStats_mar, $dryerTenStats_apr, $dryerTenStats_may, $dryerTenStats_jun, $dryerTenStats_jul, $dryerTenStats_aug, $dryerTenStats_sep, $dryerTenStats_oct, $dryerTenStats_nov, $dryerTenStats_dec);

        //machine stats dryer Eleven
        $dryerElevenStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');
        $dryerElevenStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 23)->pluck('SUM(used)');

        $dryerElevenStats = array($dryerElevenStats_jan, $dryerElevenStats_feb, $dryerElevenStats_mar, $dryerElevenStats_apr, $dryerElevenStats_may, $dryerElevenStats_jun, $dryerElevenStats_jul, $dryerElevenStats_aug, $dryerElevenStats_sep, $dryerElevenStats_oct, $dryerElevenStats_nov, $dryerElevenStats_dec);

        //machine stats dryer Twelve
        $dryerTwelveStats_jan = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'January')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_feb = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'February')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_mar = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'March')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_apr = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'April')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_may = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'May')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_jun = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'June')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_jul = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'July')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_aug = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_sep = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'September')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_oct = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'October')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_nov = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'November')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');
        $dryerTwelveStats_dec = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'December')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')");
        })->where('product_id', '=', 24)->pluck('SUM(used)');

        $dryerTwelveStats = array($dryerTwelveStats_jan, $dryerTwelveStats_feb, $dryerTwelveStats_mar, $dryerTwelveStats_apr, $dryerTwelveStats_may, $dryerTwelveStats_jun, $dryerTwelveStats_jul, $dryerTwelveStats_aug, $dryerTwelveStats_sep, $dryerTwelveStats_oct, $dryerTwelveStats_nov, $dryerTwelveStats_dec);

        //machine one cycle weekly
        $washerOneStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 1)->pluck('SUM(used)')->first();

        $dryerOneStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 13)->pluck('SUM(used)')->first();

        //machine two cycle weekly
        $washerTwoStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 2)->pluck('SUM(used)')->first();

        $dryerTwoStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 14)->pluck('SUM(used)')->first();

        //machine three cycle weekly
        $washerThreeStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 3)->pluck('SUM(used)')->first();

        $dryerThreeStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 15)->pluck('SUM(used)')->first();

        //machine Four cycle weekly
        $washerFourStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 4)->pluck('SUM(used)')->first();

        $dryerFourStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 16)->pluck('SUM(used)')->first();

        //machine Five cycle weekly
        $washerFiveStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 5)->pluck('SUM(used)')->first();

        $dryerFiveStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 17)->pluck('SUM(used)')->first();

        //machine Six cycle weekly
        $washerSixStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 6)->pluck('SUM(used)')->first();

        $dryerSixStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 18)->pluck('SUM(used)')->first();

        //machine Seven cycle weekly
        $washerSevenStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 7)->pluck('SUM(used)')->first();

        $dryerSevenStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 19)->pluck('SUM(used)')->first();

        //machine Eight cycle weekly
        $washerEightStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 8)->pluck('SUM(used)')->first();

        $dryerEightStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 20)->pluck('SUM(used)')->first();

        //machine Nine cycle weekly
        $washerNineStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 9)->pluck('SUM(used)')->first();

        $dryerNineStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 21)->pluck('SUM(used)')->first();

        //machine Ten cycle weekly
        $washerTenStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 10)->pluck('SUM(used)')->first();

        $dryerTenStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 22)->pluck('SUM(used)')->first();

        //machine Eleven cycle weekly
        $washerElevenStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 11)->pluck('SUM(used)')->first();

        $dryerElevenStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 23)->pluck('SUM(used)')->first();

        //machine Twelve cycle weekly
        $washerTwelveStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 12)->pluck('SUM(used)')->first();

        $dryerTwelveStats_weekly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where('transaction_date', '>', Carbon::now()->subDays(7));
        })->where('product_id', '=', 24)->pluck('SUM(used)')->first();

        //machine One cycle monthly
        $washerOneStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 1)->pluck('SUM(used)')->first();

        $dryerOneStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 13)->pluck('SUM(used)')->first();

        //machine Two cycle monthly
        $washerTwoStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 2)->pluck('SUM(used)')->first();

        $dryerTwoStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 14)->pluck('SUM(used)')->first();

        //machine Three cycle monthly
        $washerThreeStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 3)->pluck('SUM(used)')->first();

        $dryerThreeStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 15)->pluck('SUM(used)')->first();

        //machine Four cycle monthly
        $washerFourStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 4)->pluck('SUM(used)')->first();

        $dryerFourStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 16)->pluck('SUM(used)')->first();

        //machine Five cycle monthly
        $washerFiveStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 5)->pluck('SUM(used)')->first();

        $dryerFiveStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 17)->pluck('SUM(used)')->first();

        //machine Six cycle monthly
        $washerSixStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 6)->pluck('SUM(used)')->first();

        $dryerSixStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 18)->pluck('SUM(used)')->first();

        //machine Seven cycle monthly
        $washerSevenStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 7)->pluck('SUM(used)')->first();

        $dryerSevenStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 19)->pluck('SUM(used)')->first();

        //machine Eight cycle monthly
        $washerEightStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 8)->pluck('SUM(used)')->first();

        $dryerEightStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 20)->pluck('SUM(used)')->first();

        //machine Nine cycle monthly
        $washerNineStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 9)->pluck('SUM(used)')->first();

        $dryerNineStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 21)->pluck('SUM(used)')->first();

        //machine Ten cycle monthly
        $washerTenStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 10)->pluck('SUM(used)')->first();

        $dryerTenStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 22)->pluck('SUM(used)')->first();

        //machine Eleven cycle monthly
        $washerElevenStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 11)->pluck('SUM(used)')->first();

        $dryerElevenStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 23)->pluck('SUM(used)')->first();

        //machine Twelve cycle monthly
        $washerTwelveStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 12)->pluck('SUM(used)')->first();

        $dryerTwelveStats_monthly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%M'))"), '=', Carbon::now()->format('F'))->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 24)->pluck('SUM(used)')->first();

         //machine One cycle yearly
        $washerOneStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 1)->pluck('SUM(used)')->first();

        $dryerOneStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 13)->pluck('SUM(used)')->first();

        //machine Two cycle yearly
        $washerTwoStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 2)->pluck('SUM(used)')->first();

        $dryerTwoStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 14)->pluck('SUM(used)')->first();

        //machine Three cycle yearly
        $washerThreeStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 3)->pluck('SUM(used)')->first();

        $dryerThreeStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 15)->pluck('SUM(used)')->first();

        //machine Four cycle yearly
        $washerFourStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 4)->pluck('SUM(used)')->first();

        $dryerFourStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 16)->pluck('SUM(used)')->first();

        //machine Five cycle yearly
        $washerFiveStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 5)->pluck('SUM(used)')->first();

        $dryerFiveStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 17)->pluck('SUM(used)')->first();

        //machine Six cycle yearly
        $washerSixStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 6)->pluck('SUM(used)')->first();

        $dryerSixStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 18)->pluck('SUM(used)')->first();

        //machine Seven cycle yearly
        $washerSevenStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 7)->pluck('SUM(used)')->first();

        $dryerSevenStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 19)->pluck('SUM(used)')->first();

        //machine Eight cycle yearly
        $washerEightStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 8)->pluck('SUM(used)')->first();

        $dryerEightStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 20)->pluck('SUM(used)')->first();

        //machine Nine cycle yearly
        $washerNineStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 9)->pluck('SUM(used)')->first();

        $dryerNineStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 21)->pluck('SUM(used)')->first();

        //machine Ten cycle yearly
        $washerTenStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 10)->pluck('SUM(used)')->first();

        $dryerTenStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 22)->pluck('SUM(used)')->first();

        //machine Eleven cycle yearly
        $washerElevenStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 11)->pluck('SUM(used)')->first();

        $dryerElevenStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 23)->pluck('SUM(used)')->first();

        //machine Twelve cycle yearly
        $washerTwelveStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 12)->pluck('SUM(used)')->first();

        $dryerTwelveStats_yearly = Sales_details::selectRaw('SUM(used)')->whereHas('sale',function($query) use ($yearnow) { 
            $query->where(DB::raw("(DATE_FORMAT(transaction_date, '%Y'))"), '=', $yearnow);
        })->where('product_id', '=', 24)->pluck('SUM(used)')->first();



        //donut sales by payment mode
        $cashpay = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->where('payment_mode', 'cash')->get()->toArray();
        $cashpay = array_column($cashpay, 'ROUND(SUM(amount_due),2)');
        $loadpay = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->where('payment_mode', 'card load')->get()->toArray();
        $loadpay = array_column($loadpay, 'ROUND(SUM(amount_due),2)');
        
        //sales today
        $datenow = Carbon::now()->format('Y-m-d');
        $twentyfour_one = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '00:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '00:59')")->get()->toArray();
        $twentyfour_one = array_column($twentyfour_one, 'ROUND(SUM(amount_due),2)');
        $one_two = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '01:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '01:59')")->get()->toArray();
        $one_two = array_column($one_two, 'ROUND(SUM(amount_due),2)');
        $two_three = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '02:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '02:59')")->get()->toArray();
        $two_three = array_column($two_three, 'ROUND(SUM(amount_due),2)');
        $three_four = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '03:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '03:59')")->get()->toArray();
        $three_four = array_column($three_four, 'ROUND(SUM(amount_due),2)');
        $four_five = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '04:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '04:59')")->get()->toArray();
        $four_five = array_column($four_five, 'ROUND(SUM(amount_due),2)');
        $five_six = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '05:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '05:59')")->get()->toArray();
        $five_six = array_column($five_six, 'ROUND(SUM(amount_due),2)');
        $six_seven = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '06:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '06:59')")->get()->toArray();
        $six_seven = array_column($six_seven, 'ROUND(SUM(amount_due),2)');
        $seven_eight = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '07:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '07:59')")->get()->toArray();
        $seven_eight = array_column($seven_eight, 'ROUND(SUM(amount_due),2)');
        $eight_nine = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '08:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '08:59')")->get()->toArray();
        $eight_nine = array_column($eight_nine, 'ROUND(SUM(amount_due),2)');
        $nine_ten = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '09:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '09:59')")->get()->toArray();
        $nine_ten = array_column($nine_ten, 'ROUND(SUM(amount_due),2)');
        $ten_eleven = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '10:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '10:59')")->get()->toArray();
        $ten_eleven = array_column($ten_eleven, 'ROUND(SUM(amount_due),2)');
        $eleven_twelve = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '11:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '11:59')")->get()->toArray();
        $eleven_twelve = array_column($eleven_twelve, 'ROUND(SUM(amount_due),2)');
        $twelve_thirteen = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '12:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '12:59')")->get()->toArray();
        $twelve_thirteen = array_column($twelve_thirteen, 'ROUND(SUM(amount_due),2)');
        $thirteen_fourteen = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '13:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '13:59')")->get()->toArray();
        $thirteen_fourteen = array_column($thirteen_fourteen, 'ROUND(SUM(amount_due),2)');
        $fourteen_fifteen = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '14:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '14:59')")->get()->toArray();
        $fourteen_fifteen = array_column($fourteen_fifteen, 'ROUND(SUM(amount_due),2)');
        $fifteen_sixteen = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '15:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '15:59')")->get()->toArray();
        $fifteen_sixteen = array_column($fifteen_sixteen, 'ROUND(SUM(amount_due),2)');
        $sixteen_seventeen = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '16:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '16:59')")->get()->toArray();
        $sixteen_seventeen = array_column($sixteen_seventeen, 'ROUND(SUM(amount_due),2)');
        $seventeen_eighteen = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '17:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '17:59')")->get()->toArray();
        $seventeen_eighteen = array_column($seventeen_eighteen, 'ROUND(SUM(amount_due),2)');
        $eighteen_nineteen = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '18:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '18:59')")->get()->toArray();
        $eighteen_nineteen = array_column($eighteen_nineteen, 'ROUND(SUM(amount_due),2)');
        $nineteen_twenty = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '19:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '19:59')")->get()->toArray();
        $nineteen_twenty = array_column($nineteen_twenty, 'ROUND(SUM(amount_due),2)');
        $twenty_twentyone = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '20:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '20:59')")->get()->toArray();
        $twenty_twentyone = array_column($twenty_twentyone, 'ROUND(SUM(amount_due),2)');
        $twentyone_twentytwo = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '21:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '21:59')")->get()->toArray();
        $twentyone_twentytwo = array_column($twentyone_twentytwo, 'ROUND(SUM(amount_due),2)');
        $twentytwo_twentythree = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '22:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '22:59')")->get()->toArray();
        $twentytwo_twentythree = array_column($twentytwo_twentythree, 'ROUND(SUM(amount_due),2)');
        $twentythree_twentyfour = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%Y-%m-%d') = '$datenow')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') >= '23:00')")->whereRaw("(DATE_FORMAT(transaction_date, '%H:%i') <= '23:59')")->get()->toArray();
        $twentythree_twentyfour = array_column($twentythree_twentyfour, 'ROUND(SUM(amount_due),2)');

       
        $salestoday = array($twentyfour_one, $one_two, $two_three, $three_four, $four_five, $five_six, $six_seven, $seven_eight, $eight_nine, $nine_ten,$ten_eleven, $eleven_twelve, $twelve_thirteen, $thirteen_fourteen, $fourteen_fifteen, $fifteen_sixteen, $sixteen_seventeen, $seventeen_eighteen, $eighteen_nineteen, $nineteen_twenty, $twenty_twentyone, $twentyone_twentytwo, $twentytwo_twentythree, $twentythree_twentyfour);

        $topmembers = DB::table('sales')->selectRaw('DISTINCT (sales.member_id), CONCAT(users.firstname, " ", users.lastname) as name, SUM(amount_due) as amount_due')->groupBy('member_id')->join('users', 'sales.member_id', '=', 'users.id')->limit(10)->orderBy('amount_due', 'desc')->get()->toArray();

        return view('admin.dashboard')->with(['newmembers' => $newmembers, 'lowstock' => $lowstock, 'reloadsales' => $reloadsales, 'sales' => $sales,'cashpay' => json_encode($cashpay,JSON_NUMERIC_CHECK), 'loadpay' => json_encode($loadpay,JSON_NUMERIC_CHECK),'yearsales' => $yearsales,'salestoday' => $salestoday, 'topmembers' => $topmembers, 'washerOneStats' => $washerOneStats, 'washerTwoStats' => $washerTwoStats, 'washerThreeStats' => $washerThreeStats, 'washerFourStats' => $washerFourStats, 'washerFiveStats' => $washerFiveStats, 'washerSixStats' => $washerSixStats, 'washerSevenStats' => $washerSevenStats, 'washerEightStats' => $washerEightStats, 'washerNineStats' => $washerNineStats, 'washerTenStats' => $washerTenStats, 'washerElevenStats' => $washerElevenStats, 'washerTwelveStats' => $washerTwelveStats, 'dryerOneStats' => $dryerOneStats, 'dryerTwoStats' => $dryerTwoStats, 'dryerThreeStats' => $dryerThreeStats, 'dryerFourStats' => $dryerFourStats, 'dryerFiveStats' => $dryerFiveStats, 'dryerSixStats' => $dryerSixStats, 'dryerSevenStats' => $dryerSevenStats, 'dryerEightStats' => $dryerEightStats, 'dryerNineStats' => $dryerNineStats, 'dryerTenStats' => $dryerTenStats, 'dryerElevenStats' => $dryerElevenStats, 'dryerTwelveStats' => $dryerTwelveStats, 'washerOneStats_weekly' => $washerOneStats_weekly, 'dryerOneStats_weekly' => $dryerOneStats_weekly, 'washerTwoStats_weekly' => $washerTwoStats_weekly, 'dryerTwoStats_weekly' => $dryerTwoStats_weekly, 'washerThreeStats_weekly' => $washerThreeStats_weekly, 'dryerThreeStats_weekly' => $dryerThreeStats_weekly, 'washerFourStats_weekly' => $washerFourStats_weekly, 'dryerFourStats_weekly' => $dryerFourStats_weekly, 'washerFiveStats_weekly' => $washerFiveStats_weekly, 'dryerFiveStats_weekly' => $dryerFiveStats_weekly, 'washerSixStats_weekly' => $washerSixStats_weekly, 'dryerSixStats_weekly' => $dryerSixStats_weekly, 'washerSevenStats_weekly' => $washerSevenStats_weekly, 'dryerSevenStats_weekly' => $dryerSevenStats_weekly, 'washerEightStats_weekly' => $washerEightStats_weekly, 'dryerEightStats_weekly' => $dryerEightStats_weekly, 'washerNineStats_weekly' => $washerNineStats_weekly, 'dryerNineStats_weekly' => $dryerNineStats_weekly, 'washerTenStats_weekly' => $washerTenStats_weekly, 'dryerTenStats_weekly' => $dryerTenStats_weekly, 'washerElevenStats_weekly' => $washerElevenStats_weekly, 'dryerElevenStats_weekly' => $dryerElevenStats_weekly, 'washerTwelveStats_weekly' => $washerTwelveStats_weekly, 'dryerTwelveStats_weekly' => $dryerTwelveStats_weekly, 'washerOneStats_monthly' => $washerOneStats_monthly, 'dryerOneStats_monthly' => $dryerOneStats_monthly, 'washerOneStats_monthly' => $washerOneStats_monthly, 'dryerOneStats_monthly' => $dryerOneStats_monthly, 'washerTwoStats_monthly' => $washerTwoStats_monthly, 'dryerTwoStats_monthly' => $dryerTwoStats_monthly, 'washerThreeStats_monthly' => $washerThreeStats_monthly, 'dryerThreeStats_monthly' => $dryerThreeStats_monthly, 'washerFourStats_monthly' => $washerFourStats_monthly, 'dryerFourStats_monthly' => $dryerFourStats_monthly, 'washerFiveStats_monthly' => $washerFiveStats_monthly, 'dryerFiveStats_monthly' => $dryerFiveStats_monthly, 'washerSixStats_monthly' => $washerSixStats_monthly, 'dryerSixStats_monthly' => $dryerSixStats_monthly, 'washerSevenStats_monthly' => $washerSevenStats_monthly, 'dryerSevenStats_monthly' => $dryerSevenStats_monthly, 'washerEightStats_monthly' => $washerEightStats_monthly, 'dryerEightStats_monthly' => $dryerEightStats_monthly, 'washerNineStats_monthly' => $washerNineStats_monthly, 'dryerNineStats_monthly' => $dryerNineStats_monthly, 'washerTenStats_monthly' => $washerTenStats_monthly, 'dryerTenStats_monthly' => $dryerTenStats_monthly, 'washerElevenStats_monthly' => $washerElevenStats_monthly, 'dryerElevenStats_monthly' => $dryerElevenStats_monthly, 'washerTwelveStats_monthly' => $washerTwelveStats_monthly, 'dryerTwelveStats_monthly' => $dryerTwelveStats_monthly, 'washerOneStats_monthly' => $washerOneStats_monthly, 'dryerOneStats_monthly' => $dryerOneStats_monthly,'washerOneStats_yearly' => $washerOneStats_yearly, 'dryerOneStats_yearly' => $dryerOneStats_yearly, 'washerTwoStats_yearly' => $washerTwoStats_yearly, 'dryerTwoStats_yearly' => $dryerTwoStats_yearly, 'washerThreeStats_yearly' => $washerThreeStats_yearly, 'dryerThreeStats_yearly' => $dryerThreeStats_yearly, 'washerFourStats_yearly' => $washerFourStats_yearly, 'dryerFourStats_yearly' => $dryerFourStats_yearly, 'washerFiveStats_yearly' => $washerFiveStats_yearly, 'dryerFiveStats_yearly' => $dryerFiveStats_yearly, 'washerSixStats_yearly' => $washerSixStats_yearly, 'dryerSixStats_yearly' => $dryerSixStats_yearly, 'washerSevenStats_yearly' => $washerSevenStats_yearly, 'dryerSevenStats_yearly' => $dryerSevenStats_yearly, 'washerEightStats_yearly' => $washerEightStats_yearly, 'dryerEightStats_yearly' => $dryerEightStats_yearly, 'washerNineStats_yearly' => $washerNineStats_yearly, 'dryerNineStats_yearly' => $dryerNineStats_yearly, 'washerTenStats_yearly' => $washerTenStats_yearly, 'dryerTenStats_yearly' => $dryerTenStats_yearly, 'washerElevenStats_yearly' => $washerElevenStats_yearly, 'dryerElevenStats_yearly' => $dryerElevenStats_yearly, 'washerTwelveStats_yearly' => $washerTwelveStats_yearly, 'dryerTwelveStats_yearly' => $dryerTwelveStats_yearly, 'washerOneStats_yearly' => $washerOneStats_yearly, 'dryerOneStats_yearly' => $dryerOneStats_yearly, ]);
    }

}
