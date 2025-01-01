@extends('admin::layouts.master')

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header pl-3 d-flex align-items-center">
              <h3 class="card-title mr-auto">Content List</h3>

              <form class="form-inline card-tools float-right mb-0">
                <div class="form-group mr-1">
                  <select class="form-control form-control-sm" name="module" placeholder="Module" disabled>
                    <option value="">--Module--</option>
                    <option value="home" @if ($module['alias'] == 'home') selected @endif>Home</option>
                    @foreach (Module::allEnabled() as $moduleName => $moduleObject)
                      <option value="{{ $moduleObject->getAlias() }}" @if ($module['alias'] == $moduleObject->getAlias()) selected @endif>{{ $moduleName }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group mr-1">
                  <input type="text" name="slug" class="form-control form-control-sm" placeholder="--Slug--" value="{{ request()->input('slug') }}">
                </div>
                <div class="form-group mr-1">
                  <input type="text" name="title" class="form-control form-control-sm" placeholder="--Title--" value="{{ request()->input('title') }}">
                </div>
                <div class="form-group mr-1">
                  <select class="form-control form-control-sm" name="type">
                    <option value="">--Type--</option>
                    <option value="post" @if (request()->input('type') == 'post') selected @endif>post</option>
                    <option value="page" @if (request()->input('type') == 'page') selected @endif>page</option>
                    <option value="template" @if (request()->input('type') == 'template') selected @endif>template</option>
                    <option value="draft-post" @if (request()->input('type') == 'draft-post') selected @endif>draft-post</option>
                    <option value="draft-page" @if (request()->input('type') == 'draft-page') selected @endif>draft-page</option>
                    <option value="draft-template" @if (request()->input('type') == 'draft-template') selected @endif>draft-template
                    </option>
                  </select>
                </div>
                <div class="form-group mr-1">
                  <select class="form-control form-control-sm" name="status">
                    <option value="">--Status--</option>
                    <option value="publish" @if (request()->input('status') == 'publish') selected @endif>publish</option>
                    <option value="private" @if (request()->input('status') == 'private') selected @endif>private</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-sm btn-default form-control form-control-sm">
                  <i class="fas fa-search"></i>
                </button>

              </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: calc(100vh - 321px);">
              <table class="table table-sm table-striped table-hover table-head-fixed text-nowrap">
                <thead>
                  <tr>
                    <th width="14px">#</th>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Children</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Release At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($paginator ?? [] as $item)
                    <tr>
                      <td>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="{{ $item['id'] }}">
                        </div>
                      </td>
                      <td><a class="" href="contents/{{ $item['id'] }}">{{ $item['id'] }}</a></td>
                      <td>{{ $item['title'] }}</td>
                      <td>{{ $item['children_count'] }}</td>
                      <td>{{ $item['type'] }}</td>
                      <td>{{ $item['status'] }}</td>
                      <td>{{ $item['created_at'] }}</td>
                      <td>{{ $item['updated_at'] }}</td>
                      <td>{{ $item['release_at'] }}</td>
                      <td class="" style="padding-top: 3px;padding-bottom: 3px;">
                        <button type="button" class="btn btn-sm py-0 btn-secondary">预览</button>
                        <button type="button" class="btn btn-sm py-0 btn-primary">编辑</button>
                        <button type="button" class="btn btn-sm py-0 btn-danger">删除</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>

            </div>
            <!-- /.card-body -->
            <div class="card-footer py-2 clearfix d-flex align-items-center">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox">
                <button type="button" class="btn btn-sm btn-link form-check-label">全选</button>
              </div>
              <div class="form-group mb-0 mr-2">
                <select class="form-control form-control-sm">
                  <option>选中项：</option>
                  <optgroup label="选中项：">
                    <option>编辑</option>
                    <option>复制</option>
                    <option>删除</option>
                    <option>导出</option>
                    <option>迁移</option>
                  </optgroup>

                  <optgroup label="----"></optgroup>
                </select>
              </div>
              <div class="mr-auto">

                <a type="button" class="btn btn-sm btn-primary" href="contents/create">新增</a>
                <button type="button" class="btn btn-sm btn-warning">修改</button>
                <button type="button" class="btn btn-sm btn-danger">删除</button>
                <button type="button" class="btn btn-sm btn-secondary">上传</button>
                <button type="button" class="btn btn-sm btn-secondary">下载</button>
              </div>
              <span class="px-1"> 共 {{ $paginator->total() }} 条</span>
              <span class="px-1"> {{ $paginator->currentPage() }}/{{ $paginator->count() }} </span>
              {{ $paginator->onEachSide(2)->withQueryString(['slug', 'title', 'type', 'status'])->links() }}
            </div>
            <!-- /.card-footer -->
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
