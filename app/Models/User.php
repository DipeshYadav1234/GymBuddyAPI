<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'dob',
        'weight',
        'height',
        'password',
        'is_profile_complete'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function messageProbablity($message, $recognizedWords, $singleResponse = False, $requiredWords = [])
    {
        $messageCertainty = 0;
        $hasRequiredWords = 0;
        foreach ($message as $word) {
            if (in_array($word, $recognizedWords)) {
                $messageCertainty += 1;
            }
        }
        $percentage = (float)$messageCertainty / (float)count($recognizedWords);
        foreach ($requiredWords as $word) {
            if (in_array($word, $message)) {
                $hasRequiredWords += 1;
            }
        }
        $hasWordsPercentage = 0;
        if(count($requiredWords) > 0) {
            $hasWordsPercentage = (float)$hasRequiredWords / (float)count($requiredWords);
            $hasWordsPercentage = $hasWordsPercentage * 100;
        }
        if ($hasWordsPercentage > 50 || $singleResponse) {
            return max($percentage * 100, $hasWordsPercentage);
        } else {
            return 0;
        }

    }

    public static function checkAllMessages($message)
    {
        $highestProbList = [];
        $q1 = 'This really depends on your preference. Ask yourself, how many days can you, and would like to, realistically and sustainably exercise.';
        $q2 = 'Your current fitness levels, the type of training and the intensity all play a part in how long to exercise for.';
        $q3 = 'The goal here is to slowly warm up the body to mobilise the muscle groups and joints you’ll be using during your workout. This can be done through some light movement, such as walking, skipping or a gentle jog.';
        $q4 = 'Whenever works best for you. The time of day you feel most energised.';
        $q5 = 'Our lives nowadays are largely sedentary due to how we work. Our bodies are designed to move and be used, this keeps them working well for longer. Exercise can increase our quality of life.';
        $q6 = 'A full-body workout routine focusing on compound exercises like squats, deadlifts, and bench presses is ideal for beginners. Start with lighter weights and gradually increase as you build strength';
        $q7 = 'Each machine usually has instructions or diagrams nearby, but if you\'re unsure, don\'t hesitate to ask a gym staff member for assistance. They can demonstrate proper form and usage';
        $q8 = 'Before or after a workout, opt for a balanced meal containing carbohydrates and protein, like a banana with peanut butter or Greek yogurt with fruit. Afterward, focus on replenishing your energy stores with a mix of protein and carbs, such as a chicken and vegetable stir-fry with brown rice.';
        $q9 = 'Start with light weights to master proper form before progressing to heavier loads. Listen to your body and don\'t push through pain. Warm up before each workout and stretch afterward to prevent injuries';
        $q10 = 'Compound exercises like squats, deadlifts, bench presses, and rows are great for building muscle mass. Incorporate them into your routine along with isolation exercises like bicep curls and tricep extensions.';
        $q11 = 'While supplements can enhance your progress, they\'re not essential for beginners. Focus on getting nutrients from whole foods first. If you\'re considering supplements, a basic protein powder and creatine monohydrate are good starting points.';
        $q12 = 'For beginners, aiming for 3-4 workouts per week is a good starting point. Make sure to include rest days in your schedule to allow your muscles to recover and grow.';
        $q13 = 'Cardio exercises like running, cycling, and swimming primarily focus on improving cardiovascular health and burning calories, while strength training involves lifting weights to build muscle strength and size.';
        $q14 = 'Set realistic goals, track your progress, and find activities you enjoy. Consider working out with a friend or hiring a personal trainer for added accountability and support.';
        $q15 = 'Classes like yoga, Pilates, or group fitness sessions can be great for beginners as they offer guidance and a sense of community. Experiment with different classes to see what you enjoy the most.';

        $highestProbList['Hello!'] = self::messageProbablity($message, ['hello', 'hi', 'hey', 'sup', 'heyo'], True);
        $highestProbList['See you!'] = self::messageProbablity($message, ['bye', 'goodbye'], True);
        $highestProbList['I\'m doing fine, and you?'] = self::messageProbablity($message, ['how', 'are', 'you', 'doing'], False, ['how', 'you']);
        $highestProbList['You\'re welcome!'] = self::messageProbablity($message, ['thank', 'thanks'], True);
        $highestProbList['Thank you!'] = self::messageProbablity($message, ['i', 'love', 'code', 'palace'], False, ['code', 'palace']);
        $highestProbList['I am your fitness Instructor'] = self::messageProbablity($message, ['name', 'what', 'your', 'yourself'], True, ['name', 'what']);
        $highestProbList[$q1] = self::messageProbablity($message, ['how', 'many', 'week', 'exercise'], False, ['how', 'exercise', 'week', 'times']);
        $highestProbList[$q2] = self::messageProbablity($message, ['how', 'long', 'work', 'out', 'exercise'], False, ['how', 'long', 'need', 'work', 'out']);
        $highestProbList[$q3] = self::messageProbablity($message, ['how', 'warm-up', 'do', 'exercise', 'cool-down'], False, ['how','warm-up', 'cool-down', 'exercise']);
        $highestProbList[$q4] = self::messageProbablity($message, ['what', 'time', 'exercise'], False, ['what', 'exercise', 'time']);
        $highestProbList[$q5] = self::messageProbablity($message, ['why', 'exercise', 'fitness','work','out','important'], False, ['why', 'fitness', 'important']);
        $highestProbList[$q6] = self::messageProbablity($message, ['what', 'best', 'routine','work','out','begginers'], False, ['what', 'workout', 'routine']);
        $highestProbList[$q7] = self::messageProbablity($message, ['how', 'do', 'use','machine','properly'], False, ['how', 'machine', 'properly']);
        $highestProbList[$q8] = self::messageProbablity($message, ['what', 'should', 'eat','before','after', 'workout'], False, ['what', 'eat', 'workout']);
        $highestProbList[$q9] = self::messageProbablity($message, ['how', 'avoid', 'injuring','while','exercising'], False, ['how', 'avoid', 'injuring']);
        $highestProbList[$q10] = self::messageProbablity($message, ['what', 'some', 'effective','exersice','building', 'muscle'], False, ['what','effective', 'exercise', 'building', 'muscle']);
        $highestProbList[$q11] = self::messageProbablity($message, ['can', 'you', 'recommend','supplements','beginners'], False, ['can', 'recommend', 'supplements']);
        $highestProbList[$q12] = self::messageProbablity($message, ['how', 'often', 'should','workout','week'], False, ['how', 'often', 'workout', 'week']);
        $highestProbList[$q13] = self::messageProbablity($message, ['what', 'difference', 'between','cardio','strength', 'training'], False, ['what', 'difference', 'between', 'cardio', 'strength', 'training']);
        $highestProbList[$q14] = self::messageProbablity($message, ['how', 'can', 'stay','motivated'], False, ['how', 'stay', 'motivated']);
        $highestProbList[$q15] = self::messageProbablity($message, ['are', 'classes', 'group', 'workouts','try'], False, ['are', 'classes', 'group', 'workouts']);
        



        return $highestProbList;
    }
}
