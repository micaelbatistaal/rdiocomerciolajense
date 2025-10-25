<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <style>
    body{background:#0f172a;color:#e2e8f0;display:grid;place-items:center;min-height:100vh;font-family:Inter,system-ui,sans-serif}
    .card{background:#111827;padding:2rem;border-radius:1rem;width:100%;max-width:380px;box-shadow:0 10px 30px rgba(0,0,0,.35)}
    label{display:block;margin:.75rem 0 .25rem}
    input{width:100%;padding:.75rem;border-radius:.6rem;border:1px solid #334155;background:#0b1220;color:#e2e8f0}
    .btn{width:100%;padding:.8rem;margin-top:1rem;background:#2563eb;color:#fff;border:none;border-radius:.6rem;font-weight:600;cursor:pointer}
    .error{background:#7f1d1d;color:#fecaca;padding:.6rem .75rem;border-radius:.5rem;margin-bottom:.75rem}
  </style>
</head>
<body>
  <div class="card">
    <h2 style="margin:0 0 1rem;font-weight:700">Login</h2>

    @if ($errors->any())
      <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
      @csrf
      <label for="email">E-mail</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

      <label for="password">Senha</label>
      <input id="password" type="password" name="password" required>

      <button class="btn" type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>
