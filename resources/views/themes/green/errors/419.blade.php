@extends(template().'layouts.error')
@section('title','419')

@section('error_code','419')
@section('error_message', trans("Your session has expired"))

@section('error_image')
    <img src="{{ asset(config('filelocation.error')) }}" alt="">
@endsection

