@foreach ($metas ?? [] as $meta)
  <li class="nav-item" style="width: 100%;">
    @if (sizeof($meta->children) == 0)
      <a class="nav-link d-flex flex-row py-1 pr-2" href="#{{ $meta->mid ?? $meta->slug }}">
        <i class="bi bi-folder-fill mr-1"></i>
        <span class="flex-grow-1 text-truncate">{{ $meta->name }}</span>
      </a>
    @else
      <a class="nav-link d-flex flex-row py-1 pr-2" role="button" data-toggle="collapse" data-target="#collapse-{{ $meta->mid ?? $meta->slug }}" aria-expanded="false" aria-controls="collapse-{{ $meta->mid ?? $meta->slug }}" href="#{{ $meta->mid ?? $meta->slug }}">
        <i class="bi bi-folder-fill mr-1"></i>
        <span class="flex-grow-1 text-truncate">{{ $meta->name }}</span>
        <i class="bi bi-caret-right-fill"></i>
      </a>
      <ul class="nav flex-column collapse" id="collapse-{{ $meta->mid ?? $meta->slug }}">
        @include('webnav::shared.master.nav-item', ['metas' => $meta->children ?? []])
      </ul>
    @endif
  </li>
@endforeach
