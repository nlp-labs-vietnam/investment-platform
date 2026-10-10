@extends(template().'layouts.error')
@section('title','403 Forbidden')

@section('error_code','403')
@section('error_message', trans("You don't have permission to access this page"))

@section('error_image')
    <img src="{{ asset(config('filelocation.error')) }}" alt="" class="img-fluid">
@endsection

