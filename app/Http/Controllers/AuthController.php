<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
   public function register(){
      return view('auth.register');
   }

   public function registerPost(Request $request){
      $request->validate([
         "name" => "required",
         "email" => "required|email",
         "contact" => "required|numeric",
         "password" => "required|confirmed|min:6"
      ]);

      $user = User::create([
         "name" => $request->name,
         "email" => $request->email,
         "contact" =>$request->contact,
         "password" => Hash::make($request->password)
      ]);
      auth()->login($user);

      return redirect()->route("dashboard")->with("success", "User registered successfully.");

   }

   public function dashboard(Request $request){
      return view("dashboard");
   }

   public function logout(Request $request){
      auth()->logout();
      return redirect()->route("register");
   }

   public function login(Request $request){
      return view("auth.login");
   }

   public function loginPost(Request $request){
       $request->validate([
         "email" => "required|email",
         "password" => "required"
      ]);

      if(auth()->attempt(["email"=> $request->email, "password"=> $request->password])){
         return redirect()->route("dashboard")->with("success", "User logged in successfully.");

      }
      return back()->with("error", "Username or password incorrect.");

   }
}
