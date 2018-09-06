<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use App\Balance;
use App\Reload_sale;
use Hash;
use Response;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class MemberAccountsController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'member')->orderBy('id', 'desc')->paginate(7);
        return view('admin.member')->with('members', $members);
    }

    public function create(Request $request)
    {
        $rules = array(
        'card_number' => 'bail|required|numeric|digits:10|unique:users,card_number',
        'firstname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'lastname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'address' => 'bail|required|regex:/^[#.0-9a-zA-Z\s,-]+$/|min:6',
        'contact' => 'bail|required|digits_between:7,11',
        'email' => 'bail|required|email|unique:users,email',
        'initial_amount' => 'bail|required|numeric',
        'payment_amount' => 'bail|required|numeric|greater_than_equal:initial_amount'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $member = new User;
            $member->card_number = $request->card_number;
            $member->firstname = $request->firstname;
            $member->lastname = $request->lastname;
            $member->address = $request->address;
            $member->contact_number = $request->contact;
            $member->email = $request->email;
            $member->role = 'member';
            $member->save();

            $member_load = new Balance;
            $member_load->id = $member->id;
            $member_load->load_balance = $request->initial_amount; 
            $member_load->save();

            $member_reload = new Reload_sale;
            $member_reload->member_id = $member->id;
            $member_reload->amount_due = $request->initial_amount;
            $member_reload->amount_paid = $request->payment_amount;
            $member_reload->change_amount = $request->change_amount;
            $member_reload->save();
        }
    }

    public function edit(Request $request)
    {
        $member = User::find($request->member_id);

        $rules = array(
        'card_number' => "bail|required|numeric|digits:10|unique:users,card_number,$member->id",
        'firstname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'lastname' => 'bail|required|regex:/^[\pL\s\-]+$/u|min:2',
        'address' => 'bail|required|regex:/^[#.0-9a-zA-Z\s,-]+$/|min:6',
        'contact' => 'bail|required|digits_between:7,11',
        'email' => "bail|required|email|unique:users,email,$member->id",
        'points' => 'bail|required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $member = User::find($request->member_id);
            $member->card_number = $request->card_number;
            $member->firstname = $request->firstname;
            $member->lastname = $request->lastname;
            $member->address = $request->address;
            $member->contact_number = $request->contact;
            $member->email = $request->email;
            $member->save();

            $member->balance->points_balance = $request->points;
            $member->balance->save();
        }
    }

    public function reload(Request $request)
    {
        $member = User::find($request->member_id);

        $total_load = $request->reload_amount + $request->current_load;
        $member->balance->load_balance = $total_load;
        $member->balance->save();

        $member_reload = new Reload_sale;
        $member_reload->member_id = $request->member_id;
        $member_reload->amount_due = $request->reload_amount;
        $member_reload->amount_paid = $request->payment_amount;
        $member_reload->change_amount = $request->change_amount;
        $member_reload->staff_name = Auth::user()->id;
        $member_reload->save();
    }

    public function destroy(Request $request)
    {
        $member = User::find($request->member_id)->delete();
        $balance = Balance::where('id', $request->member_id)->delete();
        $reload = Reload_sale::where('member_id', $request->member_id)->delete();
    }

    public function search(Request $request)
    {
        $search = $request->search;

        if($search == "")
        {
            return Redirect::to('accounts/members');
        }
        else
        {
            $members = User::where('role', 'member')->where(function($query) use ($request, $search)
                {
                    $query->where('card_number', 'LIKE', '%' . $search . '%')->orWhere('firstname', 'LIKE', '%' . $search . '%')->orwhere('lastname', 'LIKE', '%' . $search . '%')->orWhere(DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%' . $search . '%');
                })->paginate(7);

            $members->appends($request->only('search'));
            $count = $members->count();
            $totalcount = User::where('role', 'member')->where(function($query) use ($request, $search)
                {
                    $query->where('card_number', 'LIKE', '%' . $search . '%')->orWhere('firstname', 'LIKE', '%' . $search . '%')->orwhere('lastname', 'LIKE', '%' . $search . '%')->orWhere(DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%' . $search . '%');
                })->count();
            return view('admin.member')->with(['members' => $members, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]);
        }
    }
}
