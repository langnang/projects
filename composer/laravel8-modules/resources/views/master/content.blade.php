@extends('layouts.master')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-9">
        @isset($content)
          <div class="jumbotron mt-2">
            <h3 class="display-6">{{ $content->title }}</h3>
            <p class="lead">{{ $content->subtitle }}</p>
            <hr class="my-2">
            <p>{{ $content->description }}</p>
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
