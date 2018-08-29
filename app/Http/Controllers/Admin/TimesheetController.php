<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use App\Timesheet;
use App\Product;
use Response;
use Validator;
use DB;
use Auth;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class TimesheetController extends Controller
{
    
    public function index()
    {
        $employees = Timesheet::orderBy('id', 'desc')->paginate(7);
        // $now = Carbon::now()->format('Y-m-d');
        // ->where(DB::raw("(DATE_FORMAT(time_in,'%Y-%m-%d'))"), '=', $now)
        $time_in = Timesheet::where('user_id', Auth::user()->id)->whereNotNull('time_in')->whereNull('time_out')->get();
        $time_out = Timesheet::where('user_id', Auth::user()->id)->whereNull('time_out')->get();

        return view('admin.timesheet')->with(['employees' => $employees, 'time_in' => $time_in, 'time_out' => $time_out]); 
    }

    public function time_in(Request $request)
    {
    	if($request->id == Auth::user()->card_number)
    	{
	        $timesheet = new Timesheet;
	        $timesheet->user_id = Auth::user()->id;
	        $timesheet->time_in = Carbon::now();
	        $timesheet->save();	
	    }
	    else
	    {
	    	$error = 'The ID you have entered doesn\'t match with your account.';
	    	return Response::json(array('error' => $error));
	    }
    }

    public function time_out()
    {
    	if (Auth::check()) 
        {
            $timesheet = Timesheet::where('user_id', Auth::user()->id)->whereNull('time_out')->first();
            $timesheet->time_out = Carbon::now();
            $timesheet->save();
        }
    }

    public function filter(\Illuminate\Http\Request $request)
    {
        $daterange = array_map('trim', explode('-', $request->date_filter));
        
        if(count($daterange) <= 1)
        {
            return Redirect::to('timesheet');
        }
        else
        {
            $now = Carbon::now()->format('Y-m-d');
		    $time_in = Timesheet::where('user_id', Auth::user()->id)->where(DB::raw("(DATE_FORMAT(time_in,'%Y-%m-%d'))"), '=', $now)->whereNotNull('time_in')->whereNull('time_out')->get();
		    $time_out = Timesheet::where('user_id', Auth::user()->id)->whereNull('time_out')->get();

            $date_start = date('Y-m-d',strtotime($daterange[0]));
            $date_end = date('Y-m-d',strtotime($daterange[1]));
            $employees = Timesheet::where(function($query) use ($request, $date_start, $date_end)
                {
                    $query->where(DB::raw("(DATE_FORMAT(time_in,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(time_in,'%Y-%m-%d'))"), '<=', $date_end);
                })->orderBy('id', 'desc')->paginate(7);
            $employees->appends($request->only('date_filter'));
               
            $count = $employees->count();
            $totalcount = Timesheet::where(function($query) use ($request, $date_start, $date_end)
                    {
                        $query->where(DB::raw("(DATE_FORMAT(time_in,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(time_in,'%Y-%m-%d'))"), '<=', $date_end);
                    })->count();
            return view('admin.timesheet')->with(['employees' => $employees,'count' => $count, 'date_start' => $date_start, 'date_end' => $date_end,'totalcount' => $totalcount, 'time_in' => $time_in, 'time_out' => $time_out]);  
        }
    }

    public function export(Request $request)
    {
    	$url = url()->previous();

    	$parts = parse_url($url, PHP_URL_QUERY);
		parse_str($parts, $query);

		if(isset($query['date_filter']))
		{
			$daterange = array_map('trim', explode('-', $query['date_filter']));
	        $date_start = date('Y-m-d',strtotime($daterange[0]));
	        $date_end = date('Y-m-d',strtotime($daterange[1]));
		}
		else
		{
	        $min_date = DB::table('timesheet')->min('time_in');
	        $max_date = DB::table('timesheet')->max('time_in');
	        $date_start = date('Y-m-d',strtotime($min_date));
	        $date_end = date('Y-m-d',strtotime($max_date));
		}
    	$headers = array(
        'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Content-Disposition' => 'attachment; filename=abc.csv',
        'Expires' => '0',
        'Pragma' => 'public',
    	);

		$filename = "Timesheet_Report.csv";
		$handle = fopen($filename, 'w');
		fputcsv($handle, [
			"Date",
		    "ID Number",
		    "Username",
		    "Employee Name",
		    'Time In',
		    'Time Out'
		]);


		Timesheet::orderBy('id', 'desc')->where(DB::raw("(DATE_FORMAT(time_in,'%Y-%m-%d'))"), '>=', $date_start)->where(DB::raw("(DATE_FORMAT(time_in,'%Y-%m-%d'))"), '<=', $date_end)->chunk(100, function ($data) use ($handle) {
		    foreach ($data as $employee) {
				if(empty($employee->time_out))
	            {
	            	$employee->time_out = '';
	            }
	            else
	            {
	            	$employee->time_out = date('h:i:s A', strtotime($employee->time_out));
	            }

		        // Add a new employee with data
		        fputcsv($handle, [
		        	date('F d, Y', strtotime($employee->time_in)),
		            $employee->user->card_number,
		            $employee->user->username,
		            $employee->user->firstname . " " . $employee->user->lastname,
		            date('h:i:s A', strtotime($employee->time_in)),
		            $employee->time_out
		        ]);
		    }
		});

		fclose($handle);

		return Response::download($filename, "Timesheet_Report.csv", $headers);
	}
}   