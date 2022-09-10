<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link name="favicon" type="image/x-icon" href="{{ asset(\App\Models\GeneralSetting::first()->favicon) }}" rel="shortcut icon" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!--active-shop Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/active-shop.min.css')}}" rel="stylesheet">

    <!--active-shop Premium Icon [ DEMONSTRATION ]-->
    <link href="{{ asset('css/demo/active-shop-demo-icons.min.css')}}" rel="stylesheet">

    <!--Font Awesome [ OPTIONAL ]-->
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">

    <!--Switchery [ OPTIONAL ]-->
    <link href="{{ asset('plugins/switchery/switchery.min.css')}}" rel="stylesheet">

    <!--DataTables [ OPTIONAL ]-->
    <link href="{{ asset('plugins/datatables/media/css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/datatables/extensions/Responsive/css/responsive.dataTables.min.css') }}" rel="stylesheet">

    <!--Select2 [ OPTIONAL ]-->
    <link href="{{ asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet">

    <link href="{{ asset('css/bootstrap-select.min.css')}}" rel="stylesheet">

    <!--Chosen [ OPTIONAL ]-->
    {{-- <link href="{{ asset('plugins/chosen/chosen.min.css')}}" rel="stylesheet"> --}}

    <!--Bootstrap Tags Input [ OPTIONAL ]-->
    <link href="{{ asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.css') }}" rel="stylesheet">

    <!--Summernote [ OPTIONAL ]-->
    <link href="{{ asset('css/jodit.min.css') }}" rel="stylesheet">

    <!--Theme [ DEMONSTRATION ]-->
    <!-- <link href="{{ asset('css/themes/type-full/theme-dark-full.min.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('css/themes/type-c/theme-navy.min.css') }}" rel="stylesheet">

    <!--Spectrum Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/spectrum.css')}}" rel="stylesheet">

    <!--Custom Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/custom.css')}}" rel="stylesheet">
    <link href="{{ asset('css/new_custom.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />

    @if(Cookie::has('theme'))
        @if(Cookie::get('theme') == 'dark')
            <link href="{{ asset('css/new_custom_dark_theme.css')}}" rel="stylesheet">
        @endif
    @endif

    <style>
        .floating-container .media{
            font-size: 25px;
        }
        @media only screen and (max-width: 600px) {
            .non-content{
                display: none;
            }
        }
    </style>
    @yield('styles')
    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src=" {{asset('js/jquery.min.js') }}"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    <!--active-shop [ RECOMMENDED ]-->
    <script src="{{ asset('js/active-shop.min.js') }}"></script>

    <!--Alerts [ SAMPLE ]-->
    <script src="{{ asset('js/demo/ui-alerts.js') }}"></script>

    <!--Switchery [ OPTIONAL ]-->
    <script src="{{ asset('plugins/switchery/switchery.min.js')}}"></script>

    <!--DataTables [ OPTIONAL ]-->
    <script src="{{ asset('plugins/datatables/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('plugins/datatables/media/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>

    <!--DataTables Sample [ SAMPLE ]-->
    <script src="{{ asset('js/demo/tables-datatables.js')}}"></script>

    <!--Select2 [ OPTIONAL ]-->
    <script src="{{ asset('plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js')}}"></script>

    <!--Summernote [ OPTIONAL ]-->
    <script src="{{ asset('js/jodit.min.js') }}"></script>

    <!--Bootstrap Tags Input [ OPTIONAL ]-->
    <script src="{{ asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>

    <!--Bootstrap Validator [ OPTIONAL ]-->
    <script src="{{ asset('plugins/bootstrap-validator/bootstrapValidator.min.js') }}"></script>

    <!--Bootstrap Wizard [ OPTIONAL ]-->
    <script src="{{ asset('plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>

    <!--Bootstrap Datepicker [ OPTIONAL ]-->
    <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <!--Form Component [ SAMPLE ]-->
    <script src="{{asset('js/demo/form-wizard.js')}}"></script>

    <!--Spectrum JavaScript [ REQUIRED ]-->
    <script src="{{ asset('js/spectrum.js')}}"></script>

    <!--Spartan Image JavaScript [ REQUIRED ]-->
    <script src="{{ asset('js/spartan-multi-image-picker-min.js') }}"></script>

    <!--Custom JavaScript [ REQUIRED ]-->
    <script src="{{ asset('js/custom.js')}}"></script>

    <script>


        function showFrontendAlert(type, message){
            if(type == 'danger'){
                type = 'error';
            }
            swal({
                position: 'top-end',
                type: type,
                title: message,
                showConfirmButton: false,
                timer: 3000
            });
        }
    </script>

    <script type="text/javascript">


        function show_logs(model ,subject_id ,view){
            $.post('{{ route('receipts.logs') }}', {_token:'{{ csrf_token() }}' ,model:model ,subject_id:subject_id ,view:view}, function(data){
                $('#logs .modal-body').html(null);
                $('#logs').modal('show');
                $('#logs .modal-body').html(data);
            });
        }

            $(document).ready(function(){
                $('#date').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#date_span').html('( ' + data + ' )');
                    });

                });
                $('#start_date').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date_time') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#span_date_start').html('( ' + data + ' )');
                    });

                });
                $('#end_date').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date_time') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#span_date_end').html('( ' + data + ' )');
                    });

                });

                $('#date_of_receiving_order').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#date_of_receiving_order_text').val(data);
                    });

                });


                $('#deliver_date').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#deliver_date_text').val(data);
                    });

                });

                $('#from_date').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#from_date_text').val(data);
                    });

                });

                $('#to_date').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#to_date_text').val(data);
                    });

                });

                $('#from_date_time').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date_time') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#from_date_time_text').val(data);
                    });

                });

                $('#to_date_time').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date_time') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#to_date_time_text').val(data);
                    });

                });

                $('#start_date').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date_time') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#start_date_text').val(data);
                    });

                });

                $('#end_date').on('change', function() {
                    var val = $(this).val();
                    $.post('{{ route('get_date_time') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                        $('#end_date_text').val(data);
                    });

                });

                //$('div.alert').not('.alert-important').delay(3000).fadeOut(350);
                if($('.active-link').parent().parent().parent().is('ul')){
                    $('.active-link').parent().parent().addClass('in');
                    $('.active-link').parent().parent().parent().addClass('in');
                }
                if($('.active-link').parent().parent().is('li')){
                    $('.active-link').parent().parent().addClass('active-sub');
                }
                if($('.active-link').parent().is('ul')){
                    $('.active-link').parent().addClass('in');
                }

                if ($('#lang-change').length > 0) {
                    $('#lang-change .dropdown-item a').each(function() {
                        $(this).on('click', function(e){
                            e.preventDefault();
                            var $this = $(this);
                            var locale = $this.data('flag');
                            $.post('{{ route('language.change') }}',{_token:'{{ csrf_token() }}', locale:locale}, function(data){
                                location.reload();
                            });

                        });
                    });
                }

                //perevent submittig multiple times
                $("body").on("submit", "form", function() {
                    $(this).submit(function() {
                        return false;
                    });
                    return true;
                });
            });

        function notification_seen(id){
            $.post('{{ route('alert.seen') }}', {_token:'{{ @csrf_token() }}',id:id}, function(data){

            });
        }
    </script>

    {{-- <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JEPZBJPH52"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-JEPZBJPH52');
    </script>  --}}
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KNZ4MQG');</script>
    <!-- End Google Tag Manager -->
</head>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KNZ4MQG"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- LogsModal -->
    <div class="modal fade bd-example-modal-lg" id="logs" tabindex="-1" aria-labelledby="logsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logsLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    @foreach (session('flash_notification', collect())->toArray() as $message)
        <script type="text/javascript">
            $(document).on('nifty.ready', function() {
                showAlert('{{ $message['level'] }}', '{{ $message['message'] }}');
            });
        </script>
    @endforeach

    @php
        $general_settings = \App\Models\GeneralSetting::first();
        if($general_settings->admin_sidenav != null){
            $admin_sidenav = $general_settings->admin_sidenav;
        }else{
            $admin_sidenav = 'sm';
        }

    @endphp

    <div id="container" class="effect aside-float aside-bright mainnav-{{$admin_sidenav}}">

            @include('inc.admin_nav')

            <div class="boxed">

                <!--CONTENT CONTAINER-->
                <!--===================================================-->
                <div id="content-container">
                    <div id="page-content">

                        @yield('content')

                    </div>
                </div>
            </div>

            @include('inc.admin_sidenav')

            @include('inc.admin_footer')

            @include('partials.modal')

    </div>

        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script type="text/javascript">

            // Enable pusher logging - don't include this in production
            //Pusher.logToConsole = true;

            var pusher = new Pusher('5fe57ffd2f40d8e4fbb0', {
                cluster: 'eu'
            });

            // var channel = pusher.subscribe('my-channel');
            // channel.bind('my-event', function(data) {
            //     alert(JSON.stringify(data));
            // });
        </script>
        <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
        <script>
            @if(!Cookie::has('device_token'))
                $(document).ready(function(){
                    initFirebaseMessagingRegistration();
                });
            @endif

            var firebaseConfig = {
                apiKey: "AIzaSyDQPcBmACU5DEKYk5tYsrjXZvSXpquU8K0",
                authDomain: "ertgal-82937.firebaseapp.com",
                projectId: "ertgal-82937",
                storageBucket: "ertgal-82937.appspot.com",
                messagingSenderId: "472298756042",
                appId: "1:472298756042:web:8482b91ef945698b23a133",
                measurementId: "G-K4J3VX5JDH"
            };
            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();
            function initFirebaseMessagingRegistration() {
                    messaging
                    .requestPermission()
                    .then(function () {
                        return messaging.getToken()
                    })
                    .then(function(token) {
                        console.log(token);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '{{ route("save-token") }}',
                            type: 'POST',
                            data: {
                                token: token
                            },
                            dataType: 'JSON',
                            success: function (response) {
                                console.log('Token saved successfully.');
                            },
                            error: function (err) {
                                console.log('User Chat Token Error'+ err);
                            },
                        });
                    }).catch(function (err) {
                        console.log('User Chat Token Error'+ err);
                    });
            }
            messaging.onMessage(function(payload) {
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(noteTitle, noteOptions);
            });
        </script>

        <script>
            function searchByPhone(e){
                $.post('{{ route('search_by_phone') }}', {_token:'{{ csrf_token() }}',phone:e.value}, function(data){
                    $('#exampleModal .modal-footer').html(data);
                });
            }
        </script>
        @yield('script')
</body>
</html>
