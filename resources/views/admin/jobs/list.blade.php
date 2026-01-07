@extends('front.layouts.app')
@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>

            <div class="col-lg-9">
                <div class="message-div"></div>

                <div class="card border-0 shadow mb-4">
                    
              


      <div class="card border-0  mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Jobs</h3>
                            </div>
                           
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @foreach($jobs as $job)
                                    <tr>
                                        <td>{{$job->id}}</td>
                                        <td>
                                            <p>{{$job->title}}</p>
                                            <p>Applicants:{{$job->applications->count()}}</p>
                                        </td>
                                        <td>{{$job->user->name}}</td>
                                        <td>
                                            @if($job->status ==1)
                                            <p class="text-success">Active</p>
                                            @else
                                            <p class="text-danger">Block</p>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d-m-Y') }}</td>
                                        <td>
                                         <div class="action-dots ">
                                                <button class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    <li><a class="dropdown-item" href="{{ route('admin.jobs.edit', $job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>

                                                    <li><a class="dropdown-item" href="#" onclick="deleteJob({{ $job->id }})" ><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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
    </div>
</section>
@endsection

@section('customJs')

<script type="text/javascript">
function deleteJob(jobId) {
    if (confirm('Are you sure you want to delete this job?')) {
        $.ajax({
            url: '/admin/jobs/' + jobId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                let messageDiv = $('.message-div'); // front/message div
                if (response.status) {
                    messageDiv.html(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );
                    location.reload();
                } else {
                    messageDiv.html(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );
                }
            },
            error: function(xhr) {
                let messageDiv = $('.message-div');
                messageDiv.html(
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    'Something went wrong!' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>'
                );
            }
        });
    }
}


</script>
@endsection


