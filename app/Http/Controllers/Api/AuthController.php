<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $error_message = [
        'name.required'=>'Name is required',
        'name.max' => 'Name cannot be more than 50 characters',
        'email.required' => 'Email is required!',
        'email.email' => 'Email is not valid!',
        'password.min' => 'Password must be atleast 5 characters!',
        ];

        $validators =  Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:5|max:25|confirmed',
        ], $error_message);

        if($validators->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validators->getMessageBag()->first()
            ], 200);
        } elseif (!empty(User::where('email',$request->email)->first())){
            return response()->json([
                'status'=>422,
                'message'=>"Email is already exists."
            ], 200);
        } else {
            $user = new User([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->name),
            ]);
            $user->save();
        }

        return response()->json([
            'status'=>200,
            'message'=>'User successfully created'
        ], 200);

    }
}
