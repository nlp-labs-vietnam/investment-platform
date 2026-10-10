@extends(template() . 'layouts.app')
@section('title',trans('Home'))
@section('content')
    {!!  $sectionsData !!}
@endsection
