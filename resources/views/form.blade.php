@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Contact Form</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="post" action="{{ route('form.submit') }}">
        @csrf
        <div class="mb-2">
            <input name="name" value="{{ old('name') }}" placeholder="Name" class="form-control">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-2">
            <input name="email" value="{{ old('email') }}" placeholder="Email" class="form-control">
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-2">
            <textarea name="message" placeholder="Message" class="form-control">{{ old('message') }}</textarea>
            @error('message') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <button class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
