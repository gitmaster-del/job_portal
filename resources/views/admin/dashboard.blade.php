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
                <div class="card border-0 shadow mb-4">
                    <div class="card-body dashboard text-center ">
               <p class="h2">Welcome Administrator !!</p>
              
               </div>
               </div>
            </div>
        </div>
    </div>
</section>
@endsection


