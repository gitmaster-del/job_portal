@extends('front.layouts.app')
@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>

            <div class="col-lg-9">
                @include('front.message')

                <form action="" method="POST" id="editJobForm" name="editJobForm">
                    @csrf
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-4">Edit Job Details</h3>

                            {{-- Job Title & Category --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Title <span class="req">*</span></label>
                                    <input value="{{ $job->title }}" type="text" name="title" id="title" class="form-control" placeholder="Job Title">
                                    <p class="text-danger" id="error-title"></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Category <span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $job->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger" id="error-category"></p>
                                </div>
                            </div>

                            {{-- Job Type & Vacancy --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Job Type <span class="req">*</span></label>
                                    <select name="jobType" id="jobType" class="form-control">
                                        <option value="">Select Job Nature</option>
                                        @foreach($jobTypes as $jobType)
                                            <option value="{{ $jobType->id }}" {{ $job->job_type_id == $jobType->id ? 'selected' : '' }}>
                                                {{ $jobType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger" id="error-jobType"></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Vacancy <span class="req">*</span></label>
                                    <input value="{{ $job->vacancy }}" type="number" min="1" name="vacancy" id="vacancy" class="form-control" placeholder="Vacancy">
                                    <p class="text-danger" id="error-vacancy"></p>
                                </div>
                            </div>

                            {{-- Salary & Location --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Salary</label>
                                    <input value="{{ $job->salary }}" type="text" name="salary" id="salary" class="form-control" placeholder="Salary">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Location <span class="req">*</span></label>
                                    <input value="{{ $job->location }}" type="text" name="location" id="location" class="form-control" placeholder="Location">
                                    <p class="text-danger" id="error-location"></p>
                                </div>
                            </div>

                            {{-- Featured & Status --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="isFeatured" id="isFeatured" {{ $job->isfeatured ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isFeatured">Featured</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4 d-flex align-items-center">
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ $job->status == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusActive">Active</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusBlock" value="0" {{ $job->status == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusBlock">Block</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label class="mb-2">Description <span class="req">*</span></label>
                                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Description">{{ $job->description }}</textarea>
                                <p class="text-danger" id="error-description"></p>
                            </div>

                            {{-- Benefits --}}
                            <div class="mb-4">
                                <label class="mb-2">Benefits</label>
                                <textarea name="benefits" id="benefits" class="form-control" rows="4" placeholder="Benefits">{{ $job->benefits }}</textarea>
                            </div>

                            {{-- Responsibility --}}
                            <div class="mb-4">
                                <label class="mb-2">Responsibility</label>
                                <textarea name="responsibility" id="responsibility" class="form-control" rows="4" placeholder="Responsibility">{{ $job->responsibility }}</textarea>
                            </div>

                            {{-- Experience --}}
                            <div class="mb-4">
                                <label class="mb-2">Experience <span class="req">*</span></label>
                                <select name="experience" id="experience" class="form-control">
                                    @for($i=1; $i<=9; $i++)
                                        <option value="{{ $i }}" {{ $job->experience == $i ? 'selected' : '' }}>{{ $i }} Year{{ $i>1 ? 's' : '' }}</option>
                                    @endfor
                                    <option value="10_plus" {{ $job->experience == '10_plus' ? 'selected' : '' }}>10+ Years</option>
                                </select>
                                <p class="text-danger" id="error-experience"></p>
                            </div>

                            {{-- Keywords --}}
                            <div class="mb-4">
                                <label class="mb-2">Keywords</label>
                                <input value="{{ $job->keywords }}" type="text" name="keywords" id="keywords" class="form-control" placeholder="Keywords">
                            </div>

                            {{-- Company Details --}}
                            <h3 class="fs-4 mb-4 mt-5 border-top pt-4">Company Details</h3>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Company Name <span class="req">*</span></label>
                                    <input value="{{ $job->company_name }}" type="text" name="company_name" id="company_name" class="form-control" placeholder="Company Name">
                                    <p class="text-danger" id="error-company_name"></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Company Location</label>
                                    <input value="{{ $job->company_location }}" type="text" name="company_location" id="company_location" class="form-control" placeholder="Company Location">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="mb-2">Website</label>
                                <input value="{{ $job->company_website }}" type="text" name="company_website" id="company_website" class="form-control" placeholder="Website">
                            </div>
                        </div>

                        <div class="card-footer p-4 text-end">
                            <button type="submit" class="btn btn-primary">Update Job</button>
                            <a href="{{ route('admin.jobs') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
$(document).ready(function() {
    $('#editJobForm').on('submit', function(e) {
        e.preventDefault();

        $('.form-control').removeClass('is-invalid');
        $('.text-danger').html('');

        $('button[type="submit"]').prop('disabled', true).html('Updating...');

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('admin.jobs.update', $job->id) }}",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                $('button[type="submit"]').prop('disabled', false).html('Update Job');

                if (response.status === true) {
                    window.location.href = "{{ route('admin.jobs') }}";
                } else {
                    alert(response.message || "An error occurred.");
                }
            },
            error: function(xhr) {
                $('button[type="submit"]').prop('disabled', false).html('Update Job');
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#' + field).addClass('is-invalid');
                        $('#error-' + field).html(messages[0]);
                    });
                } else if (xhr.status === 404) {
                    alert('Job not found.');
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
});
</script>
@endsection
