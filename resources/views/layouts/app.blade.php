<html>
<head>
  <title>Wintergarten: A Taste of Cinema History</title>
  <link rel="stylesheet" type="text/css" href="/wintergarten.min.css">
  <link rel="shortcut icon" href="/favicon.png" type="image/png">
  <script src="https://use.typekit.net/{{ $config['typekit_id'] }}.js"></script>
  <script>try{Typekit.load({ async: true });}catch(e){}</script>
</head>
<body>

  <div data-component="layout" class="pos-relative overflow-hidden">
    @include('_shared.header')
    @yield('content')
  </div>

  @if ($hasFooter)
    @include('_shared.footer')
  @endif

  <div class="modals">
    @include('modals.join')
    @include('modals.login')
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="/wintergarten.js"></script>
</body>
</html>