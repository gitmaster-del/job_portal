<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\savedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AccountController extends Controller
{
    // Show registration form
    public function registration()
    {
        return view('front.account.registration');
    }

    // Process registration
    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Save new user
        $user = new User();
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Registration successful. Please login.',
        ]);
    }

    // Show login form
    public function login()
    {
        return view('front.account.login');
    }

    // Authenticate user
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('account.profile');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    // Show user profile
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('front.account.profile', compact('user'));
    }

    // Update profile (AJAX)
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'designation' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Update user profile
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->designation = trim($request->designation);
        $user->mobile = trim($request->mobile);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }




public function createJob(){

    $categories =Category::orderBy('name','ASC')->where('status',1)->get();

    $jobType =JobType::orderBy('name','ASC')->where('status',1)->get();

    return view('front.account.job.create',[
        'categories' => $categories,
        'jobType' => $jobType
        ]);
    }


    public function saveJob(Request $request){
        

   $validator = Validator::make($request->all(), [
         'title'=>'required|min:5|max:200',
           'category'=>'required',
           'jobType'=>'required',
           'vacancy'=>'required|integer',
           'location'=>'required|max:50',
           'description'=>'required',
           'company_name'=>'required|min:3|max:70',
]);


if ($validator->passes()){


    $job = new Job();
    $job -> title = $request->title;
    $job -> category_id = $request->category;
    $job -> job_type_id = $request->jobType;
    $job ->user_id = Auth::user()->id;
    $job -> vacancy = $request->vacancy;
    $job -> salary = $request->salary;
    $job -> location = $request->location;
    $job -> description = $request->description;
    $job -> benefits = $request->benefits;
    $job -> responsibility = $request->responsibility;
    $job -> keywords = $request->keywords;
    $job -> experience = $request->experience;
    $job -> company_name = $request->company_name;
    $job -> company_location = $request->company_location;
    $job -> company_website = $request->company_website;
    $job->save();


    Session()->flash('success','Job added Successfully.');
    return response()->json([
        'success' => true,
        'errors' => []
    ]);

}else{
      return response()->json([
        'success' => false,
        'errors' => $validator->errors()
    ], 422);
}
}

public function myJob(){

    $jobs = Job::where('user_id',Auth::user()->id)->with('jobType')->orderBy('created_at','DESC')->paginate(10);

    return view('front.account.job.my-jobs',[
    'jobs' => $jobs

    ]);
}



public function editJob($jobId)
{
    $job = Job::where('id', $jobId)
              ->where('user_id', Auth::id())
              ->firstOrFail();

    $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
    $jobTypes = JobType::where('status', 1)->orderBy('name', 'asc')->get();

    return view('front.account.job.edit', compact('job', 'categories', 'jobTypes'));
}




public function updateJob(Request $request, $jobId)
{
  
    $validator = Validator::make($request->all(), [
        'title'=>'required|min:5|max:200',
        'category'=>'required',
        'jobType'=>'required',
        'vacancy'=>'required|integer',
        'location'=>'required|max:50',
        'description'=>'required',
        'company_name'=>'required|min:3|max:70',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $job = Job::find($jobId);

    if (!$job) {
        return response()->json([
            'success' => false,
            'errors' => ['job' => ['Job not found.']]
        ], 404);
    }

    $job->title = $request->title;
    $job->category_id = $request->category;
    $job->job_type_id = $request->jobType;
    $job->user_id = Auth::id();
    $job->vacancy = $request->vacancy;
    $job->salary = $request->salary;
    $job->location = $request->location;
    $job->description = $request->description;
    $job->benefits = $request->benefits;
    $job->responsibility = $request->responsibility;
    $job->keywords = $request->keywords;
    $job->experience = $request->experience;
    $job->company_name = $request->company_name;
    $job->company_location = $request->company_location;
    $job->company_website = $request->company_website;
    $job->save();

    session()->flash('success', 'Job updated successfully.');

    return response()->json([
        'success' => true,
        'message' => 'Job updated successfully.'
    ]);
}



public function deleteJob(Request $request)
{
    $job = Job::where([
        'user_id' => Auth::user()->id,
        'id' => $request->jobId,
    ])->first();

    if(!$job){
        return response()->json([
            'success' => false,
            'message' => 'Job not found.'
        ]);
    }

    $job->delete();

    return response()->json([
        'success' => true,
        'message' => 'Job deleted successfully.'
    ]);
}


public function myJobApplications(){

    $jobApplications = JobApplication::where('user_id',Auth::user()->id)
    ->with(['job','job.jobType','job.applications'])
    ->orderBy('created_at','desc')
    ->paginate(10);


    return view('front.account.job.my-job-applications',[
        'jobApplications' => $jobApplications
    ]);
}

public function removeJobs(Request $request)
{
    $jobApplication = JobApplication::where([
        'id' => $request->id,
        'user_id' => Auth::user()->id
    ])->first();

    if (!$jobApplication) {
        return response()->json([
            'success' => false,
            'message' => 'Job application not found.'
        ]);
    }

    $jobApplication->delete();

    return response()->json([
        'success' => true,
        'message' => 'Job application removed successfully.'
    ]);
}


public function savedJobs( ){
  

    $savedJob =savedJob::where([
        'user_id'=>Auth::user()->id
    ])->with(['job','job.jobType','job.applications'])->orderBy('created_at','desc')->paginate(10);

    return view('front.account.job.saved-jobs',[
        'savedJobs' => $savedJob
    ]);
}

public function removeSavedJob(Request $request)
{
    $savedJob = SavedJob::where([
        'id' => $request->id,
        'user_id' => Auth::user()->id
    ])->first();

    if ($savedJob == null) {
        session()->flash('erroe','Job Not Found');
        return response()->json([
            'success' => false,
            'message' => 'Job not found.'
        ]);
    }

    SavedJob::find($request->id)->delete();
        session()->flash('erroe','Job removed successfully');

    return response()->json([
        'success' => true,
        'message' => 'Job removed successfully.'
    ]);
}


public function updatePassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'old_password' => 'required',
        'new_password' => 'required|min:5',
        'confirm_password' => 'required|same:new_password'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'status' => false,
            'errors' => ['user' => ['User not found.']],
        ]);
    }

    // Check old password
    if (!Hash::check($request->old_password, $user->password)) {
        return response()->json([
            'status' => false,
            'errors' => ['old_password' => ['Old password is incorrect.']],
        ]);
    }

    $user->password = $request->new_password;

    try {
        $user->save();
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'errors' => ['exception' => [$e->getMessage()]],
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Password updated successfully.',
    ]);
}


public function forgotPassword(){
    return view('front.account.forgot-password');
}



public function processForgotPassword(Request $request){
    $validator = Validator::make($request->all(),[
         'email' => 'required|email|exists:users,email'
    ]);

    if($validator->fails()){
        return redirect()->route('account.forgotPassword')->withInput()->withErrors($validator);
    }
     $token = Str::random(60);

     \DB::table('password_reset_tokens')->where('email',$request->email)->delete();
    \DB::table('password_reset_tokens')->insert([
        'email'=>$request->email,
        'token'=> $token,
        'created_at'=>now()
    ]);
     $user = User::where('email', $request->email)->first();
     $mailData=[
       'token'=>$token,
       'user'=>$user,
       'subject'=>'You have requested to change your password.'
     ];
    Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

    return redirect()->route('account.forgotPassword')->with('success','Reset Password email has been sent.');
    
}


public function resetPassword($tokenString){
    $token = \DB::table('password_reset_tokens')->where('token',$tokenString)->first();
     \DB::table('password_reset_tokens')->where('token',$tokenString)->first();
     if($token == null){
        return redirect()->route('account.forgotPassword')->with('error','Invalid Token');
     }


     return view('front.account.reset-password',[
        'tokenString' => $tokenString
     ]);
     
}

public function processResetPassword(Request $request){
     $token = \DB::table('password_reset_tokens')->where('token',$request->token)->first();
     if($token == null){
        return redirect()->route('account.forgotPassword')->with('error','Invalid Token');
     }
     $validator = Validator::make($request->all(),[
         'new_password' => 'required|min:5',
         'confirm_password'=>'required|same:new_password'
    ]);

    if($validator->fails()){
        return redirect()->route('account.resetPassword',$request->token)->withErrors($validator);
    }
    User::where('email',$token->email)->update([
         'password'=>Hash::make($request->new_password)
    ]);
        return redirect()->route('login')->with('success','Password reset successfully.');

}


   
}
