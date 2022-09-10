@extends('layouts.blank')

@section('content')
<div class="text-center">
    <h1 class="error-code text-danger">{{__('403')}}</h1>
    <p class="h4 text-uppercase text-bold">{{__('Not Authoriza!')}}</p>
    <div class="pad-btm">
        {{__('Sorry,.')}}
    </div>
</div>
@endsection
