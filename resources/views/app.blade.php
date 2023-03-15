<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sistema de crédito</title>
    <link href="{{ url('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('front/css/index.css') }}" rel="stylesheet">
    <script>
      const appUrl = "{{ env('APP_URL') }}"
    </script>
  </head>
  <body class="bg-light">
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
      <div class="container-fluid">
        <h3 class="navbar-brand">Crédito pessoal</h3>

        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Pessoas</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <main class="container">
      @yield('content')
    </main>
    <footer class="footer mt-auto py-5 bg-light">
      <div class="container">
        <span class="text-muted">Sistema de crédito pessoal Gosat.</span>
      </div>
    </footer>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 999999999999999">
      <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true"></div>
    </div>
    
    <script src="{{ url('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('front/js/index.js') }}"></script>
    @yield('scripts')
  </body>
</html>
