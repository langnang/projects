@extends('layouts.master')

@section('content')
  <div class="container">
    <main class="row">
      <div class="col-12 col-lg-9">
        @empty($contents['posts'])
        @else
          @foreach ($contents['posts'] as $content)
            <div class="card my-2">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <span class="badge badge-secondary">{{ $content->status }}</span>
                  {{ $content->title }}
                </h5>
              </div>
              <div class="card-body px-3 py-2">
                {!! markdown_to_html($content->text) !!}
                <small class="alert alert-info d-block text-center py-1 mb-0" role="alert">
                  阅读更多
                </small>
              </div>
              <div class="card-footer py-2 small">
                <a href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'content/' . ($content->id ?? $content->slug)) }}" class="btn btn-sm btn-primary"><small>Go somewhere</small></a>
                {{ $content->updated_at }}
              </div>
            </div>
          @endforeach
          {{ $contents['posts']->links() }}
        @endempty

      </div>
      <aside class="col-3 d-md-none d-lg-block">
        @include('shared.master.main-aside')
      </aside>
    </main>

    @isset($links['sites'])
      <main-links class="row">
        <div class="col">
          <div class="card">
            <div class="card-header">
              <h6 class="card-title mb-0">关联链接</h6>
            </div>
            <div class="card-body py-1">
              <ul class="list-inline row row-cols-1 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 mb-0">
                @foreach ($links['sites'] as $link)
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
