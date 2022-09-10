@extends('frontend.layouts.app')

@section('content')

<section class="gry-bg py-4">
    <div class="profile">
        <div class="container">
            <div class="row">

                <div id="user-register-partial" class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
                    @include('frontend.partials.register_partial')
                </div>

            </div>
        </div>
    </div>
</section>

@endsection