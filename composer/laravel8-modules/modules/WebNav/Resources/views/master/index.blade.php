@extends('layouts.master', ['hideFooter' => true])

@section('content')
  <div class="container-fluid px-2">
    <div class="row">
      <nav class="col-lg-2 d-md-none d-lg-block bg-light sidebar collapse">
        <div class="sidebar-sticky pt-2">
          <ul class="nav flex-column">
            @include('webnav::shared.master.nav-item', ['metas' => $metas ?? []])
          </ul>

          <h6 class="sidebar-heading d-none justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Saved reports</span>
            <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
              <span data-feather="plus-circle"></span>
            </a>
          </h6>
          <ul class="nav flex-column mb-2 d-none">
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
        <div class="" style="min-height: calc(100vh - 156px)">

          @foreach ($metas ?? [] as $meta)
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2 border-bottom">
              <h1 class="h4" id="{{ $meta->mid ?? $meta->slug }}"><i class="bi bi-tag"></i> {{ $meta->name }}</h1>
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
        </div>
        @include('shared.master.footer')
      </main>

    </div>
  </div>
@endsection

@push('scripts')
  {{-- <script crossorigin="anonymous" src="https://unpkg.com/feather-icons@4.29.2/dist/feather.min.js"></script> --}}
  {{-- <script crossorigin="anonymous" src="https://unpkg.com/chart.js@4.4.7/dist/chart.umd.js"></script> --}}
@endpush
