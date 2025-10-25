@extends('layouts.app')
@section('content')
<div style="max-width:720px;margin:2rem auto;">
  <h1>Configurar URL do Tocador</h1>
  @if(session('success'))
    <div style="background:#064e3b;color:#bbf7d0;padding:.75rem 1rem;border-radius:.5rem;margin:.75rem 0;">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div style="background:#7f1d1d;color:#fecaca;padding:.75rem 1rem;border-radius:.5rem;margin:.75rem 0;">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('admin.radio.update') }}">
    @csrf
    @method('PUT')
    <label for="url" style="display:block;margin:.5rem 0 .25rem;">URL do stream</label>
    <input id="url" type="url" name="url" value="{{ old('url', $url) }}" required style="width:100%;padding:.75rem;border-radius:.6rem;border:1px solid #334155;background:#0b1220;color:#e2e8f0">
    <button type="submit" style="margin-top:1rem;padding:.8rem 1.2rem;border:0;border-radius:.6rem;background:#2563eb;color:#fff;font-weight:600;">Salvar</button>
  </form>
</div>
@endsection