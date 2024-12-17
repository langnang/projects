@extends('layouts.master', ['hideFooter' => true])

@section('content')
  <div class="container-fluid px-2">
    <div class="row">
      <nav id="sidebarMenu" class="col-lg-2 d-md-none d-lg-block bg-light sidebar collapse">
        <div class="sidebar-sticky pt-2">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#">
                <span data-feather="home"></span>Dashboard
                <span class="sr-only">(current)</span>
              </a>
            </li>
            @foreach ($metas ?? [] as $meta)
              <li class="nav-item" style="width: 100%;">
                <a class="nav-link text-truncate py-1" href="#{{ $meta->mid }}">
                  <span data-feather="file"></span>{{ $meta->name }}
                </a>
              </li>
            @endforeach
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file"></span>
                Orders
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="shopping-cart"></span>
                Products
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="users"></span>
                Customers
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="bar-chart-2"></span>
                Reports
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="layers"></span>
                Integrations
              </a>
            </li>
          </ul>

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Saved reports</span>
            <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
              <span data-feather="plus-circle"></span>
            </a>
          </h6>
          <ul class="nav flex-column mb-2">
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Current month
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Last quarter
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Social engagement
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                Year-end sale
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <main role="main" class="ml-sm-auto col-lg-10">
        @foreach ($metas ?? [] as $meta)
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2 border-bottom">
            <h1 class="h4" id="{{ $meta->mid }}"><i class="bi bi-tag"></i> {{ $meta->name }}</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
              </div>
              <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
              </button>
            </div>
          </div>

          <div class="row row-cols-4">
            @foreach ($contents as $content)
              <div class="col my-2 px-2">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title text-truncate" title="{{ $content->title }}">{{ $content->title }}</h5>
                    <p class="card-text text-truncate">{{ $content->description }}</p>
                    <p class="mb-0 flex justify-content-end">
                      <a href="#" class="btn btn-sm btn-outline-primary ml-1">详情</a>
                      <a href="#" class="btn btn-sm btn-outline-primary ml-1">跳转</a>
                    </p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endforeach

        <h2>Section title</h2>
        <div class="table-responsive">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th>#</th>
                <th>Header</th>
                <th>Header</th>
                <th>Header</th>
                <th>Header</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1,001</td>
                <td>random</td>
                <td>data</td>
                <td>placeholder</td>
                <td>text</td>
              </tr>
              <tr>
                <td>1,002</td>
                <td>placeholder</td>
                <td>irrelevant</td>
                <td>visual</td>
                <td>layout</td>
              </tr>
              <tr>
                <td>1,003</td>
                <td>data</td>
                <td>rich</td>
                <td>dashboard</td>
                <td>tabular</td>
              </tr>
              <tr>
                <td>1,003</td>
                <td>information</td>
                <td>placeholder</td>
                <td>illustrative</td>
                <td>data</td>
              </tr>
              <tr>
                <td>1,004</td>
                <td>text</td>
                <td>random</td>
                <td>layout</td>
                <td>dashboard</td>
              </tr>
              <tr>
                <td>1,005</td>
                <td>dashboard</td>
                <td>irrelevant</td>
                <td>text</td>
                <td>placeholder</td>
              </tr>
              <tr>
                <td>1,006</td>
                <td>dashboard</td>
                <td>illustrative</td>
                <td>rich</td>
                <td>data</td>
              </tr>
              <tr>
                <td>1,007</td>
                <td>placeholder</td>
                <td>tabular</td>
                <td>information</td>
                <td>irrelevant</td>
              </tr>
              <tr>
                <td>1,008</td>
                <td>random</td>
                <td>data</td>
                <td>placeholder</td>
                <td>text</td>
              </tr>
              <tr>
                <td>1,009</td>
                <td>placeholder</td>
                <td>irrelevant</td>
                <td>visual</td>
                <td>layout</td>
              </tr>
              <tr>
                <td>1,010</td>
                <td>data</td>
                <td>rich</td>
                <td>dashboard</td>
                <td>tabular</td>
              </tr>
              <tr>
                <td>1,011</td>
                <td>information</td>
                <td>placeholder</td>
                <td>illustrative</td>
                <td>data</td>
              </tr>
              <tr>
                <td>1,012</td>
                <td>text</td>
                <td>placeholder</td>
                <td>layout</td>
                <td>dashboard</td>
              </tr>
              <tr>
                <td>1,013</td>
                <td>dashboard</td>
                <td>irrelevant</td>
                <td>text</td>
                <td>visual</td>
              </tr>
              <tr>
                <td>1,014</td>
                <td>dashboard</td>
                <td>illustrative</td>
                <td>rich</td>
                <td>data</td>
              </tr>
              <tr>
                <td>1,015</td>
                <td>random</td>
                <td>tabular</td>
                <td>information</td>
                <td>text</td>
              </tr>
            </tbody>
          </table>
        </div>
        @include('shared.master.footer')
      </main>

    </div>
  </div>
@endsection

@push('scripts')
  <script crossorigin="anonymous" src="https://unpkg.com/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/feather-icons@4.29.2/dist/feather.min.js"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/chart.js@4.4.7/dist/chart.umd.js"></script>
@endpush
