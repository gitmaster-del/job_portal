@extends('front.layouts.app')
@section('main')

<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>

       @if(Session::has('success'))
    <div class="alert alert-success flash-message">
        {{ Session::get('success') }}
    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger flash-message">
        {{ Session::get('error') }}
    </div>
@endif


        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Reset Password</h1>
                    <form action="{{ route('account.processResetPassword') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $tokenString }}">

                        <div class="mb-3">
                            <label for="new_password" class="mb-2">New Password*</label>
                            <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror">
                            @error('confirm_password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="justify-content-between d-flex">
                            <button class="btn btn-primary mt-2">Reset Password</button>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>

        <div class="py-lg-5">&nbsp;</div>
    </div>
</section>

@endsection

@section('customJs')
<script>
@section('customJs')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.flash-message');

    messages.forEach(function(msg) {
        setTimeout(function() {
            msg.style.transition = "opacity 0.5s ease";
            msg.style.opacity = "0";
            setTimeout(function() {
                msg.remove();
            }, 500);
        }, 2000);
    });
});
</script>
@endsection

</script>
@endsection
