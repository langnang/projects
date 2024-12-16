@extends('layouts.master')

@section('content')
  <div class="container">
    <main class="row">
      <div class="col-9">
        @empty($contents['paginator'])
        @else
          @foreach ($contents['paginator'] ?? [] as $content)
            <div class="card my-2">
              <div class="card-header">
                <h5 class="card-title mb-0">{{ $content->title }}</h5>
              </div>
              <div class="card-body px-3 py-2">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                  content.</p>
              </div>
              <div class="card-footer py-2">
                <a href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'content/' . $content->cid) }}"
                  class="btn btn-sm btn-primary">Go somewhere</a>
              </div>
            </div>
          @endforeach
        @endempty

      </div>
      <aside class="col-3">
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
