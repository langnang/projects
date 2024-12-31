@extends('admin::layouts.master')

@push('styles')
  <style>
    .note-editor.card {
      margin-bottom: 0;
    }
  </style>
@endpush

@section('content')
  <section class="content">
    <div class="container-fluid">
      <form class="row" method="post" name="content">
        @csrf
        <div class="col-12">

        </div>
        <div class="col-12">
          <div class="card card-outline card-primary">
            <div class="card-header py-2">
              <h3 class="card-title">Meta</h3>
            </div>
            <div class="card-body pb-0">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Name</span>
                    </div>
                    <input type="text" class="form-control" name='name' value="{{ old('name', $meta['name']) }}">
                  </div>
                </div>
                <div class="form-group col-md-3">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Slug</span>
                    </div>
                    <input type="text" class="form-control" name='slug' value="{{ $meta['slug'] ?? '' }}">
                  </div>
                </div>
                <div class="form-group col-md-3">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Module</span>
                    </div>
                    <select class="form-control" name='module'>
                      @foreach (Module::all() ?? [] as $moduleName => $moduleObject)
                        <option value="{{ $moduleObject->getAlias() }}" @if ($moduleObject->getAlias() == old('module', $meta['module'])) selected @endif>{{ $moduleName }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Ico</span>
                    </div>
                    <input type="text" class="form-control" name='ico' value="{{ $meta['ico'] ?? '' }}">
                  </div>
                </div>
                <div class="form-group col-md-3">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Type</span>
                    </div>
                    <select class="form-control" name='type'>
                      @foreach (Arr::get($options, 'meta.type', []) as $option)
                        <option value="{{ $option['value'] }}" @if ($option['value'] == old('type', $meta['type'])) selected @endif>{{ $option['name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group col-md-3">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Status</span>
                    </div>
                    <select class="form-control" name='status'>
                      @foreach (Arr::get($options, 'meta.status', []) as $option)
                        <option value="{{ $option['value'] }}" @if ($option['value'] == old('status', $meta['status'])) selected @endif>{{ $option['name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group col-12">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Description</span>
                    </div>
                    <textarea name="description" id="" class="form-control" rows="2">{{ $meta['description'] ?? '' }}</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer py-2">
              <div class="row">
                <div class="col mr-auto">
                  <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                  <button type="button" class="btn btn-sm btn-warning">Release</button>
                </div>
                <div class="col col-auto">
                  <button type="button" class="btn btn-sm btn-secondary">Draft</button>
                  <button type="button" class="btn btn-sm btn-danger">Faker</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">Categories</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <div class="card-body">

            </div>

            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Tags</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <div class="card-body">

            </div>

            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Links</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <div class="card-body">

            </div>

            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </div>
          </div>
        </div>
      </form>

    </div>
    <div class="d-none">
      <div id="editor">
        {{ $meta['text'] ?? '' }}
      </div>
    </div>
  </section>

  <section>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control form-control-sm">
              </div>
              <div class="form-group">
                <label for="">Type</label>
                <input type="text" class="form-control form-control-sm">
              </div>
              <div class="form-group">
                <label for="">Value</label>
                <textarea type="text" class="form-control form-control-sm"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>


  </section>
@endsection


@push('scripts')
  {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script> --}}
  {{-- <script src="https://cdn.jsdelivr.net/npm/ckeditor5@41.4.2/dist/browser/index.umd.min.js"></script> --}}
  {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ckeditor5@41.4.2/dist/browser/index.min.css"> --}}
  <script>
    $(document).on('click', '[name="insert-field"]', function(e) {
      console.log(e, $(this))
    })
    $(document).on('click', '[name="delete-field"]', function(e) {
      console.log(e, $(this))
    })
    $('#exampleModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var field = button.parents('.form-row')
      var field_name = field.find('[name]').val();
      var field_type = field.find('[name]').val();
      var field_value = field.find('[name]').val();
      console.log(parent)
      //   var signature = button.data('signature') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      //   var modal = $(this)
      //   modal.find('.modal-title').text('php artisan ' + signature)
      //   modal.find('.modal-body input').val(signature)
      //   axios({
      //     url: "/api/artisan",
      //     method: "post",
      //     data: {
      //       signature,
      //     },
      //   }).then(res => {
      //     console.log(modal, res);
      //     modal.find('.modal-body').text(res)
      //   });
    })
  </script>
  <script>
    $(function() {
      // Summernote
      //   $('#summernote').summernote()

      // CodeMirror
      //   CodeMirror.fromTextArea(document.getElementById("codeMirror"), {
      //     mode: "markdown",
      //     theme: "monokai"
      //   });
    })

    // $(document).ready(() => {
    //   ckeditor5.ClassicEditor
    //     .create(document.querySelector('#editor'), {
    //       toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
    //       heading: {
    //         options: [{
    //             model: 'paragraph',
    //             title: 'Paragraph',
    //             class: 'ck-heading_paragraph'
    //           },
    //           {
    //             model: 'heading1',
    //             view: 'h1',
    //             title: 'Heading 1',
    //             class: 'ck-heading_heading1'
    //           },
    //           {
    //             model: 'heading2',
    //             view: 'h2',
    //             title: 'Heading 2',
    //             class: 'ck-heading_heading2'
    //           }
    //         ]
    //       }
    //     })
    //     .catch(error => {
    //       console.error(error);
    //     });
    // })
  </script>
@endpush
