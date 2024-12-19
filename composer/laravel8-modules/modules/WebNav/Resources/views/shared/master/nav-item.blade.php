@foreach ($metas ?? [] as $meta)
  <li class="nav-item" style="width: 100%;">
    @if (sizeof($meta->children) == 0)
      <a class="nav-link d-flex flex-row py-1 pr-2" href="#{{ $meta->mid ?? $meta->slug }}">
        <i class="bi bi-folder-fill mr-1"></i>
        <span class="flex-grow-1 text-truncate">{{ $meta->name }}</span>
        @auth
          <button type="button" class="btn btn-outline-primary btn-sm p-1 px-0 mr-1" data-toggle="modal" data-target="#{{ $module }}-meta-item-modal" data-method="insert_meta_item" data-mids="{{ $mids . '-' . $meta->mid }}"><i class="bi bi-plus"></i></button>
          <button type="button" class="btn btn-outline-warning btn-sm p-1 px-0 mr-1" data-toggle="modal" data-target="#{{ $module }}-meta-item-modal" data-method="update_meta_item" data-mids="{{ $mids . '-' . $meta->mid }}"><i class="bi bi-pen"></i></button>
          <button type="button" class="btn btn-outline-danger btn-sm p-1 px-0 mr-1" data-toggle="modal" data-target="#{{ $module }}-meta-item-modal" data-method="delete_meta_item" data-mids="{{ $mids . '-' . $meta->mid }}"><i class="bi bi-trash"></i></button>
        @endauth
      </a>
    @else
      <a class="nav-link d-flex flex-row py-1 pr-2" role="button" data-toggle="collapse" data-target="#collapse-{{ $meta->mid ?? $meta->slug }}" aria-expanded="false" aria-controls="collapse-{{ $mids . '-' . $meta->mid ?? $meta->slug }}" href="#{{ $meta->mid ?? $meta->slug }}">
        <i class="bi bi-folder-fill mr-1"></i>
        <span class="flex-grow-1 text-truncate">{{ $meta->name }}</span>
        @auth
          <button type="button" class="btn btn-outline-primary btn-sm p-1 px-0 mr-1" data-toggle="modal" data-target="#{{ $module }}-meta-item-modal" data-method="insert_meta_item" data-mids="{{ $mids . '-' . $meta->mid }}"><i class="bi bi-plus"></i></button>
          <button type="button" class="btn btn-outline-warning btn-sm p-1 px-0 mr-1" data-toggle="modal" data-target="#{{ $module }}-meta-item-modal" data-method="update_meta_item" data-mids="{{ $mids . '-' . $meta->mid }}"><i class="bi bi-pen"></i></button>
        @endauth
        <i class="bi bi-caret-right-fill"></i>
      </a>
      <ul class="nav flex-column collapse" id="collapse-{{ $meta->mid ?? $meta->slug }}">
        @include('webnav::shared.master.nav-item', ['module' => $module, 'metas' => $meta->children ?? [], 'mids' => $mids . '-' . $meta->mid])
      </ul>
    @endif
  </li>
@endforeach
