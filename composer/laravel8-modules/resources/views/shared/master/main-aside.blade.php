<form class="my-2" action="" method="GET">
  @csrf
  <div class="form-group">
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
    <ul class="list-group list-group-flush">
      @foreach ($children ?? [] as $meta)
        <a class="list-group-item text-truncate py-1 pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'meta/' . ($meta->mid ?? $meta->slug)) }}" title="{{ $meta->name }}">{{ $meta->name }}</a>
      @endforeach
    </ul>
  </div>
@endempty
@empty($metas['categories'])
@else
  <div class="card my-2">
    <div class="card-header p-2">
      分类
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($metas['categories'] ?? [] as $meta)
        <a class="list-group-item text-truncate py-1 pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'meta/' . ($meta->mid ?? $meta->slug)) }}" title="{{ $meta->name }}">{{ $meta->name }}</a>
      @endforeach
    </ul>
  </div>
@endempty
@empty($metas['tags'])
@else
  <div class="card my-2">
    <div class="card-header p-2">
      标签
    </div>
    <div class="card-body py-2 pl-2 pr-1">
      @foreach ($metas['tags'] ?? [] as $meta)
        <a class="badge badge-pill badge-primary text-truncate my-1" href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'meta/' . ($meta->mid ?? $meta->slug)) }}" title="{{ $meta->name }}" style="max-width: 100%;">{{ $meta->name }}</a>
      @endforeach
    </div>
  </div>
@endempty
@empty($contents['latest'])
@else
  <div class="card my-2">
    <div class="card-header p-2">
      最新
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($contents['latest'] ?? [] as $content)
        <a class="list-group-item text-truncate py-1  pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'content/' . ($content->cid ?? $content->slug)) }}" title="{{ $content->title }}">{{ $content->title }}</a>
      @endforeach
    </ul>
  </div>
@endempty
@empty($contents['hottest'])
@else
  <div class="card my-2">
    <div class="card-header p-2">
      最热
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($contents['hottest'] ?? [] as $content)
        <a class="list-group-item text-truncate py-1 pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'content/' . ($content->cid ?? $content->slug)) }}" title="{{ $content->title }}">{{ $content->title }}</a>
      @endforeach
    </ul>
  </div>
@endempty
@empty($comments['latest'])
@else
  <div class="card my-2">
    <div class="card-header p-2">
      最新回复
    </div>
    <ul class="list-group list-group-flush">
      @foreach ($comments['latest'] ?? [] as $comment)
        <a class="list-group-item text-truncate py-1 pr-1" href="{{ url((isset($module) ? $module['alias'] . '/' : '') . 'content/' . $comment->cid) }}" title="{{ $comment->text }}">{{ $comment->text }}</a>
      @endforeach
    </ul>
  </div>
@endempty
