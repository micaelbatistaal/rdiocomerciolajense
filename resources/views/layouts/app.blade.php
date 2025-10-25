<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #0f172a; color: #fff; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
