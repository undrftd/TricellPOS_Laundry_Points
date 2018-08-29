<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Product;
use Response;
use Validator;
use DB;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ServicesController extends Controller
{
    
    public function index()
    {
    	$products = Product::where('product_name', 'LIKE', 'Washer%')->orderBy('product_id', 'asc')->paginate(7);
    	return view('admin.washers')->with('products', $products);
    }

    public function dryer()
    {
        $products = Product::where('product_name', 'LIKE', 'Dryer%')->orderBy('product_id', 'asc')->paginate(7);
        return view('admin.dryers')->with('products', $products);
    }

    public function product()
    {
        $products = Product::where('product_id', '>', '24')->orderBy('product_id', 'asc')->paginate(7);
        return view('admin.products')->with('products', $products);  
    }

    public function create(Request $request)
    {
        $rules = array(
        'product_name' => 'required|unique:products,product_name',
        'product_qty' => 'required|integer',
        'price' => 'required|numeric',
        'member_price' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $product = new Product;
            $product->product_name = $request->product_name;
            $product->product_qty = $request->product_qty;
            $product->price = $request->price;
            $product->member_price = $request->member_price;
            $product->save();
        }
    }

    public function edit(Request $request)
    {
    	$product = Product::find($request->product_id);

        $rules = array(
        'product_name' => "required|unique:products,product_name,$product->product_id,product_id",
        'product_qty' => 'required|integer',
        'price' => 'required|numeric',
        'member_price' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $product = Product::find($request->product_id);
            $product->product_name = $request->product_name;
            $product->product_qty = $request->product_qty;
            $product->price = $request->price;
            $product->member_price = $request->member_price;
            $product->save();
        }	
    }

    public function destroy(Request $request)
    {
        $product = Product::find($request->product_id)->delete();
    }

    public function search_washer(Request $request)
    {
        $search = $request->search;

        if($search == "")
        {
            return Redirect::to('services/washers');
        }
        else
        {
            $products = Product::where('product_id', '<=', '12')->where('product_name', 'LIKE', '%' . $search . '%')->paginate(7);

            $products->appends($request->only('search'));
            $count = $products->count();
            $totalcount = Product::where('product_id', '<=', '12')->where('product_name', 'LIKE', '%' . $search . '%')->count();
            return view('admin.washers')->with(['products' => $products, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]);
        }
    }

    public function search_dryer(Request $request)
    {
        $search = $request->search;

        if($search == "")
        {
            return Redirect::to('services/dryers');
        }
        else
        {
            $products = Product::where('product_id', '<=', '24')->where('product_id', '>=', '13')->where('product_name', 'LIKE', '%' . $search . '%')->paginate(7);

            $products->appends($request->only('search'));
            $count = $products->count();
            $totalcount = Product::where('product_id', '<=', '24')->where('product_id', '>=', '13')->where('product_name', 'LIKE', '%' . $search . '%')->count();
            return view('admin.dryers')->with(['products' => $products, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]);
        }
    }

    public function search_product(Request $request)
    {
        $search = $request->search;

        if($search == "")
        {
            return Redirect::to('services/products');
        }
        else
        {
            $products = Product::where('product_id', '>', '24')->where('product_name', 'LIKE', '%' . $search . '%')->paginate(7);

            $products->appends($request->only('search'));
            $count = $products->count();
            $totalcount = Product::where('product_id', '>', '24')->where('product_name', 'LIKE', '%' . $search . '%')->count();
            return view('admin.products')->with(['products' => $products, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]);
        }
    }
}
