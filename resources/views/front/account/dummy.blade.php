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

              
            </div>
        </div>
    </div>
</section>
@endsection


</script>
@endsection
