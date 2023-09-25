@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
    <main id="main-container">
        <div class="bg-image overflow-hidden"
            style="background-image: url({{ asset('media/photos/photo3@2x.jpg') }});">
            <div class="bg-primary-dark-op">
                <div class="content content-full">
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mt-5 mb-2 text-center text-sm-start">
                        <div class="flex-grow-1">
                            <h1 class="fw-semibold text-white mb-0">Dashboard</h1>
                            <h2 class="h4 fw-normal text-white-75 mb-0">Selamat Datang, {{ Auth::user()->nama }} !</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection