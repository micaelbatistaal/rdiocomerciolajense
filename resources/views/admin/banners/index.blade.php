@extends('layouts.app')
@section('content')
<div style="max-width:900px;margin:2rem auto;">
  <h1>Banners 720x90</h1>

  @if(session('success'))
    <div style="background:#064e3b;color:#bbf7d0;padding:.75rem 1rem;border-radius:.5rem;margin:.75rem 0;">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div style="background:#7f1d1d;color:#fecaca;padding:.75rem 1rem;border-radius:.5rem;margin:.75rem 0;">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" style="margin-bottom:2rem;background:#111827;padding:1rem;border-radius:.75rem;">
    @csrf
    <label style="display:block;margin:.25rem 0;">Imagens (múltiplas) — recomendado 720x90</label>
    <input type="file" name="images[]" multiple accept="image/*">

    <label style="display:block;margin:.75rem 0 .25rem;">Link (opcional)</label>
    <input type="url" name="link_url" placeholder="https://..." style="width:100%;padding:.6rem;border-radius:.5rem;border:1px solid #334155;background:#0b1220;color:#e2e8f0">

    <button type="submit" style="margin-top:.75rem;padding:.7rem 1rem;border:0;border-radius:.6rem;background:#2563eb;color:#fff;font-weight:600;">Enviar</button>
  </form>

  <table style="width:100%;border-collapse:collapse;">
    <thead><tr><th style="text-align:left;padding:.5rem;">Preview</th><th>Link</th><th>Status</th><th>Ações</th></tr></thead>
    <tbody>
      @forelse($banners as $b)
        <tr style="border-top:1px solid #334155;">
          <td style="padding:.5rem;">
            <img src="{{ asset('storage/'.$b->image_path) }}" alt="banner" style="max-width:360px;height:auto;">
          </td>
          <td style="text-align:center;">{{ $b->link_url ?? '—' }}</td>
          <td style="text-align:center;">{{ $b->active ? 'Ativo' : 'Inativo' }}</td>
          <td style="text-align:center;">
            <form method="POST" action="{{ route('admin.banners.toggle', $b) }}" style="display:inline-block;">
              @csrf
              <button style="padding:.4rem .7rem;border:0;border-radius:.5rem;background:#475569;color:#fff">Ativar/Inativar</button>
            </form>
            <form method="POST" action="{{ route('admin.banners.destroy', $b) }}" style="display:inline-block;margin-left:.4rem;" onsubmit="return confirm('Excluir banner?');">
              @csrf @method('DELETE')
              <button style="padding:.4rem .7rem;border:0;border-radius:.5rem;background:#7f1d1d;color:#fff">Excluir</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" style="padding:.75rem;">Nenhum banner enviado ainda.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection