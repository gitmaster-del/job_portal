<?php

namespace App\Http\Controllers\Admin;
use App\Models\Job;
use App\Models\Category;
use App\Models\JobType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::orderBy('created_at','desc')->with('user','applications')->paginate(10);
        return view('admin.jobs.list',[
            'jobs' => $jobs
        ]);


    }

   
   public function edit($id)
{
    $job = Job::find($id);
    
    if (!$job) {
        abort(404);
    }

    $categories = Category::orderBy('name', 'ASC')->get();
    $jobTypes = JobType::orderBy('name', 'ASC')->get();

    return view('admin.jobs.edit', compact('job', 'categories', 'jobTypes'));
}

public function update(Request $request, $id)
{
    $job = Job::find($id);

    if (!$job) {
        return response()->json([
            'status' => false,
            'errors' => ['job' => ['Job not found.']]
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'title' => 'required|min:5|max:200',
        'category' => 'required',
        'jobType' => 'required',
        'vacancy' => 'required|integer|min:1',
        'location' => 'required|max:50',
        'description' => 'required',
        'experience' => 'required',
        'company_name' => 'required|min:3|max:75',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $job->title = $request->title;
    $job->category_id = $request->category;
    $job->job_type_id = $request->jobType;
    $job->vacancy = $request->vacancy;
    $job->salary = $request->salary;
    $job->location = $request->location;
     $job->isfeatured = (!empty($request->isFeatured) )? $request->isFeatured: 0;
    $job->status = $request->status ?? 0;
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
        'status' => true,
        'message' => 'Job updated successfully.'
    ]);
}


public function destroy($id)
{
    $job = Job::find($id);

    if (!$job) {
        return response()->json([
            'status' => false,
            'message' => 'Job not found.'
        ], 404);
    }

    $job->delete();

    return response()->json([
        'status' => true,
        'message' => 'Job deleted successfully.'
    ]);
}

}




