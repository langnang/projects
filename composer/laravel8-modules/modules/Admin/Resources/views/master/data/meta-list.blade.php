@extends('admin::layouts.master')

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h3 class="card-title mr-auto">Fixed Header Table</h3>

              <form class="form-inline card-tools mb-0">
                <input type="hidden" name="parent" value="{{ request()->input('parent', 0) }}">
                <div class="form-group ml-1">
                  <select class="form-control form-control-sm" name="module" placeholder="Module">
                    <option value="">--Module--</option>
                    @foreach (\Module::allEnabled() as $moduleName => $moduleObject)
                      <option value="{{ $moduleObject->getAlias() }}" @if (request()->input('module') == $moduleObject->getAlias()) selected @endif>{{ $moduleName }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group ml-1">
                  <input type="text" name="slug" class="form-control form-control-sm" placeholder="Slug" value="{{ request()->input('slug') }}">
                </div>
                <div class="form-group ml-1">
                  <input type="text" name="title" class="form-control form-control-sm" placeholder="Title" value="{{ request()->input('title') }}">
                </div>
                <div class="form-group ml-1">
                  <select class="form-control form-control-sm" name="type">
                    <option value="">--Type--</option>
                    @foreach (Arr::get($options, 'meta.type') as $option)
                      <option value="{{ $option['value'] }}" @if (request()->input('type') == $option['value']) selected @endif>{{ $option['name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group ml-1">
                  <select class="form-control form-control-sm" name="status">
                    <option value="">--Status--</option>
                    @foreach (Arr::get($options, 'meta.status') as $option)
                      <option value="{{ $option['value'] }}" @if (request()->input('status') == $option['value']) selected @endif>{{ $option['name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <button type="submit" class="btn btn-sm btn-default form-control form-control-sm ml-1">
                  <i class="fas fa-search"></i>
                </button>
              </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: calc(100vh - 321px);;">
              <table class="table table-sm table-striped table-hover table-head-fixed text-nowrap">
                <thead>
                  <tr>
                    <th width="14px">#</th>
                    <th>ID</th>
                    <th>Name</th>
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
                          <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                        </div>
                      </td>
                      <td><a class="" href="metas/{{ $item['id'] }}">{{ $item['id'] }}</a></td>
                      <td>{{ $item['name'] }}</td>
                      <td><a class="" href="metas?parent={{ $item['id'] }}">{{ $item['children_count'] }}</a></td>
                      <td>{{ $item['type'] }}</td>
                      <td>{{ $item['status'] }}</td>
                      <td>{{ $item['created_at'] }}</td>
                      <td>{{ $item['updated_at'] }}</td>
                      <td>{{ $item['release_at'] }}</td>
                      <td style="padding-top: 3px;padding-bottom: 3px;">
                        <button type="button" class="btn btn-sm py-0 btn-secondary">预览</button>
                        <a href="metas/{{ $item['id'] }}" type="button" class="btn btn-sm py-0 btn-warning">编辑</a>
                        <button type="button" class="btn btn-sm py-0 btn-danger">删除</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>

            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <div class="card-footer__left float-left">
                {{ $paginator->withQueryString(['status'])->links() }}
              </div>
              <div class="card-footer__right float-right">
                <a type="button" class="btn btn-secondary" href="/admin/webstack/metas/insert">新增</a>
                <button type="button" class="btn btn-secondary">Middle</button>
                <button type="button" class="btn btn-secondary">Right</button>
              </div>
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
