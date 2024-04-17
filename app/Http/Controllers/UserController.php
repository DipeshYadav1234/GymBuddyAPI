<?php

namespace App\Http\Controllers;


use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request){
        $data = $request->all();
        $user = User::where('email', $data['email'])->where('password', $data['password'])->first();
        if($user != null) {
            $date = new \DateTime($user->dob);
            $todayDate = now();
            $age = date_diff($todayDate,$date);
            $data = $user->toArray();
            $data['age'] = $age->y;
            return $data;
        }
        return ['success' => False];//['message' => 'Invalid user name or password'];
    }

    public function register(Request $request) {
        $data = $request->all();
        $user = User::create($data);
        if($user){
            return ['success' => True];
        }
        else {
            return ['success' => False];
        }
    }

    public function completeUserProfile(Request $request) {
        $data = $request->all();
        $user = User::where('email', $data['email'])->first();
        $user->update(['dob'=>$data['dob'], 'weight'=>$data['weight'], 'height'=>$data['height'], 'is_profile_complete' => True]);

        return ['success' => True];
    }

    public function getUserData(Request $request) {
        $data = $request->all();
        $user = User::where('email', $data['email'])->first();
        if($user != null) {
            $date = new \DateTime($user->dob);
            $todayDate = now();
            $age = date_diff($todayDate,$date);
            $data = $user->toArray();
            $data['age'] = $age->y;
            return $data;
        }
    }
}
