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
        $aug = DB::table('sales')->selectRaw('ROUND(SUM(amount_due),2)')->whereRaw("(DATE_FORMAT(transaction_date, '%M') = 'August')")->whereRaw("(DATE_FORMAT(transaction_date, '%Y') = '$yearnow')")->get()->toArray();
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

        //machines in use
        $washer_use = DB::table('products')->where('switch', 1)->where('product_id', '<=', 12)->count();
        $dryer_use = DB::table('products')->where('switch', 1)->where('product_id', '>', 12)->count();

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

        return view('admin.dashboard')->with(['newmembers' => $newmembers, 'lowstock' => $lowstock, 'reloadsales' => $reloadsales, 'sales' => $sales, 'washer_use' => $washer_use, 'dryer_use' => $dryer_use, 'cashpay' => json_encode($cashpay,JSON_NUMERIC_CHECK), 'loadpay' => json_encode($loadpay,JSON_NUMERIC_CHECK),'yearsales' => $yearsales,'salestoday' => $salestoday, 'topmembers' => $topmembers]);
    }

}
