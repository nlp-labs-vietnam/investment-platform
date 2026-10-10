<!DOCTYPE html>
<html lang="en" @if(session()->get('rtl') == 1) dir="rtl" @endif>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('Payment Failed')</title>
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('assets/themes/light/css/all.min.css') }}">
    <link href="{{asset('assets/global/css/success_faield.css')}}" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}"
          type="image/x-icon">
</head>
<body>
<header class="site-header" id="header">
    <h1 class="site-header__title" data-lead-id="site-header-title">@lang('Sorry')!</h1>
</header>

<div class="main-content">
    <i class="fa fa-times main-content__times" id="checkmark"></i>
    <p class="main-content__body" data-lead-id="main-content-body">
        @lang('We really appreciate you giving us a moment of your time today but unfortunately the payment was unsuccessful due to')
        {{ session('error') ?? __('it seems some issue in server to server communication. Kindly connect with administrator') }}</p>
</div>
<footer class="site-footer" id="footer">
    <a href="{{ url('/') }}">@lang('Go Back to Home')</a>
    <p class="site-footer__fineprint" id="fineprint">@lang('Copyright') @lang('©') {{ date('Y') }} | @lang('All Rights Reserved') <a href="{{ url('/') }}">{{ __(basicControl()->site_title) }}</a></p>
</footer>
</body>
</html>







