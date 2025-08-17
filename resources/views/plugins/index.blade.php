@extends('layouts.app')
@section('content')
  <h2 class="mb-4">Plugins</h2>
  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

  <form action="{{ route('plugins.upload') }}" method="post" enctype="multipart/form-data" class="mb-4">
    @csrf
    <div class="input-group" style="max-width:480px;">
      <input type="file" name="zip" class="form-control" required>
      <button class="btn btn-primary">Upload Plugin</button>
    </div>
    <div class="form-text">Upload module ZIPs whose root folder is the StudlyCase module name (e.g., <code>Form/...</code>)</div>
  </form>

  <div class="row">
    @foreach($modules as $m)
      <div class="col-md-4">
        <div class="card mb-3 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">{{ $m->getName() }}</h5>
            <p class="mb-2">
              Status:
              <span class="badge {{ $m->isEnabled() ? 'bg-success' : 'bg-secondary' }}">
                {{ $m->isEnabled() ? 'Enabled' : 'Disabled' }}
              </span>
            </p>
            <div class="d-flex gap-2">
              <form method="post" action="{{ $m->isEnabled() ? route('plugins.disable',$m->getName()) : route('plugins.enable',$m->getName()) }}">
                @csrf
                <button class="btn btn-{{ $m->isEnabled() ? 'warning' : 'success' }}">
                  {{ $m->isEnabled() ? 'Disable' : 'Enable' }}
                </button>
              </form>
              <form method="post" action="{{ route('plugins.delete',$m->getName()) }}" onsubmit="return confirm('Delete this module folder?');">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection