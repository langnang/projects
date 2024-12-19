@extends('layouts.master')

@section('content')
  <div class="container">
    <main class="row">
      <div class="col-12 col-lg-9">
        @empty($contents['paginator'])
        @else
          @foreach ($contents['paginator'] ?? [] as $content)
            <div class="card my-2">
              <div class="card-header">
                <h5 class="card-title mb-0">{{ $content->title }}</h5>
              </div>
              <div class="card-body px-3 py-2 h5 mb-0">
                @foreach ($content['text']['groups'] as $groupKey => $group)
                  <a href="{{ asset('./webhunt/content/' . $content->id . '-' . $groupKey) }}" class="badge badge-dark">{{ $group['name'] }}</a>
                @endforeach
              </div>
              <div class="card-footer py-2 small">
                <a href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'content/' . ($content->id ?? $content->slug)) }}" class="btn btn-sm btn-primary"><small>Go somewhere</small></a>
                {{ $content->updated_at }}
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
