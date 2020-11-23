<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JobsController extends Controller
{

    function __construct()
    {
        $this->middleware(['auth:api'], ['except' => ['all']]);
    }

    public function all(Request $request) : JsonResponse {
        return response()->json(Job::all()->toArray());
    }
    
    public function index() 
    {
        return view('jobs')->with('jobs', Job::all());
    }

    public function doJob(Request $request) : JsonResponse {
        $id = $request->job_id ?? false;
        
        if($id === false || !($job = Job::find($id))) {
            return response()->json([
                "message" => "Job by id '$id' was not found",
                "success" => false
                ], 200);
        }
        # BIG TODO
        // Send appropriate error message
        if(!auth()->user()->canDoJob($job)) {
            if($job->energy > auth()->user()->getStat('energy')->value) {
                return response()->json([
                    "message" => "User has insufficient energy level",
                    "success" => false
                ]);
            }
            return response()->json([
                "message" => "Requirements for this job was not met",
                "success" => false
                ]);
        }
        // Execute job
        $m = mt_rand($job->rm_min, $job->rm_max);

        auth()->user()->getStat('money')->value += $m;
        auth()->user()->getStat('energy')->value -= $job->energy;
        auth()->user()->getStat('xp')->value += $job->xp;
        auth()->user()->push();


        return response()->json([
            "message" => "Job accomplished",
            "success" => true,
            "rewards" => [
                "money" => $m,
                "xp"    => $job->xp,
            ],
            "spent"   => [
                "energy" => $job->energy
            ]
        ]);
    }

}
