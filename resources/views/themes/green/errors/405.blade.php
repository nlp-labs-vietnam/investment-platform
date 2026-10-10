@extends(template().'layouts.error')
@section('title','405')

@section('error_code','405')
@section('error_message', trans("Method Not Allowed"))

@section('error_image')
    <img src="{{ asset(config('filelocation.error')) }}" alt="">
@endsection
