@extends('layouts.master')

@section('content')
  <div class="container">
    <main class="row">
      <div class="col-12 col-lg-9">
        @empty($contents)
        @else
          @foreach ($contents as $content)
            <div class="card my-2 @switch($content->status) @case('public') border-secondary @case('publish') border-primary @break @case('protected') border-warning @break @case('private') border-danger @break @default @break @endswitch">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <i class="bi bi-record-fill " data-toggle="tooltip" data-placement="right" title="{{ $content->status }}"></i>
                  {{ $content->title }}
                </h5>
              </div>
              <div class="card-body px-3 py-2">
                <p class="card-text mb-2">{{ $content->description }}</p>

              </div>
              <div class="card-footer py-2 small d-flex align-items-center">
                <span class="px-2">{{ $content->updated_at }}</span>
                <span class="px-2 mr-auto">{{ $content->user }}</span>
                <a href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'content/' . ($content->id ?? $content->slug)) }}" class="alert alert-info py-1 mb-0" role="alert">
                  <small>阅读更多</small>
                </a>
              </div>
            </div>
          @endforeach
          {{ $contents->links() }}
        @endempty

      </div>
      <aside class="col-3 d-md-none d-lg-block">
        @include('shared.master.main-aside')
      </aside>
    </main>

    @isset($links)
      <main-links class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h6 class="card-title mb-0">关联链接</h6>
            </div>
            <div class="card-body py-1">
              <ul class="list-inline row row-cols-1 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 mb-0">
                @foreach ($links as $link)
                  <li class="list-inline-item col mr-0 my-1">
                    <img src="{{ $link->ico }}" alt="" height="20px">
                    <a class="" href="{{ $link->url }}" target="_blank"> <small>{{ $link->title }}</small></a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </main-links>
    @endisset
  </div>
@endsection
