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
                    <div class="card border-0 mb-4 p-3">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Job Applications</h3>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Job Title</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Employer</th>
                                            <th scope="col">Applied Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @foreach($applications as $application)
                                        <tr id="job-{{$application->id}}">
                                            <td>{{$application->job->title}}</td>
                                            <td>{{$application->user->name}}</td>
                                            <td>{{$application->employer->name}}</td>
                                            <td>{{ \Carbon\Carbon::parse($application->applied_date)->format('d-m-Y') }}</td>
                                            <td>
                                                <div class="action-dots">
                                                    <button class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="deleteJobApplication({{ $application->id }})">
                                                                <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div>
                                {{$applications->links()}}
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
function deleteJobApplication(jobId) {
    if (confirm('Are you sure you want to delete this job application?')) {
        $.ajax({
            url: '/admin/job-applications/' + jobId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                let messageDiv = $('.message-div');
                if (response.status) {
                    messageDiv.html(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );
                    $('#job-' + jobId).remove();
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
