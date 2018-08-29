<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Discount;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;

class DiscountsController extends Controller
{
    public function index()
    {
    	$discounts = Discount::paginate(7);
        return view('staff.discounts')->with('discounts', $discounts); 
    }

    public function create(Request $request)
    {
    	$rules = array(
        'discount_name' => 'bail|required|required|min:2|unique:discounts,discount_name',
        'discount_type' => 'required',
        'discount_value' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $discount = new Discount;
            $discount->discount_name = $request->discount_name;
            $discount->discount_type = $request->discount_type;

            if($request->discount_type == 'percentage')
            {
            	$discount_value = $request->discount_value / 100;
            	$discount->discount_value = $discount_value;
            }
            else
            {
            	$discount->discount_value = $request->discount_value;
            }

            $discount->save();
        }
    }

    public function edit(Request $request)
    {
        $discount = Discount::find($request->discount_id);

        $rules = array(
        'discount_name' => "required|min:2|unique:discounts,discount_name,$discount->id",
        'discount_type' => 'required',
        'discount_value' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $discount = Discount::find($request->discount_id);
            $discount->discount_name = $request->discount_name;
            $discount->discount_type = $request->discount_type;

            if($request->discount_type == 'percentage')
            {
                $discount_value = $request->discount_value / 100;
                $discount->discount_value = $discount_value;
            }
            else
            {
                $discount->discount_value = $request->discount_value;
            }

            $discount->save();
        }
    }

    public function destroy(Request $request)
    {
        $discount = Discount::find($request->discount_id)->delete();
    }

    public function search(Request $request)
    {
        $search = $request->search;

        if($search == "")
        {
            return Redirect::to('preferences/discounts');
        }
        else
        {
        	$discounts = Discount::where(function($query) use ($request, $search)
                {
                    $query->where('discount_name', 'LIKE', '%' . $search . '%');
                })->paginate(7);

            $discounts->appends($request->only('search'));
            $count = $discounts->count();
            $totalcount = Discount::where(function($query) use ($request, $search)
                {
                    $query->where('discount_name', 'LIKE', '%' . $search . '%');
                })->count();
            return view('staff.discounts')->with(['discounts' => $discounts, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]);
        }
    }
}
