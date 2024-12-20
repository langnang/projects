@extends('layouts.master')

@section('content')
  <div class="container py-3">
    <form class="card" method="POST">
      <div class="card-header">
        <h5 class="card-title mb-0">Meta</h5>
      </div>
      <div class="card-body">
        @csrf
        <input type="hidden" name="_target" value="">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="content_ids" value="">
        <input type="hidden" name="parent" value="">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Name</label>
          <div class="col-sm-10">
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
            @error('name')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Slug</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="slug">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Ico</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="ico">
          </div>
        </div>

        <fieldset class="form-group row">
          <legend class="col-form-label col-sm-2 float-sm-left pt-0">Type</legend>
          <div class="col-sm-10">
            <div class="row @error('type') is-invalid @enderror">
              @foreach (\Arr::get($options, 'meta.type') ?? [] as $option)
                <div class="col">
                  <div class="form-check">
                    <input class="form-check-input @error('type') is-invalid @enderror" type="radio" name="type" value="{{ $option['value'] }}" @if (old('type') == $option['value']) checked @endif>
                    <label class="form-check-label">
                      {{ $option['name'] }}
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
            @error('type')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </fieldset>
        <fieldset class="form-group row">
          <legend class="col-form-label col-sm-2 float-sm-left pt-0">Status</legend>
          <div class="col-sm-10">
            <div class="row @error('status') is-invalid @enderror">
              @foreach (\Arr::get($options, 'meta.status') ?? [] as $option)
                <div class="col">
                  <div class="form-check">
                    <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status" value="{{ $option['value'] }}" @if (old('status') == $option['value']) checked @endif>
                    <label class="form-check-label">
                      {{ $option['name'] }}
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
            @error('status')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </fieldset>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Description</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="description" rows="3"></textarea>
          </div>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-end">
        <button type="button" class="btn ml-2 btn-secondary" data-dismiss="modal">Cancel</button>
        <div class="alert alert-danger my-0 d-none" name="delete-alert" role="alert" style="flex-grow: 1">
          确定要删除吗？
        </div>
        <button type="submit" class="btn ml-2 btn-primary">Confirm</button>
      </div>
    </form>
  </div>
@endsection
