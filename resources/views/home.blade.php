@extends('layouts.app')

@section('content')

  <header class="fill-width ratio ratio--5-2" data-component="banner">
    <img src="/bg_camera.jpg">
    <div class="pos-absolute bg-maroon-transparent fill-width fill-height pos-top pos-left padding-top-xxlarge text-center row">
      <div class="col small-centered small-3-4 medium-2-3 large-1-2 xlarge-1-3">
        <h1 class="head-1 text--white margin-vert-large">A Taste of Cinema History</h1>
        <div class="head-2 text--white">
          <p class="margin-bottom-medium hide-for-medium-below">We are indebted to the early pioneers and innovators of the moving image.</p>
          <p>Welcome to Wintergarten, named after the first public movie theater in history — a place to be inspired by filmmakers from the early days of cinema.</p>
        </div>
      </div>
    </div>
  </header>

   @include('_shared.github')

  <section data-component="collections" class="padding-large">
    @foreach ($collections as $collection)
      <h2 class="margin-left-medium padding-bottom-small head-2 border border--bottom">{{ $collection['name'] }}</h2>
      <ul class="row padding-bottom-large">
        @foreach ($collection['items'] as $item)
        <li class="col small-1-1 medium-1-2 large-1-4 padding-medium" data-component="item">
          <h3 class="head-3 margin-bottom-small truncate">{{ $item['name'] }}</h3>
          <a href="/watch/{{ $item['id'] }}" class="ratio ratio--16-9" {{ !$loggedIn ? 'data-modal-open="join"' : '' }}>
            <img src="{{ $item['thumbnail']['medium'] }}" class="fill-width">
          </a>
          <div class="is-hidden">
            {{ $item['description'] }}
          </div>
        </li>
        @endforeach
      </ul>
    @endforeach
  </section>

@endsection