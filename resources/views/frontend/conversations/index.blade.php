@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @elseif(Auth::user()->user_type == 'designer')
                        @include('frontend.inc.designer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0 d-inline-block">
                                        {{__('Conversations')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li><a href="{{ route('conversations.index') }}">{{__('Conversations')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card no-border mt-4 p-3">
                            <div class="py-4">
                                @foreach ($conversations as $key => $conversation)
                                    <div class="block block-comment border-bottom">
                                        

                                            <div class="row">
                                                <div class="col-1">
                                                    <div class="block-image">
                                                        <a  href="{{ route('conversations.show', encrypt($conversation->id)) }}">
                                                            @if (Auth::user()->id == $conversation->sender_id)
                                                                <img @if ($conversation->receiver->avatar_original == null) src="{{ asset('frontend/images/user.png') }}" @else src="{{ asset($conversation->receiver->avatar_original) }}" @endif class="rounded-circle">
                                                            @else
                                                                <img width="50" height="50" @if ($conversation->sender->avatar_original == null) src="{{ asset('frontend/images/user.png') }}" @else src="{{ asset($conversation->sender->avatar_original) }}" @endif class="rounded-circle">
                                                            @endif
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <p>
                                                        @if (Auth::user()->id == $conversation->sender_id)
                                                            <a  href="{{ route('conversations.show', encrypt($conversation->id)) }}">{{ $conversation->receiver->email }}</a>
                                                        @else
                                                            <a  href="{{ route('conversations.show', encrypt($conversation->id)) }}">{{ $conversation->sender->email }}</a>
                                                        @endif
                                            
                                                        @if ((Auth::user()->id == $conversation->sender_id && $conversation->sender_viewed == 0) || (Auth::user()->id == $conversation->receiver_id && $conversation->receiver_viewed == 0))
                                                            <span class="badge badge-pill badge-danger">{{ __('New') }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="col-9">
                                                </div>
                                            </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $conversations->links() }}
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
