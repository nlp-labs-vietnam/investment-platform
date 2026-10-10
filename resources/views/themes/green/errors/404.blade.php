@extends(template().'layouts.error')
@section('title','404')

@section('error_code','404')
@section('error_message', trans("We can't seem to find the page you are looking for"))

@section('error_image')
    <img src="{{ asset(config('filelocation.error')) }}" alt="">
@endsection

