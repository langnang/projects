<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top border-bottom py-1 px-2">
  <div class="col-md-12 col-lg-2 text-center">
    <a class="navbar-brand" href="{{ env('APP_URL') }}" style="padding: 0;">
      <img src="{{ env('APP_URL') }}favicon.ico" width="30" height="30" class="d-inline-block align-top" alt="">
      {{ env('APP_NAME') }}
    </a>
    <button class="navbar-toggler float-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
  <div class="col-md-0 col-lg-10">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="{{ env('APP_URL') }}{{ $module['alias'] }}">{{ $module['name'] }}{{ $module['nameCn'] ? '（' . $module['nameCn'] . '）' : null }}
            <span class="sr-only">(current)</span></a>
        </li>
        @foreach ($module['navbar'] ?? [] as $navbar)
          @if (sizeof($navbar['children'] ?? []) > 0)
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                Dropdown
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link" href="{{ env('APP_URL') }}{{ $module['slug'] }}/{{ $navbar['path'] }}">{{ $navbar['nameCn'] ?? $navbar['name'] }}</a>
            </li>
          @endif
        @endforeach
        @auth
          <li class="nav-item">
            <a class="nav-link" href="#">
              <i class="bi bi-plus-circle" style="font-size: 21px;"></i>
            </a>
          </li>
        @endauth
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <div class="form-group">
          <div class="input-group input-group-sm mb-0">
            <input class="form-control" name="title" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button">Search</button>
            </div>
          </div>
        </div>
      </form>
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item d-none">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown d-none">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item d-none">
          <a class="nav-link disabled">Disabled</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
            Modules
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            @foreach (Module::all() ?? [] as $moduleName => $module)
              @if (Module::isEnabled($moduleName))
                <a class="dropdown-item" href="{{ env('APP_URL') }}{{ $module->getLowerName() ?? strtolower($moduleName) }}">{{ $moduleName }}（{{ config($module->getLowerName() . '.nameCn') }}）</a>
              @endif
            @endforeach
          </div>
        </li>
        @if (Auth::check())
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item px-2" href="#">
                <i class="bi bi-plus-circle"></i> Create Meta
              </a>
              <a class="dropdown-item px-2" href="#">
                <i class="bi bi-plus-circle"></i> Create Content
              </a>
              <a class="dropdown-item px-2" href="#">
                <i class="bi bi-plus-circle"></i> Create Link
              </a>
              <a class="dropdown-item px-2" href="#">
                <i class="bi bi-plus-circle"></i> Import
              </a>
              <a class="dropdown-item px-2" href="#">
                <i class="bi bi-plus-circle"></i> Export
              </a>
              <a class="dropdown-item px-2" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="bi bi-asterisk"></i> {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>

            </div>
          </li>
        @else
          @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
          @endif

          @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
          @endif
        @endif
      </ul>
    </div>
  </div>
</nav>
