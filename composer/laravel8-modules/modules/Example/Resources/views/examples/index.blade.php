@extends('example::layouts.master')


@section('content')
  <ul>
    @foreach ($Example as $template)
      <li><a href="/Example/{{ basename($template) }}">{{ basename($template) }}</a></li>
    @endforeach
  </ul>
@endsection
