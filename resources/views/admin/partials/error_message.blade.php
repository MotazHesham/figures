
@if(count($errors) > 0)
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            {{$error}}
            <br>
        @endforeach
    </div>
    @section('script')
        <script type="text/javascript">
            showFrontendAlert('error', 'Error Occured');
        </script>
    @endsection
@endif