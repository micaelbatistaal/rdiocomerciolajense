@extends('layouts.app')
@section('content')
@php
$radioUrl = optional(\App\Models\Setting::where('key','radio_stream_url')->first())->value;
$banners = \App\Models\Banner::orderByDesc('id')->get();
@endphp
<div style="padding:2rem;max-width:1100px;margin:0 auto;">

<div style="width:100%;  padding: 1rem; text-align: center;">
<img src="/logo-radio.png" alt="" width="100px">



</div>






<div style="width:100%;  padding: 1rem; text-align: right;">

<form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"
              style="padding:.5rem 1rem;border:0;border-radius:.5rem;background:#dc2626;color:#fff;font-weight:600;cursor:pointer;">
        Sair
      </button>
    </form>

</div>






<div style="background:#000; padding:1.25rem;border-radius:1rem;margin-top:1rem; ">
<h2 style="margin-top:0;">Conf. Player</h2>
<form method="POST" action="{{ route('admin.radio.update') }}">
@csrf
@method('PUT')
<label for="url" style="display:block;margin:.25rem 0;">URL do Streaming</label>
<input id="url" type="url" name="url"
value="{{ old('url', $radioUrl) }}"
required
style="width:100%;padding:.75rem;border-radius:.6rem;border:1px solid #334155;background:#0b1220;color:#e2e8f0">
<button type="submit"
style="margin-top:.75rem;padding:.8rem 1.2rem;border:0;border-radius:.6rem;background:#2563eb;color:#fff;font-weight:600;">
Salvar
</button>
</form>
</div>


<div style="background:#111827;padding:1.25rem;border-radius:1rem;margin-top:1rem;">
<h2 style="margin-top:0;">Banners 720×90</h2>
<form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
@csrf
<label>Enviar imagem (720×90)</label>
<input type="file" name="banners[]" multiple accept="image/*" required>
<label>Link (opcional)</label>
<input type="url" name="link_url" placeholder="https://exemplo.com" style="width:100%;padding:.5rem;margin:.25rem 0;border-radius:.4rem;border:1px solid #334155;background:#0b1220;color:#e2e8f0">
<button type="submit" style="margin-top:.75rem;padding:.6rem 1rem;border:0;border-radius:.6rem;background:#22c55e;color:#fff;font-weight:600;">Enviar</button>
</form>


<ul style="margin-top:1rem;padding-left:1rem;">
@forelse($banners as $b)
<li style="margin:.5rem 0;">
<img src="{{ asset('storage/'.$b->image_path) }}" alt="banner" style="max-width:360px;height:auto;border-radius:.25rem;">
{!! $b->link_url ? '<div>Link: '.$b->link_url.'</div>' : '' !!}
<div>Status: {{ $b->active ? 'Ativo' : 'Inativo' }}</div>
<form method="POST" action="{{ route('admin.banners.toggle', $b->id) }}" style="display:inline;">
@csrf @method('PUT')
<button style="background:#2563eb;border:0;border-radius:.4rem;color:#fff;padding:.25rem .75rem;margin-top:.25rem;">{{ $b->active ? 'Desativar' : 'Ativar' }}</button>
</form>
<form method="POST" action="{{ route('admin.banners.destroy', $b->id) }}" style="display:inline;">
@csrf @method('DELETE')
<button style="background:#dc2626;border:0;border-radius:.4rem;color:#fff;padding:.25rem .75rem;margin-top:.25rem;">Excluir</button>
</form>
</li>
@empty
<li>Nenhum banner enviado ainda.</li>
@endforelse
</ul>
</div>
</div>
@endsection