@extends('layouts.master')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-9">
        @isset($link)
          <div class="jumbotron mt-2">
            <h3 class="display-6 d-flex align-items-center">
              <img src="{{ $link->ico }}" class="mr-2" alt="..." style="width: 36px">
              {{ $link->title }}
            </h3>
            <p class="lead">{{ $link->keywords }}</p>
            <hr class="my-2">
            <p>{{ $link->description }}</p>
            <a class="btn btn-primary btn-lg" href="{{ $link->url }}" role="button" target="_blank">Jump</a>
          </div>
          <article>
            @isset($link->text)
              @if (is_string($link->text))
                {!! markdown_to_html($content->text) !!}
              @else
                {{ json_encode($link->text, JSON_UNESCAPED_UNICODE) }}
              @endif
            @endisset
          </article>
        @endempty
      </div>
      <aside class="col-3">
        @include('master.shared.main-aside')
      </aside>
    </div>
  </div>
@endsection
