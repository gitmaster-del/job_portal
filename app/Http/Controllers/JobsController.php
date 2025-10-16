<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class JobsController extends Controller
{
   public function index(Request $request)
{
    $categories = Category::where('status', 1)->get();
    $jobTypes = JobType::where('status', 1)->get();
    $jobs = Job::where('status', 1);
    $jobTypeArray = [];

    if (!empty($request->keywords)) {
        $jobs = $jobs->where(function ($query) use ($request) {
            $query->orWhere('title', 'like', '%' . $request->keywords . '%')
                ->orWhere('keywords', 'like', '%' . $request->keywords . '%');
        });
    }

    if (!empty($request->location)) {
        $jobs = $jobs->where('location', $request->location);
    }

    if (!empty($request->category)) {
        $jobs = $jobs->where('category_id', $request->category);
    }

    if (!empty($request->job_type)) {
        $jobTypeArray = explode(',', $request->job_type);
        $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
    }

    if (!empty($request->experience)) {
        $jobs = $jobs->where('experience', $request->experience);
    }

    $jobs = $jobs->with(['jobType', 'category']);

    if ($request->has('sort') && $request->sort == 0) {
        $jobs = $jobs->orderBy('id', 'ASC'); 
    } else {
        $jobs = $jobs->orderBy('id', 'DESC'); 
    }

    $jobs = $jobs->paginate(9);

    return view('front.jobs', [
        'categories' => $categories,
        'jobTypes' => $jobTypes,
        'jobs' => $jobs,
        'jobTypeArray' => $jobTypeArray,
    ]);
}




// show job detail page
public function detail($id){



    $job = Job::where(['id'=> $id,'status' =>1 ])->with(['jobType','category'])->first();

    if($job==null){
        abort(404);
    }
    $count=0;
    if(Auth::user()){
         $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

    }


    // fetch applicant



    $applications = JobApplication::where('job_id', $id)->with('user')->get();

    
    return view('front.jobDetail',['job'=>$job,'count'=>$count,'applications'=>$applications]);
}






public function applyJob(Request $request){
$id = $request->id;
$job = Job::where('id',$id)->first();

// job in database exist or not
if($job == null){
    $message='Job does not exist';
    session()->flash('error',$message);
    return response()->json([
        'status' => false,
        'message' => $message

    ]);

}

// cannot apply on own job 
$employer_id= $job->user_id;
if($employer_id == Auth::user()->id){
    $message='You cannot apply on you own job'
;    session()->flash('error',$message);
    return response()->json([
        'status' => false,
        'message' => $message

    ]);
}

// you cannot apply on a job twice
$jobApplicationCount = JobApplication::where([
    'user_id'=>Auth::user()->id,
    'job_id'=>$id
])->count();

if($jobApplicationCount>0){
   $message='You already applied on this job';
session()->flash('error',$message);
    return response()->json([
        'status' => true,
        'message' => $message

    ]); 

}



$application = new JobApplication;
$application ->job_id =$id;
$application ->user_id =Auth::user()->id;
$application ->employer_id =Auth::user()->id;
$application->applied_date=now();
$application->save();


// send email to employer
$employer = User::where('id',$employer_id)->first();
$mailData =[
'employer' => $employer,
'user'=> Auth::user(),
'job'=> $job
];
Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

$message='You have successfully applied.';
session()->flash('success',$message);
    return response()->json([
        'status' => true,
        'message' => $message

    ]);

}



public function saveJob(Request $request){
    $id = $request->id;

    $job = Job::find($id);

    if($job == null){
        $message='Job not found';
        session()->flash('error',$message);
        return response()->json([
            'status' => false,
            'message' => $message
        ]);
        }

      // user already saved this job
       $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

       if($count > 0){
           $message='Job already saved';
           session()->flash('error',$message);
           return response()->json([
               'status' => false,
                'message' => $message
           ]);
       }

       $savedJob = new SavedJob;
       $savedJob->user_id = Auth::user()->id;
       $savedJob->job_id = $id;
       $savedJob->save();

       $message='Job saved successfully';
       session()->flash('success',$message);
       return response()->json([
           'status' => true,
           'message' => $message
       ]);
   }
}



