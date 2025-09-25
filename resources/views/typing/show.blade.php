@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2>Typing Test Result</h2>
                    <p><strong>Gross WPM:</strong> {{ number_format($grossWPM, 2) }}</p>
                    <p><strong>Accuracy:</strong> {{ number_format($accuracy, 2) }}%</p>
                    <p><strong>Net Speed WPM:</strong> {{ number_format($netWPM, 2) }}</p>
                    <p><strong>Status:</strong>
                        <span class="fw-bold {{ $status == 'Passed' ? 'text-success' : 'text-danger' }}">
                        {{ $status }}
                        </span>
                    </p>

                </div>

            </div>
        </div>
    </section>
@stop
