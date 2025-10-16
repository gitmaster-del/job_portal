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

                <form action="" method="POST" id="createJobForm" name="CreateJobForm">
                    @csrf

                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Job Details</h3>

                            {{-- Job Title & Category --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Title<span class="req">*</span></label>
                                    <input type="text" placeholder="Job Title" id="title" name="title" class="form-control">
                                    <p class="text-danger" id="error-title"></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Category<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @if($categories->isNotEmpty())
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="text-danger" id="error-category"></p>
                                </div>
                            </div>

                            {{-- Job Type & Vacancy --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Job Type<span class="req">*</span></label>
                                    <select name="jobType" id="jobType" class="form-control">
                                        <option value="">Select Job Nature</option>
                                        @if($jobType->isNotEmpty())
                                            @foreach($jobType as $Jobtype)
                                                <option value="{{ $Jobtype->id }}">{{ $Jobtype->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="text-danger" id="error-jobType"></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input type="number" min="1" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                    <p class="text-danger" id="error-vacancy"></p>
                                </div>
                            </div>

                            {{-- Salary & Location --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Salary</label>
                                    <input type="text" placeholder="Salary" id="salary" name="salary" class="form-control">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Location<span class="req">*</span></label>
                                    <input type="text" placeholder="location" id="location" name="location" class="form-control">
                                    <p class="text-danger" id="error-location"></p>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label class="mb-2">Description<span class="req">*</span></label>
                                <textarea class="form-control" name="description" id="description" rows="5" placeholder="Description"></textarea>
                                <p class="text-danger" id="error-description"></p>
                            </div>

                            {{-- Optional Fields --}}
                            <div class="mb-4">
                                <label class="mb-2">Benefits</label>
                                <textarea class="form-control" name="benefits" id="benefits" rows="5" placeholder="Benefits"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="mb-2">Responsibility</label>
                                <textarea class="form-control" name="responsibility" id="responsibility" rows="5" placeholder="Responsibility"></textarea>
                            </div>
                           
                            {{-- Experience --}}
                            <div class="mb-4">
                                <label class="mb-2">Experience<span class="req">*</span></label>
                                <select name="experience" id="experience" class="form-control">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }} Year{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                    <option value="10_plus">10+ Years</option>
                                </select>
                                <p class="text-danger" id="error-experience"></p>
                            </div>

                            {{-- Keywords --}}
                            <div class="mb-4">
                                <label class="mb-2">Keywords</label>
                                <input type="text" placeholder="keywords" id="keywords" name="keywords" class="form-control">
                            </div>

                            {{-- Company Details --}}
                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Name<span class="req">*</span></label>
                                    <input type="text" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                                    <p class="text-danger" id="error-company_name"></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Location</label>
                                    <input type="text" placeholder="Location" id="company_location" name="company_location" class="form-control">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="mb-2">Website</label>
                                <input type="text" placeholder="Website" id="company_website" name="company_website" class="form-control">
                            </div>
                        </div> 

                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Save Job</button>
                        </div>               
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#createJobForm').on('submit', function(e) {
        e.preventDefault();


        // Clear previous errors
        $('.form-control').removeClass('is-invalid');
        $('.text-danger').html('');

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('account.saveJob') }}",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {

                // ✅ fix here — use 'success' or 'status' depending on your controller response
                if (response.success === true) {
                    window.location.href = "{{ route('account.myJob') }}";
                } else {
                    alert(response.message || "Unexpected response from server.");
                }
            },
           error: function(xhr) {
    if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;

        $.each(errors, function(field, messages) {
            let fieldId = '#' + field;
            let errorId = '#error-' + field;

            $(fieldId).addClass('is-invalid');
            $(errorId).html(messages[0]).addClass('invalid-feedback');
        });
    } else {
        alert('Something went wrong. Please try again.');
    }
}

        });
    });
});
</script>
@endsection
