@extends('front.layouts.app')
@section('main')

<section class="section-3 py-5 bg-2">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-10">
                <h2>Find Jobs</h2>
            </div>
            <div class="col-6 col-md-2 text-end">
                <select name="sort" id="sort" class="form-control">
                    <option value="1" {{ (Request::get('sort') == '1') ? 'selected' : '' }}>Latest</option>
                    <option value="0" {{ (Request::get('sort') == '0') ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-4 col-lg-3 mb-4">
                <form action="" method="" name="searchForm" id="searchForm">
                    <div class="card border-0 shadow p-4">

                        <div class="mb-4">
                            <h5>Keywords</h5>
                            <input value="{{ Request::get('keywords') }}" type="text" id="keywords" name="keywords" placeholder="Keywords" class="form-control">
                        </div>

                        <div class="mb-4">
                            <h5>Location</h5>
                            <input value="{{ Request::get('location') }}" type="text" id="location" name="location" placeholder="Location" class="form-control">
                        </div>

                        <div class="mb-4">
                            <h5>Category</h5>
                            <select name="category" id="category" class="form-control">
                                <option value="">Select a Category</option>
                                @if($categories->isNotEmpty())
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ Request::get('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-4">
                            <h5>Job Type</h5>
                            @if($jobTypes->isNotEmpty())
                                @foreach($jobTypes as $jobType)
                                    <div class="form-check mb-2">
                                        <input 
                                            class="form-check-input" 
                                            name="job_type[]" 
                                            type="checkbox" 
                                            value="{{ $jobType->id }}" 
                                            id="job-type-{{ $jobType->id }}"
                                            {{ (in_array($jobType->id, $jobTypeArray)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="job-type-{{ $jobType->id }}">{{ $jobType->name }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mb-4">
                            <h5>Experience</h5>
                            <select name="experience" id="experience" class="form-control">
                                <option value="">Select Experience</option>
                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}" {{ Request::get('experience') == $i ? 'selected' : '' }}>{{ $i }} Years</option>
                                @endfor
                                <option value="10_plus" {{ Request::get('experience') == '10_plus' ? 'selected' : '' }}>10+ Years</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Search</button>
                        <a href="{{ route('jobs') }}" class="btn btn-secondary mt-3">Reset</a>
                    </div>
                </form>
            </div>

            <div class="col-md-8 col-lg-9">
                <div class="job_listing_area">
                    <div class="row">
                        @if($jobs->isNotEmpty())
                            @foreach($jobs as $job)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-0 shadow mb-4 p-3">
                                        <div class="card-body">
                                            <h5 class="fs-5 mb-2">{{ $job->title }}</h5>
                                            <p>{{ Str::words(strip_tags($job->description), 15, '...') }}</p>

                                            <div class="bg-light p-3 border mb-3">
                                                <p class="mb-1">
                                                    <i class="fa fa-map-marker me-2"></i>{{ $job->location }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="fa fa-clock-o me-2"></i>{{ $job->jobType->name ?? 'N/A' }}
                                                </p>
                                                @if(!is_null($job->salary))
                                                    <p class="mb-0">
                                                        <i class="fa fa-usd me-2"></i>{{ $job->salary }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="d-grid">
                                                <a href="{{ route('jobdetail', $job->id) }}" class="btn btn-primary btn-sm">Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-md-12">
                                {{ $jobs->withQueryString()->links() }}
                            </div>




                        @else
                            <div class="col-12">
                                <div class="alert alert-warning">Jobs Not Found</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script>
$("#searchForm").submit(function(e) {
    e.preventDefault();

    let url = '{{ route("jobs") }}?';
    let params = [];

    let keywords = $("#keywords").val();
    let location = $("#location").val();
    let category = $("#category").val();
    let experience = $("#experience").val();
    let sort = $("#sort").val();

    let jobTypes = [];
    $("input[name='job_type[]']:checked").each(function() {
        jobTypes.push($(this).val());
    });

    if (keywords) params.push('keywords=' + encodeURIComponent(keywords));
    if (location) params.push('location=' + encodeURIComponent(location));
    if (category) params.push('category=' + encodeURIComponent(category));
    if (jobTypes.length > 0) params.push('job_type=' + jobTypes.join(','));
    if (experience) params.push('experience=' + encodeURIComponent(experience));
    if (sort) params.push('sort=' + encodeURIComponent(sort));

    url += params.join('&');
    window.location.href = url;
});

$("#sort").change(function() {
    $("#searchForm").submit();
});
</script>
@endsection
