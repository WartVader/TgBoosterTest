<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tg booster test app</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/js/app.js'])
</head>
<body>
  <div id="app"></div>
</body>
</html>
