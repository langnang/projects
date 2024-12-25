@extends('layouts.master')

@section('content')
  <div class="container">
    <main class="row">
      <div class="col-12 col-lg-9">
        @empty($contents)
        @else
          @foreach ($contents ?? [] as $content)
            <div class="card my-2">
              <div class="card-header d-flex align-items-center">
                <img src="{{ $content->ico }}" alt="" height="24px">
                <h5 class="card-title mb-0"> {{ $content->title }}</h5>
              </div>
              <div class="card-body px-3 py-2 h5 mb-0">
                @foreach ($content['text']['groups'] as $groupKey => $group)
                  <a href="{{ asset('./webhunt/content/' . $content->id . '-' . $groupKey) }}" class="badge badge-dark">{{ $group['name'] }}</a>
                @endforeach
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
        @endempty

      </div>
      <aside class="col-3 d-md-none d-lg-block">
        @include('shared.master.main-aside')
      </aside>
      <div class="col-12">
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
            <li class="page-item disabled">
              <a class="page-link">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>

      </div>
    </main>
  </div>
@endsection
