<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;

class JobsController extends Controller
{
    
    public function index() 
    {
        return view('jobs')->with('jobs', Job::all());
    }

    public function doJob() {
        $id = request()->post()["id"] ?? false;
        if($id === false || !($job = Job::find($id))) {
            return ["message" => "Notikusi kļūda: šis darbs neeksistē", "error" => true];
        }
        # BIG TODO
        // Send appropriate error message
        if(!\Auth::user()->canDoJob($job)) {
            if($job->energy > \Auth::user()->getStat('energy')->value) {
                return ["message" => "Jums nepietiek enerģijas", "error" => true];
            }
            return ["message" => "Nav izpildītas prasības", "error" => true];
        }
        // Execute job
        $m = mt_rand($job->rm_min, $job->rm_max);

        \Auth::user()->getStat('money')->value += $m;
        \Auth::user()->getStat('energy')->value -= $job->energy;
        \Auth::user()->getStat('xp')->value += $job->xp;
        \Auth::user()->push();


        return [
            "message" => "Darbs izpildīts (+\${$m})"
        ];
    }

}
