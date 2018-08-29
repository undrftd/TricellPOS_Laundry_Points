<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Response;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function index() 
    {   
        $profile = DB::table('profile')->select('*')->where('id', 1)->first();
        return view('admin.profile')->with('profile', $profile); 
    }

    public function edit(Request $request)
    { 
        $rules = array(
        'branch_name' => 'bail|required|min:2',
        'address' => 'bail|required|regex:/^[#.0-9a-zA-Z\s,-]+$/|min:6',
        'contact_number' => 'bail|required|digits_between:7,11',
        'email' => 'bail|required|email',
        'tin' => 'required',
        'vat' => 'required|numeric',
        'washertimer' => 'required|integer',
        'dryertimer' => 'required|integer',
        'lowstock' => 'required|integer'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $profile = DB::table('profile')->where('id',1)->update([
            'branch_name' => $request->branch_name,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'tin' => $request->tin,
            'vat' => $request->vat,
            'washer_timer' => $request->washertimer,
            'dryer_timer' => $request->dryertimer,
            'low_stock' => $request->lowstock
            ]);
        }
    }
}
