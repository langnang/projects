@extends('layouts.master')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-9">
        @isset($content)
          <div class="jumbotron mt-2">
            <h1 class="display-4">Hello, world!</h1>
            <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to
              featured content or information.</p>
            <hr class="my-4">
            <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
            <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
          </div>
          <article>
            @if (is_string($content->text))
              {!! markdown_to_html($content->text) !!}
            @else
              {{ json_encode($content->text, JSON_UNESCAPED_UNICODE) }}
            @endif
          </article>
        @endempty
      </div>
      <aside class="col-3">
        @include('shared.master.main-aside')
      </aside>
    </div>
  </div>
@endsection
