<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.account');
    }

    public function edit(Request $request)
    { 
        $admin = User::find(Auth::user()->id);

        $rules = array(
        'firstname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'lastname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'address' => 'bail|required|regex:/^[#.0-9a-zA-Z\s,-]+$/|min:6',
        'contact_number' => 'bail|required|digits_between:7,11',
        'email' => 'bail|required|email',
        'username' => "bail|required|min:5|unique:users,card_number,$admin->id",
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
            $admin = User::find(Auth::user()->id);
            $admin->username = $request->username;
            $admin->password = Hash::make($request->password);
            $admin->firstname = $request->firstname;
            $admin->lastname = $request->lastname;
            $admin->address = $request->address;
            $admin->contact_number = $request->contact;
            $admin->email = $request->email;
            $admin->contact_number = $request->contact_number;
            $admin->save();
        }
    }
}
