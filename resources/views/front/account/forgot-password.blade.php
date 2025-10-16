@extends('front.layouts.app')
@section('main')

<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>

        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show flash-message">
                <p>{{ Session::get('success') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show flash-message">
                <p>{{ Session::get('error') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Forgot Password</h1>
                    <form action="{{ route('account.processForgotPassword') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="mb-2">Email*</label>
                            <input type="text" value="{{ old('email') }}" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="example@example.com">
                            @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div> 

                        <div class="justify-content-between d-flex">
                            <button class="btn btn-primary mt-2">Submit</button>
                        </div>
                    </form>                    
                </div>

                <div class="mt-4 text-center">
                    <p>Click here to Login -> <a href="{{ route('login') }}">Login</a></p>
                </div>
            </div>
        </div>

        <div class="py-lg-5">&nbsp;</div>
    </div>
</section>

@endsection

@section('customJs')
<script>
    window.addEventListener('DOMContentLoaded', function() {
        const flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach(function(message) {
            setTimeout(function() {
                const alert = bootstrap.Alert.getOrCreateInstance(message);
                alert.close();
            }, 5000);
        });
    });
</script>
@endsection
