<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Hash;
use Response;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    public function index() 
    {   
        return view('staff.account');
    }

    public function edit(Request $request)
    { 
        $staff = User::find(Auth::user()->id);

        $rules = array(
        'firstname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'lastname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'address' => 'bail|required|regex:/^[#.0-9a-zA-Z\s,-]+$/|min:6',
        'contact_number' => 'bail|required|digits_between:7,11',
        'email' => 'bail|required|email',
        'username' => "bail|required|min:5|unique:users,card_number,$staff->id",
        'password' => 'bail|required|min:8|confirmed',
        'password_confirmation' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $staff = User::find(Auth::user()->id);
            $staff->username = $request->username;
            $staff->password = Hash::make($request->password);
            $staff->firstname = $request->firstname;
            $staff->lastname = $request->lastname;
            $staff->address = $request->address;
            $staff->contact_number = $request->contact;
            $staff->email = $request->email;
            $staff->contact_number = $request->contact_number;
            $staff->save();
        }
    }
}
