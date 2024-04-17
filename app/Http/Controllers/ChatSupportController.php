<?php

namespace App\Http\Controllers;


use App\Models\ProductContent;
use App\Models\User;
use Illuminate\Http\Request;

class ChatSupportController extends Controller
{

    public function giveResponse(Request $request)
    {
        $data = $request->all();
        $text = $data['text'];
        $pattern = '/\s+|[,;?!.-]\s*/';
        $msgArr = preg_split($pattern, strtolower($text));
        $calArr = ['proteins', 'calories', 'fats', 'carbs', 'protein', 'calorie', 'fat', 'carb', 'bmi'];
        $requiredArr = [];
        $vegArr = ProductContent::all()->pluck('name')->toArray();
        $requiredVegies = [];
        foreach ($msgArr as $msg) {
            if (in_array($msg, $calArr)) {
                $requiredArr[] = $msg;
            }
            if (in_array($msg, $vegArr)) {
                $requiredVegies[] = $msg;
            }
        }
        $calculateBMI = False;
        if (count($requiredVegies) > 0 || count($requiredArr) > 0) {
            $calVal = '';
            foreach ($requiredArr as $req) {
                if ($req == 'proteins' || $req == 'protein') {
                    $calVal = 'protein';
                } else if ($req == 'fats' || $req == 'fat') {
                    $calVal = 'fat';
                } else if ($req == 'carbs' || $req == 'carb') {
                    $calVal = 'carbs';
                } else if ($req == 'calories' || $req == 'calorie') {
                    $calVal = 'calories';
                }
                if ($req == 'bmi' || strpos(strtolower($text), 'body mass index')) {
                    $calculateBMI = True;
                }
            }
            $res = [];
            foreach ($requiredVegies as $vegie) {
                $ans = ProductContent::where('name', $vegie)->first();
                $res[] = 'There is ' . $ans->$calVal . ' ' . $calVal . ' in 1 gram of ' . $vegie . '.';
            }
            if ($calculateBMI) {
                $user = User::where('email', $data['email'])->first();
                $res[] = 'Body Mass Index (BMI) is a measurement of a person’s weight with respect to his or her height. It is more of an indicator than a direct measurement of a person’s total body fat.';
                if ($user->weight != Null && $user->height != Null) {
                    $weight = $user->weight;
                    $height = $user->height / 100;
                    $bmi = $weight / ($height * $height);
                    $res[] = 'Your BMI is: ' . round($bmi, 2) . '.';
                }
            }
            return ['message' => join(' ', $res)];
        } else {
            $ans = User::checkAllMessages($msgArr);
            $maxProb = max(array_values($ans));
            $res = '';
            foreach ($ans as $key => $val) {
                if ($val == $maxProb) {
                    $res = $key;
                }
            }
            if ($maxProb < 1) {
                $res = 'Sorry can\'t get that can you explain again ?';
            }
        }
        return ['message' => $res];
    }
}
