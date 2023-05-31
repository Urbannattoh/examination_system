@extends('layout/layout-common')

@section('space-work')

<div class="container">
    <div class="text-center">
    <h2>Thanks for submitting your Exam, {{ Auth::user()->name }}</h2>
    <p>We will review your Exam and Update you soon by Email</p>
    <a href="/dashboard" class="btn btn-info">Go Back</a>
    </div>
</div>
@endsection