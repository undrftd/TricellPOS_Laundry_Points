<?php

namespace App\Http\Controllers\Staff;

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

class SalesLogsController extends Controller
{
    public function index()
    {
        $sales = Sale::orderBy('transaction_date', 'desc')->paginate(7);
        $sumsales = Sale::sum('amount_due');
        return view('staff.sales')->with(['sales'=> $sales, 'sumsales' => $sumsales]);
    }

    public function showdetails(Request $request, $id)
    {   
        $sales = Sale::find($id);
        if(!isset($sales))
        {
            return view('errors.404');
        } 
        else
        {
            $profile = DB::table('profile')->select('*')->where('id', 1)->first();
            $salesdetails = Sales_details::with('product')->where('sales_id', $sales->id)->get();
            $subtotal = Sales_details::selectRaw('SUM(subtotal)')->where('sales_id', $sales->id)->pluck('SUM(subtotal)');
            $cashier = User::find($sales->staff_name);
            
            if(isset($sales->discount->discount_name))
            {
                $discounts = Discount::where('id', $sales->discount_id)->first();
            }
            else
            {
                $discounts = '';
            }
            return view('staff.salesreceipt')->with(['sales' => $sales, 'salesdetails' => $salesdetails, 'cashier' => $cashier, 'profile' => $profile,'discounts' => $discounts, 'subtotal' => $subtotal]); 
        } 
    }

    public function destroy(Request $request)
    {
        if($request->type == 'guest')
        {
            $guest = Guest::find($request->cust_id)->delete();
        }
        $sales = Sale::find($request->sales_id)->delete();
        $salesdetails = Sales_details::where('sales_id', $request->sales_id)->delete();
    }

    public function filter(Request $request)
    {
        $account_type = $request->account_type;  
        $payment_mode = $request->payment_mode;
        $daterange = array_map('trim', explode('-', $request->date_filter));

        $date_start = date('Y-m-d',strtotime($daterange[0]));
        $date_end = date('Y-m-d',strtotime($daterange[1]));

        if($account_type == 'Any' && $payment_mode != 'Any')
        {
            $sales = Sale::where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->where('payment_mode', $payment_mode)->orderBy('transaction_date', 'desc')->paginate(7);

            $sales->appends($request->only('account_type', 'payment_mode', 'date_filter'));
           
            $count = $sales->count();
            $totalcount = Sale::where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->where('payment_mode', $payment_mode)->count();

            $sumsales = Sale::where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->Where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end)->where('payment_mode', $payment_mode)->sum('amount_due');
            return view('staff.sales')->with(['sales' => $sales,'count' => $count, 'account_type' => $account_type, 'payment_mode' => $payment_mode, 'date_start' => $date_start, 'date_end' => $date_end, 'sumsales' => $sumsales, 'totalcount' => $totalcount]);  
        }
        else if($account_type == 'Member' && $payment_mode == 'Any')
        {
            $sales = Sale::with('user')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
            {
                $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
            })->whereHas('User', function($query) use ($account_type)
                {
                        $query->where('role', $account_type);
                })->orderBy('transaction_date', 'desc')->paginate(7);

            $sales->appends($request->only('account_type', 'payment_mode', 'date_filter'));
           
            $count = $sales->count();
            $totalcount = Sale::with('user')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->whereHas('User', function($query) use ($account_type)
                {
                        $query->where('role', $account_type);
                })->count();

            $sumsales = Sale::with('user')->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->Where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end)->whereHas('User', function($query) use ($account_type)
                {
                        $query->where('role', $account_type);
                })->sum('amount_due');
            return view('staff.sales')->with(['sales' => $sales,'count' => $count, 'account_type' => $account_type, 'payment_mode' => $payment_mode, 'date_start' => $date_start, 'date_end' => $date_end, 'sumsales' => $sumsales, 'totalcount' => $totalcount]);  
        }
        else if($account_type == 'Walk-in' && $payment_mode == 'Cash')
        {
            $sales = Sale::with('guest')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
            {
                $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
            })->where('member_id', 0)->orderBy('transaction_date', 'desc')->paginate(7);

            $sales->appends($request->only('account_type', 'payment_mode', 'date_filter'));
           
            $count = $sales->count();
            $totalcount = Sale::with('guest')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
            {
                $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
            })->where('member_id', 0)->orderBy('transaction_date', 'desc')->count();

            $sumsales = Sale::with('guest')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
            {
                $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
            })->where('member_id', 0)->sum('amount_due');

            return view('staff.sales')->with(['sales' => $sales,'count' => $count, 'account_type' => $account_type, 'payment_mode' => $payment_mode, 'date_start' => $date_start, 'date_end' => $date_end, 'sumsales' => $sumsales, 'totalcount' => $totalcount]);  
        }
        else if($account_type == 'Member' && $payment_mode != 'Any')
        {
            $sales = Sale::with('user')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->whereHas('User', function($query) use ($account_type)
                {
                        $query->where('role', $account_type);
                })->where('payment_mode', $payment_mode)->orderBy('transaction_date', 'desc')->paginate(7);

            $sales->appends($request->only('account_type', 'payment_mode', 'date_filter'));
           
            $count = $sales->count();
            $totalcount = Sale::where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->whereHas('User', function($query) use ($account_type)
                {
                        $query->where('role', $account_type);
                })->where('payment_mode', $payment_mode)->count();

            $sumsales = Sale::where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->Where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end)->whereHas('User', function($query) use ($account_type)
                {
                        $query->where('role', $account_type);
                })->where('payment_mode', $payment_mode)->sum('amount_due');
            return view('staff.sales')->with(['sales' => $sales,'count' => $count, 'account_type' => $account_type, 'payment_mode' => $payment_mode, 'date_start' => $date_start, 'date_end' => $date_end, 'sumsales' => $sumsales, 'totalcount' => $totalcount]); 
        }
        else 
        {
            $sales = Sale::with('user')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->orderBy('transaction_date', 'desc')->paginate(7);

            $sales->appends($request->only('account_type', 'payment_mode', 'date_filter'));
           
            $count = $sales->count();
            $totalcount = Sale::where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->count();

            $sumsales = Sale::where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->Where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end)->sum('amount_due');
            return view('staff.sales')->with(['sales' => $sales,'count' => $count, 'account_type' => $account_type, 'payment_mode' => $payment_mode, 'date_start' => $date_start, 'date_end' => $date_end, 'sumsales' => $sumsales, 'totalcount' => $totalcount]); 
        }
    }

    public function export(Request $request)
    {
        $url = url()->previous();

        $parts = parse_url($url, PHP_URL_QUERY);
        parse_str($parts, $query);
        
        if(isset($query['date_filter']) && isset($query['account_type']) && isset($query['payment_mode']))
        {
            $daterange = array_map('trim', explode('-', $query['date_filter']));
            $date_start = date('Y-m-d',strtotime($daterange[0]));
            $date_end = date('Y-m-d',strtotime($daterange[1]));
            $account_type = $query['account_type'];
            $payment_mode = $query['payment_mode'];
        }
        else
        {
            $min_date = DB::table('sales')->min('transaction_date');
            $max_date = DB::table('sales')->max('transaction_date');
            $date_start = date('Y-m-d',strtotime($min_date));
            $date_end = date('Y-m-d',strtotime($max_date));
            $account_type = 'Any';
            $payment_mode = 'Any';
        }
        
            
        $headers = array(
        'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Content-Disposition' => 'attachment; filename=abc.csv',
        'Expires' => '0',
        'Pragma' => 'public',
        );

        $filename = "Sales_Report.csv";
        $handle = fopen($filename, 'w');
        fputcsv($handle, [
            "Date",
            "Customer Name",
            "Account Type",
            "Discount",
            'Total',
            'Mode of Payment'
        ]);

        if($account_type == 'Any' && $payment_mode != 'Any')
        {
            $sales = Sale::where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->where('payment_mode', $payment_mode)->orderBy('transaction_date', 'desc')->chunk(100, function ($data) use ($handle) {
            

            foreach ($data as $sale) {
                if($sale->member_id == 0)
                {
                    $name = $sale->guest->customer_name;
                    $account_type = "Walk-in";
                }
                else
                {
                    $name = $sale->user->firstname . " " . $sale->user->lastname;
                    $account_type =  ucfirst($sale->user->role);
                }

                if(isset($sale->discount->discount_name))
                {
                    $discount = $sale->discount->discount_name;
                }
                else
                {
                    $discount = 'None';
                }

                fputcsv($handle, [
                    date('F d, Y', strtotime($sale->transaction_date)),
                    $name,
                    $account_type,
                    $discount,
                    $sale->amount_due,
                    ucwords($sale->payment_mode)
                ]);
            }
        });
        }
        else if($account_type == 'Member' && $payment_mode == 'Any')
        {
            $sales = Sale::with('user')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
            {
                $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
            })->whereHas('User', function($query) use ($account_type)
                {
                        $query->where('role', $account_type);
                })->orderBy('transaction_date', 'desc')->chunk(100, function ($data) use ($handle) {
            

            foreach ($data as $sale) {
                if($sale->member_id == 0)
                {
                    $name = $sale->guest->customer_name;
                    $account_type = "Walk-in";
                }
                else
                {
                    $name = $sale->user->firstname . " " . $sale->user->lastname;
                    $account_type =  ucfirst($sale->user->role);
                }

                if(isset($sale->discount->discount_name))
                {
                    $discount = $sale->discount->discount_name;
                }
                else
                {
                    $discount = 'None';
                }

                fputcsv($handle, [
                    date('F d, Y', strtotime($sale->transaction_date)),
                    $name,
                    $account_type,
                    $discount,
                    $sale->amount_due,
                    ucwords($sale->payment_mode)
                ]);
            }
        });
        }
        else if($account_type == 'Walk-in' && $payment_mode == 'Cash')
        {
            $sales = Sale::with('guest')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
            {
                $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
            })->where('member_id', 0)->orderBy('transaction_date', 'desc')->chunk(100, function ($data) use ($handle) {
            

            foreach ($data as $sale) {
                if($sale->member_id == 0)
                {
                    $name = $sale->guest->customer_name;
                    $account_type = "Walk-in";
                }
                else
                {
                    $name = $sale->user->firstname . " " . $sale->user->lastname;
                    $account_type =  ucfirst($sale->user->role);
                }

                if(isset($sale->discount->discount_name))
                {
                    $discount = $sale->discount->discount_name;
                }
                else
                {
                    $discount = 'None';
                }

                fputcsv($handle, [
                    date('F d, Y', strtotime($sale->transaction_date)),
                    $name,
                    $account_type,
                    $discount,
                    $sale->amount_due,
                    ucwords($sale->payment_mode)
                ]);
            }
        });
        }
        else if($account_type == 'Member' && $payment_mode != 'Any')
        {
            $sales = Sale::with('user')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->whereHas('User', function($query) use ($account_type)
                {
                        $query->where('role', $account_type);
                })->where('payment_mode', $payment_mode)->orderBy('transaction_date', 'desc')->chunk(100, function ($data) use ($handle) {
            

            foreach ($data as $sale) {
                if($sale->member_id == 0)
                {
                    $name = $sale->guest->customer_name;
                    $account_type = "Walk-in";
                }
                else
                {
                    $name = $sale->user->firstname . " " . $sale->user->lastname;
                    $account_type =  ucfirst($sale->user->role);
                }

                if(isset($sale->discount->discount_name))
                {
                    $discount = $sale->discount->discount_name;
                }
                else
                {
                    $discount = 'None';
                }

                fputcsv($handle, [
                    date('F d, Y', strtotime($sale->transaction_date)),
                    $name,
                    $account_type,
                    $discount,
                    $sale->amount_due,
                    ucwords($sale->payment_mode)
                ]);
            }
        });
        }
        else 
        {
            $sales = Sale::with('user')->where(function($query) use ($request, $account_type, $payment_mode, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(transaction_date,'%Y-%m-%d'))"), '<=', $date_end);
                })->orderBy('transaction_date', 'desc')->chunk(100, function ($data) use ($handle) {
            

            foreach ($data as $sale) {
                if($sale->member_id == 0)
                {
                    $name = $sale->guest->customer_name;
                    $account_type = "Walk-in";
                }
                else
                {
                    $name = $sale->user->firstname . " " . $sale->user->lastname;
                    $account_type =  ucfirst($sale->user->role);
                }

                if(isset($sale->discount->discount_name))
                {
                    $discount = $sale->discount->discount_name;
                }
                else
                {
                    $discount = 'None';
                }

                fputcsv($handle, [
                    date('F d, Y', strtotime($sale->transaction_date)),
                    $name,
                    $account_type,
                    $discount,
                    $sale->amount_due,
                    ucwords($sale->payment_mode)
                ]);
            }
        });
        }

        fclose($handle);

        return Response::download($filename, "Sales_Report.csv", $headers);
    }
}
