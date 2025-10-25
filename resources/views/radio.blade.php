<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Rádio ao vivo</title>
  <style>
    :root{
      --bg:#004AAD;
      --card:#111827;
      --text:#e5e7eb;
      --muted:#cbd5e1;
      --accent:#22c55e;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; background:var(--bg);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,'Helvetica Neue',Arial;
      color:var(--text);
    }

    @media only screen and (max-width: 600px) {
      .ads{width: 100%;}
    }

    .radio-wrap{ display:flex; align-items:center; justify-content:center; padding:32px; }
    .radio-card{
      width:100%; max-width:800px; background:var(--card); border-radius:24px; padding:28px;
      box-shadow:0 20px 60px rgba(0,0,0,.35); text-align:center; border:1px solid rgba(255,255,255,.06);
    }
    .radio-logo{ width:180px; height:180px; overflow:hidden; margin:0 auto 18px; display:grid; place-items:center; }
    .radio-logo img{max-width:100%; max-height:100%; object-fit:contain; display:block}
    .title{font-size:1.35rem; font-weight:800; margin:2px 0}
    .subtitle{font-size:.95rem; color:var(--muted); margin:0 0 16px}

    .controls{ display:flex; align-items:center; justify-content:center; gap:16px; margin-top:8px }
    .play-btn{
      width:50px; height:50px; border-radius:50%; border:none; cursor:pointer;
      background:#b1c522; color:#062c17; display:grid; place-items:center;
      box-shadow:0 10px 30px rgba(212,219,29,.35);
      transition:transform .12s ease, filter .2s ease;
    }
    .play-btn:hover{ transform:translateY(-1px); filter:brightness(1.03) }
    .play-btn:active{ transform:translateY(0) scale(.98) }
    .play-btn svg{ width:28px; height:28px }
    .volume{ display:flex; align-items:center; gap:10px; width:55%; min-width:220px; max-width:340px }
    .volume input[type="range"]{ width:100% }
    .status{ margin-top:12px; color:var(--muted); font-size:.92rem }
    .badge{ display:inline-block; padding:6px 10px; border-radius:999px; font-size:.75rem;
            background:rgba(255,255,255,.06); color:var(--muted); margin-top:12px; border:1px solid rgba(255,255,255,.08)}
    @media (max-width:560px){ .radio-logo{ width:140px; height:140px } }
    .pulsing{ box-shadow:0 0 0 0 rgba(34,197,94,.45); animation:pulse 1.6s infinite }
    @keyframes pulse{ 0%{ box-shadow:0 0 0 0 rgba(34,197,94,.45) } 70%{ box-shadow:0 0 0 22px rgba(34,197,94,0) } 100%{ box-shadow:0 0 0 0 rgba(34,197,94,0) } }
  </style>
</head>
<body>

@php
  // Carrega a URL do stream e um banner ativo aleatório
  $radioUrl = optional(\App\Models\Setting::where('key','radio_stream_url')->first())->value;
  $banner   = \App\Models\Banner::where('active', true)->inRandomOrder()->first();
@endphp

<div class="radio-wrap">
  <div class="radio-card" role="region" aria-label="Player da rádio">

    {{-- Banner 720x90 (aleatório a cada reload) --}}
    @if($banner)
      @php($src = asset('storage/'.$banner->image_path))
      <div style="margin-bottom:18px;">
        @if($banner->link_url)
          <a href="{{ $banner->link_url }}" target="_blank" rel="noopener">
            <img class="ads" src="{{ $src }}" width="720" height="90" alt="Publicidade">
          </a>
        @else
          <img class="ads" src="{{ $src }}" width="720" height="90" alt="Publicidade">
        @endif
      </div>
    @endif

    <div class="radio-logo" aria-hidden="true">
      <img src="/logo-radio.png" alt="Logo da Rádio" />
    </div>

    <h1 class="title">Rádio ao vivo</h1>
    <p class="subtitle">Clique em tocar para começar</p>

    <div class="controls" aria-label="Controles do player">
      <button id="playPauseBtn" class="play-btn" aria-pressed="false" aria-label="Tocar">
        <!-- Play -->
        <svg id="iconPlay" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"></path></svg>
        <!-- Pause -->
        <svg id="iconPause" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M6 5h4v14H6zM14 5h4v14h-4z"></path></svg>
      </button>

      <div class="volume">
        <label for="vol" style="color:var(--muted); font-size:.85rem;">Volume</label>
        <input id="vol" type="range" min="0" max="1" step="0.01" value="0.9" aria-label="Volume" />
      </div>
    </div>

    <div class="status" id="statusText">
      {{ $radioUrl ? 'Pronto para tocar' : 'URL do stream não configurada' }}
    </div>
    <div class="badge">Streaming: ao vivo</div>

    {{-- Player usa a URL salva no painel (se não houver, fica sem src) --}}
    <audio id="radioAudio" preload="none" crossorigin="anonymous" @if($radioUrl) src="{{ $radioUrl }}" @endif></audio>
  </div>
</div>

<script>
(function(){
  const audio = document.getElementById('radioAudio');
  const btn   = document.getElementById('playPauseBtn');
  const iconPlay  = document.getElementById('iconPlay');
  const iconPause = document.getElementById('iconPause');
  const statusText = document.getElementById('statusText');
  const vol = document.getElementById('vol');

  function setStatus(msg){ statusText.textContent = msg; }

  // se a URL não está configurada, desativa o botão
  if (!audio.getAttribute('src')) {
    btn.disabled = true;
    btn.style.opacity = .6;
    setStatus('URL do stream não configurada.');
    return;
  }

  // Guarda volume entre visitas
  try {
    const saved = localStorage.getItem('radioVolume');
    if(saved !== null){ audio.volume = vol.value = parseFloat(saved); }
  } catch(e){}

  async function togglePlay(){
    if(audio.paused){
      try{
        setStatus('Conectando…'); btn.classList.add('pulsing');
        await audio.play();
        btn.setAttribute('aria-pressed','true');
        iconPlay.style.display='none'; iconPause.style.display='block';
        btn.setAttribute('aria-label','Pausar'); setStatus('Tocando ✓');
      }catch(err){
        btn.classList.remove('pulsing');
        setStatus('Não foi possível iniciar o áudio. Toque novamente.');
      }
    }else{
      audio.pause();
      btn.setAttribute('aria-pressed','false');
      iconPlay.style.display='block'; iconPause.style.display='none';
      btn.setAttribute('aria-label','Tocar');
      btn.classList.remove('pulsing'); setStatus('Pausado');
    }
  }

  btn.addEventListener('click', togglePlay);
  vol.addEventListener('input', (e)=>{
    audio.volume = parseFloat(e.target.value);
    try{ localStorage.setItem('radioVolume', audio.volume); }catch(e){}
  });

  audio.addEventListener('playing',  ()=> setStatus('Tocando ✓'));
  audio.addEventListener('pause',    ()=> setStatus('Pausado'));
  audio.addEventListener('waiting',  ()=> setStatus('Reconectando…'));
  audio.addEventListener('stalled',  ()=> setStatus('Rede instável, tentando…'));
  audio.addEventListener('error',    ()=> setStatus('Erro no streaming (verifique a URL).'));
})();
</script>
</body>
</html>
