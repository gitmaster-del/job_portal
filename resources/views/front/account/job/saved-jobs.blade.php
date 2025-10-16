@extends('front.layouts.app')
@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>

            <div class="col-lg-9">

                {{-- ✅ Only this message area (removed @include('front.message')) --}}
                <div id="jobMessage"></div>

                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Saved Jobs</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                <a href="{{route('account.createJob')}}" class="btn btn-primary">Post a Job</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Applicants</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($savedJobs->isNotEmpty())
                                        @foreach($savedJobs as $savedJob)
                                            <tr>
                                                <td>
                                                    <div class="job-name fw-500">{{$savedJob->job->title}}</div>
                                                    <div class="info1">{{$savedJob->job->jobType->name}} • {{$savedJob->job->location}}</div>
                                                </td>
                                                <td>{{$savedJob->job->applications->count()}} Applications</td>
                                                <td>
                                                    @if($savedJob->job->status == 1)
                                                        <div class="job-status text-success text-capitalize">Active</div>
                                                    @else
                                                        <div class="job-status text-danger text-capitalize">Blocked</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="action-dots ">
                                                        <button class="btn" data-bs-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="{{route('jobdetail', $savedJob->job_id)}}">
                                                                    <i class="fa fa-eye"></i> View
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="removeJob({{ $savedJob->id }})">
                                                                    <i class="fa fa-trash"></i> Remove
                                                                </a>
                                                            </li>
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
                            {{$savedJobs->links()}}
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
function removeJob(id) {
    if (confirm("Are you sure you want to remove this job?")) {
        $.ajax({
            url: "{{ route('account.removeSavedJob') }}",
            type: "POST",
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            dataType: "json",
            success: function(response) {
                let alertClass = response.success ? 'alert-success' : 'alert-danger';
                $('#jobMessage').html('<div class="alert '+alertClass+'">'+response.message+'</div>');

                if (response.success) {
                    $('tr').has('a[onclick="removeJob('+id+')"]').remove();
                }

                setTimeout(function(){
                    $('#jobMessage').fadeOut('slow', function() {
                        $(this).html('').show();
                    });
                }, 2000);
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
@endsection
