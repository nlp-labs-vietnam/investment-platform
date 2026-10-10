<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Favicons -->
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">

    <link rel="stylesheet" type="text/css" href="{{asset(template(true).'css/bootstrap.min.css')}}"/>

    <!-- Template Main CSS File -->
    <link rel="stylesheet" type="text/css" href="{{asset(template(true).'css/style.css')}}">
    <script src="{{asset(template(true).'js/fontawesomepro.js')}}"></script>
</head>

<body>


<!-- #main -->
<section class="shop-section error-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-sm-6">
                <div class="error-thum">
                    @hasSection('error_image')
                        @yield('error_image')
                    @else
                        <img src="{{ asset(config('filelocation.error2')) }}" alt="">
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="error-content">
                    <div class="error-title">@yield('error_code')</div>
                    <div class="error-info">@yield('error_message')</div>
                    <div class="d-flex justify-content-left">
                        <div class="btn-area me-3">
                            <a href="{{ url('/') }}" class="btn-custom"> <i
                                    class="fa fa-arrow-left me-2"></i> {{ trans('Home Page') }}</a>
                        </div>

                        <div class="btn-area previousBtn">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script src="{{asset(template(true).'js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset(template(true).'js/jquery.min.js')}}"></script>


<script>
    'use strict'
    $(document).ready(function () {
        let errorCode = $('.error-title').text().trim();
        let previousUrl = @json(url()->previous());
        sessionStorage.setItem('previousUrl', previousUrl);
        if (['404', '405', '403'].includes(errorCode)) {
            let storedPreviousUrl = sessionStorage.getItem('previousUrl');
            let previousBtn = `<a href="${storedPreviousUrl}" class="btn-custom"> <i class="fa fa-arrow-left me-2"></i>Previous Page</a>`;
            $('.previousBtn').html(previousBtn);
        }
    });

</script>

</body>

</html>


