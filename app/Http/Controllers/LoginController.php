<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Auth;    
class LoginController extends Controller
{   

    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'shutdown']);
    }

    public function index()
    {
        return view('login');
    }    

    public function verify(Request $request)
    {
        $credentials = $request->only('username', 'password');    
        if (Auth::attempt($credentials)) 
        {
            if(Auth::user()->role == 'admin')
            {
                return redirect('/dashboard');
            }
            else
            {
                return redirect('/staff/sales');  
            }
        }
        else
        {
            $request->session()->flash('message', 'The username and password you entered did not match our records. Please double-check and try again.');
            return redirect('/');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function shutdown()
    {   
        Auth::logout();
        //python script
        passthru("sudo python /var/www/html/shutdown.py");
    }  
}
