@extends('front.layouts.app')
@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
             @include('front.account.sidebar')
            </div>

            <div class="col-lg-9">

                <!-- AJAX Message for Profile Update -->
                <div id="ajaxMessage" class="alert d-none"></div>
                <!-- resources/views/account/profile.blade.php -->
                @if(session('error'))
               <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Profile Form -->
                <div class="card border-0 shadow mb-4">
                    <form action="{{ route('account.updateProfile') }}" method="post" id="userForm" name="userForm">
                        @csrf
                        @method('put')
                        <div class="card-body  p-4">
                            <h3 class="fs-4 mb-1">My Profile</h3>
                            <div class="mb-4">
                                <label for="" class="mb-2">Name*</label>
                                <input type="text" name="name" id="name" placeholder="Enter Name" class="form-control" value="{{$user->name}}">
                                <p class="text-danger"></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email" class="form-control" value="{{$user->email}}">
                                <p class="text-danger"></p>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Designation</label>
                                <input type="text" name="designation" id="designation" placeholder="Designation" class="form-control" value="{{$user->designation}}">
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Mobile</label>
                                <input type="text" name="mobile" id="mobile" placeholder="Mobile" class="form-control" value="{{$user->mobile}}">
                            </div>                        
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Form -->
                <form action="" id="changePasswordForm"name="changePasswordForm" method="post">
                    @csrf
                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h3 class="fs-4 mb-1">Change Password</h3>
                        <div class="mb-4">
                            <label for="" class="mb-2">Old Password*</label>
                            <input type="password" name="old_password" id="old_password" placeholder="current-password" class="form-control">
                            <p></p>
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">New Password*</label>
                            <input type="password" name="new_password" id="new_password" placeholder="new-password" class="form-control">
                            <p></p>
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="new-password" class="form-control">
                            <p></p>
                        </div>
                    </div>
                    <div class="card-footer  p-4">
                        <button type="button" class="btn btn-primary">Update</button>
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
$(document).ready(function(){
    $("#userForm").submit(function(e){
        e.preventDefault();

        $.ajax({
            url : $(this).attr('action'),
            type : 'PUT',
            dataType : 'json',
            data : $(this).serialize(),
            success : function(response){
                var msgDiv = $("#ajaxMessage");
                msgDiv.removeClass('alert-success alert-danger d-none').html('');

                if(response.status){
                    msgDiv.addClass('alert-success').removeClass('d-none').html('Profile updated successfully.');

                    $("#name, #email").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');

                    $(".s-body h5").text($("#name")
                    .val());
                    $(".s-body p.text-muted")
                    .text($("#designation").val());

                    setTimeout(function(){
                        msgDiv.addClass('d-none')
                        .removeClass('alert-success').html('');
                    }, 5000);

                } else {
                    msgDiv.addClass('alert-danger')
                    .removeClass('d-none')
                    .html('Please fix the errors below.');



                    var errors = response.errors;
                    if(errors.name){
                        $("#name").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.name);
                    } else {
                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }

                    if(errors.email){
                        $("#email").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.email);
                    } else {
                        $("#email").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                }
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    });
});




$(document).ready(function(){
    $("#changePasswordForm").on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url : "{{ route('account.updatePassword') }}", 
            type : 'POST',
            dataType : 'json',
            data : $(this).serialize(),
            success : function(response){
                var msgDiv = $("#ajaxMessage");
                msgDiv.removeClass('alert-success alert-danger d-none').html('');

                if(response.status){
                    msgDiv.addClass('alert-success').removeClass('d-none').html(response.message);

                    $("#old_password, #new_password, #confirm_password").val('');

                    setTimeout(function(){
                        msgDiv.addClass('d-none').removeClass('alert-success').html('');
                    }, 5000);

                } else {
                    msgDiv.addClass('alert-danger').removeClass('d-none').html('Please fix the errors below.');

                    var errors = response.errors;
                    if(errors.old_password){
                        $("#old_password").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.old_password[0]);
                    } else {
                        $("#old_password").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if(errors.new_password){
                        $("#new_password").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.new_password[0]);
                    } else {
                        $("#new_password").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback').html('');
                    }

                    if(errors.confirm_password){
                        $("#confirm_password").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.confirm_password[0]);
                    } else {
                        $("#confirm_password").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback').html('');
                    }
                }
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    });

    $("#changePasswordForm button").click(function(){
        $("#changePasswordForm").submit();
    });
});




document.querySelectorAll('.close-alert').forEach(function(closeBtn) {
    closeBtn.addEventListener('click', function() {
        this.parentElement.style.display = 'none';
    });
});

setTimeout(function() {
    document.querySelectorAll('[role="alert"]').forEach(function(alert) {
        alert.style.display = 'none';
    });
}, 5000);

</script>
@endsection
