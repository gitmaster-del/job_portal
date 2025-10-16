@extends('front.layouts.app')
@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>

            <div class="col-lg-9">
                @include('front.message')


                        <div id="jobMessage"></div>

                     <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">My Jobs</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                <a href="{{route('account.createJob')}}" class="btn btn-primary">Post a Job</a>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Job Created</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($jobs->isNotEmpty())
                                    @foreach($jobs as  $job)
                                         <tr class="active">
                                        <td>
                                            <div class="job-name fw-500">{{$job->title}}</div>
                                            <div class="info1">{{$job->jobType->name}} . {{$job->location}}</div>
                                        </td>
                                        <td>{{\Carbon\Carbon::parse($job->created_at)->format('d M, Y')}}</td>
                                        <td>{{$job->applications->count()}} Applications</td>
                                        <td>
                                            @if($job->status == 1)
                                            <div class="job-status text-capitalize">Active</div>
                                            @else
                                            <div class="job-status text-capitalize">Block</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-dots float-end">
                                                <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ route('jobdetail',($job->id)) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>

                                                  
                                                        <li><a class="dropdown-item" href="{{ route('account.editJob',($job->id)) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>

                                                    <li><a class="dropdown-item" href="#" onclick="deleteJob({{ $job->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                
                            </table>
                        </div>

                        <div>
                            {{$jobs->links()}}
                        </div>
                    </div>
                </div>
              
            </div>
        </div>
    </div>
</section>
@endsection
@section('customJs')
<script type="text/javascript">
function deleteJob(jobId) {
    if (confirm("Are you sure you want to delete this job?")) {
        $.ajax({
            url: "{{ route('account.deleteJob') }}",
            type: "POST",
            data: {
                jobId: jobId,
                _token: '{{ csrf_token() }}'
            },
            dataType: "json",
            success: function(response) {
                if(response.success) {
                    $('#jobMessage').html('<div class="alert alert-success">'+response.message+'</div>');

                    $('tr').has('a[onclick="deleteJob('+jobId+')"]').remove();

                    setTimeout(function(){
                        $('#jobMessage').fadeOut('slow', function() {
                            $(this).html('').show(); 
                        });
                    }, 2000);

                } else {
                    $('#jobMessage').html('<div class="alert alert-danger">'+response.message+'</div>');

                    setTimeout(function(){
                        $('#jobMessage').fadeOut('slow', function() {
                            $(this).html('').show();
                        });
                    }, 2000);
                }
            },
            error: function() {
                $('#jobMessage').html('<div class="alert alert-danger">Something went wrong.</div>');

                setTimeout(function(){
                    $('#jobMessage').fadeOut('slow', function() {
                        $(this).html('').show();
                    });
                }, 2000);
            }
        });
    }
}



</script>



