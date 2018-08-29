<?php
    
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Timesheet;
use DB;
use Hash;
use Response;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class StaffAccountsController extends Controller
{
    public function index()
    {
        $staffs = User::where('role', 'staff')->orderBy('id', 'desc')->paginate(7);
        return view('admin.staff')->with('staffs', $staffs);  
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
            $staff = new User;
            $staff->card_number = $request->card_number;
            $staff->username = $request->username;
            $staff->password = Hash::make($request->password);
            $staff->firstname = $request->firstname;
            $staff->lastname = $request->lastname;
            $staff->address = $request->address;
            $staff->contact_number = $request->contact;
            $staff->email = $request->email;
            $staff->role = 'staff';
            $staff->save();
        }
    }

    public function edit(Request $request)
    {
        $staff = User::find($request->staff_id);

        $rules = array(
        'card_number' => "bail|required|numeric|digits:10|unique:users,card_number,$staff->id",
        'username' => "bail|required|min:5|unique:users,username,$staff->id",
        'password' => 'bail|required|min:8|confirmed',
        'password_confirmation' => 'required',
        'firstname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'lastname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'address' => 'bail|required|regex:/^[#.0-9a-zA-Z\s,-]+$/|min:6',
        'contact' => 'bail|required|digits_between:7,11',
        'email' => "bail|required|email|unique:users,email,$staff->id",
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $staff = User::find($request->staff_id);
            $staff->card_number = $request->card_number;
            $staff->username = $request->username;
            $staff->password = Hash::make($request->password);
            $staff->firstname = $request->firstname;
            $staff->lastname = $request->lastname;
            $staff->address = $request->address;
            $staff->contact_number = $request->contact;
            $staff->email = $request->email;
            $staff->save();
        }
    }

    public function destroy(Request $request)
    {
        $staff = User::find($request->staff_id)->delete();
        $timesheet = Timesheet::where('user_id', $request->staff_id)->delete();
    }

    public function search(Request $request)
    {
        $search = $request->search;

        if($search == "")
        {
            return Redirect::to('accounts/staff');
        }
        else
        {
            $staffs = User::where('role', 'staff')->where(function($query) use ($request, $search)
                {
                    $query->where('card_number', 'LIKE', '%' . $search . '%')->orWhere('username', 'LIKE', '%' . $search . '%')->orWhere('firstname', 'LIKE', '%' . $search . '%')->orwhere('lastname', 'LIKE', '%' . $search . '%')->orWhere(DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%' . $search . '%');
                })->paginate(7);

            $staffs->appends($request->only('search'));
            $count = $staffs->count();
            $totalcount = User::where('role', 'staff')->where(function($query) use ($request, $search)
                {
                    $query->where('card_number', 'LIKE', '%' . $search . '%')->orWhere('username', 'LIKE', '%' . $search . '%')->orWhere('firstname', 'LIKE', '%' . $search . '%')->orwhere('lastname', 'LIKE', '%' . $search . '%')->orWhere(DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%' . $search . '%');
                })->count();

            return view('admin.staff')->with(['staffs' => $staffs, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]);  
        }
    }
}
