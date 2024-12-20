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
                <p class="card-text">{{ markdown_to_html($content->text) }}</p>
              </div>
              <div class="card-footer py-2 small">
                <a href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'content/' . ($content->id ?? $content->slug)) }}" class="btn btn-sm btn-primary"><small>Go somewhere</small></a>
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
  </div>
@endsection
