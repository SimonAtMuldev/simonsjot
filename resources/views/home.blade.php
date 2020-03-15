@extends('layouts.app')

@section('content')
    <App id="app" :user="{{ auth()->user() }}"></App>
@endsection
