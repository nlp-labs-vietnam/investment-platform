@extends(template().'layouts.error')
@section('title','500')

@section('error_code','500')
@section('error_message', trans("Internal server error, Please contact the server administrator"))

@section('error_image')
    <img src="{{ asset(config('filelocation.error')) }}" alt="">
@endsection

