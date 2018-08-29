<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use DB;
use Hash;
use Response;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class AdminAccountsController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('id', 'desc')->paginate(7);
        return view('admin.admin')->with('admins', $admins);  
    }

    public function create(Request $request)
    {
        $rules = array(
        'card_number' => 'bail|required|numeric|digits:10|unique:users,card_number',
        'username' => 'bail|required|min:5|unique:users,username',
        'password' => 'bail|required|min:8|confirmed',
        'password_confirmation' => 'required',
        'firstname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'lastname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'address' => 'bail|required|regex:/^[#.0-9a-zA-Z\s,-]+$/|min:6',
        'contact' => 'bail|required|digits_between:7,11',
        'email' => 'bail|required|email',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $admin = new User;
            $admin->card_number = $request->card_number;
            $admin->username = $request->username;
            $admin->password = Hash::make($request->password);
            $admin->firstname = $request->firstname;
            $admin->lastname = $request->lastname;
            $admin->address = $request->address;
            $admin->contact_number = $request->contact;
            $admin->email = $request->email;
            $admin->role = 'admin';
            $admin->save();
        }
    }

    public function edit(Request $request)
    {
        $admin = User::find($request->admin_id);

        $rules = array(
        'card_number' => "bail|required|numeric|digits:10|unique:users,card_number,$admin->id",
        'username' => "bail|required|min:5|unique:users,username,$admin->id",
        'password' => 'bail|required|min:8|confirmed',
        'password_confirmation' => 'required',
        'firstname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'lastname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'address' => 'bail|required|regex:/^[#.0-9a-zA-Z\s,-]+$/|min:6',
        'contact' => 'bail|required|digits_between:7,11',
        'email' => "bail|required|email|unique:users,email,$admin->id",
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $admin = User::find($request->admin_id);
            $admin->card_number = $request->card_number;
            $admin->username = $request->username;
            $admin->password = Hash::make($request->password);
            $admin->firstname = $request->firstname;
            $admin->lastname = $request->lastname;
            $admin->address = $request->address;
            $admin->contact_number = $request->contact;
            $admin->email = $request->email;
            $admin->save();
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;

        if($search == "")
        {
            return Redirect::to('accounts/admin');
        }
        else
        {
            $admins = User::where('role', 'admin')->where(function($query) use ($request, $search)
                {
                    $query->where('card_number', 'LIKE', '%' . $search . '%')->orWhere('username', 'LIKE', '%' . $search . '%')->orWhere('firstname', 'LIKE', '%' . $search . '%')->orwhere('lastname', 'LIKE', '%' . $search . '%')->orWhere(DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%' . $search . '%');
                })->paginate(7);

            $admins->appends($request->only('search'));
            $count = $admins->count();
            $totalcount = User::where('role', 'admin')->where(function($query) use ($request, $search)
                {
                    $query->where('card_number', 'LIKE', '%' . $search . '%')->orWhere('username', 'LIKE', '%' . $search . '%')->orWhere('firstname', 'LIKE', '%' . $search . '%')->orwhere('lastname', 'LIKE', '%' . $search . '%')->orWhere(DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%' . $search . '%');
                })->count();
            return view('admin.admin')->with(['admins' => $admins, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]);  
        }
    }
}
