@extends('front.layouts.app')

@section('main')
<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>

                    {{-- Success message --}}
                    <div class="alert alert-success d-none" id="successMsg"></div>

                    <form name="registrationForm" id="registrationForm">
                        <div class="mb-3">
                            <label class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                            <p class="text-danger"></p>
                        </div>

                        <div class="mb-3">
                            <label class="mb-2">Email*</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                            <p class="text-danger"></p>
                        </div>

                        <div class="mb-3">
                            <label class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                            <p class="text-danger"></p>
                        </div>

                        <div class="mb-3">
                            <label class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                            <p class="text-danger"></p>
                        </div>

                        <button class="btn btn-primary mt-2" type="submit">Register</button>
                    </form>
                </div>

                <div class="mt-4 text-center">
                    <p>Have an account? <a href="{{ route('login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $("#registrationForm").submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("account.processRegistration") }}',
            type: 'POST',
            data: $("#registrationForm").serialize(),
            dataType: 'json',
            success: function(response) {
                // Reset errors
                $('input').removeClass('is-invalid');
                $('p.text-danger').html('');

                if (response.status === false) {
                    let errors = response.errors;

                    $.each(errors, function(key, value) {
                        let input = $("#" + key);
                        input.addClass('is-invalid');
                        input.siblings('p').html(value[0]);
                    });
                } else {
                    $("#successMsg").removeClass('d-none').html(response.message);
                    $("#registrationForm")[0].reset();

                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                }
            },
            error: function(xhr) {
                alert("Something went wrong!");
                console.log(xhr.responseText);
            }
        });
    });
</script>
@endsection
