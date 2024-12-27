<form class="my-2" action="" method="GET">
  @csrf
  <div class="form-group mb-2">
    <div class="input-group input-group-sm mb-0">
      <input type="text" class="form-control form-control-sm" name="title" placeholder="Title">
      <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button">Search</button>
      </div>
    </div>
  </div>
</form>

@empty($children)
@else
  <div class="card my-2">
    <div class="card-header p-2">
      子类
    </div>
    <ul class="list-group list-group-flush d-flex align-items-center">
      @foreach ($children ?? [] as $meta)
        <a class="list-group-item text-truncate py-1 pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'meta/' . ($meta->id ?? $meta->slug)) }}" title="{{ $meta->name }}">{{ $meta->name }}</a>
        <a href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'update-meta/' . ($meta->id ?? $meta->slug)) }}" class="bi bi-edit"></a>
      @endforeach
    </ul>
  </div>
@endempty
@empty($categories)
@else
  <div class="card my-2">
    <div class="card-header p-2">
      分类
    </div>
    <ul class="list-group list-group-flush" style="max-height: 329px;overflow: auto;">
      @foreach ($categories ?? [] as $category)
        <li class="list-group-item py-1 pr-1 d-flex align-items-center">
          <a class="text-truncate mr-auto" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'meta/' . ($category->id ?? $category->slug)) }}" title="{{ $category->name }}">{{ $category->name }}</a>
          @if (Auth::check())
            <a class="bi ml-1 bi-pen" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'update-meta/' . ($category->id ?? $category->slug)) }}"></a>
          @endif
        </li>
        @foreach ($category['children'] ?? [] as $child)
          <li class="list-group-item py-1 pr-1 d-flex align-items-center">
            <a class="text-truncate mr-auto" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'meta/' . ($child->id ?? $child->slug)) }}" title="{{ $child->name }}">{{ $child->name }}</a>
            @if (Auth::check())
              <a class="bi ml-1 bi-pen" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'update-meta/' . ($child->id ?? $child->slug)) }}"></a>
            @endif
          </li>
        @endforeach
      @endforeach
    </ul>
  </div>
@endempty
@empty($tags)
@else
  <div class="card my-2">
    <div class="card-header p-2">
      标签
    </div>
    <div class="card-body py-2 pl-2 pr-1">
      @foreach ($tags ?? [] as $tag)
        <a class="badge badge-pill badge-primary text-truncate my-1" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'meta/' . ($tag->id ?? $tag->slug)) }}" title="{{ $tag->name }}" style="max-width: 100%;">{{ $tag->name }}</a>
      @endforeach
    </div>
  </div>
@endempty
@empty($latest_contents)
@else
  <div class="card my-2">
    <div class="card-header p-2">
      最新
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($latest_contents ?? [] as $latest_content)
        <a class="list-group-item text-truncate py-1  pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'content/' . ($latest_content->id ?? $latest_content->slug)) }}" title="{{ $latest_content->title }}">{{ $latest_content->title }}</a>
      @endforeach
    </ul>
  </div>
@endempty
@empty($hottest_contents)
@else
  <div class="card my-2">
    <div class="card-header p-2">
      最热
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($hottest_contents ?? [] as $hottest_content)
        <a class="list-group-item text-truncate py-1 pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'content/' . ($hottest_content->id ?? $hottest_content->slug)) }}" title="{{ $hottest_content->title }}">{{ $hottest_content->title }}</a>
      @endforeach
    </ul>
  </div>
@endempty
@empty($latest_comments)
@else
  <div class="card my-2">
    <div class="card-header p-2">
      最新回复
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($latest_comments ?? [] as $latest_comment)
        <a class="list-group-item text-truncate py-1 pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : 'home/') . 'content/' . $latest_comment->content_id) }}" title="{{ $latest_comment->text }}">{{ $latest_comment->text }}</a>
      @endforeach
    </ul>
  </div>
@endempty
