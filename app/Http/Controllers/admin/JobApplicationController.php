<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{

public function index(){
  $applications = JobApplication::orderBy('created_at','desc')
  ->with('job','user','employer')
  ->paginate(10);

  return view('admin.job-applications.list',[
       'applications'=> $applications
  ]);

}



public function destroy($id){
    $jobApplication = JobApplication::find($id);

    if (!$jobApplication) {
        return response()->json([
            'status' => false,
            'message' => 'Job not found.'
        ], 404);
    }

    $jobApplication->delete();

    return response()->json([
        'status' => true,
        'message' => 'Job deleted successfully.'
    ]);
}

}
