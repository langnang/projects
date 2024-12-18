
(function () {
  'use strict'

  $('#meta-item-modal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget); // Button that triggered the modal
    console.log(button);
    const mids = button.data('mids').toString().split('-'); // Extract info from data-* attributes
    const mid = mids.slice(-1)[0];
    const modal = $(this);
    let meta;
    const method = button.data('method'); // Extract info from data-* attributes
    modal.find(`.modal-footer [name=delete]`).addClass('d-none');
    modal.find(`.modal-body input,.modal-body textarea`).prop('disabled', false);
    console.log(mids, mid, method);
    if (method) {
      modal.find('.modal-body input[name=_target]').val(method);
      switch (method) {
        case "insert_meta_item":
          modal.find('.modal-title').text('Insert Meta');
          if (mid) modal.find('.modal-body input[name=parent]').val(mid);
          break;
        case "update_meta_item":
          modal.find('.modal-title').text('Update Meta')
          if (mid) modal.find('.modal-body input[name=mid]').val(mid);

          meta = mids.slice(1).reduce(function (total, val) {
            return total.find(v => v.mid == val);
          }, $app.metas);
          console.log($app.metas, mids, mid, meta);
          if (meta) {
            ['name', 'slug', 'ico', 'parent'].forEach(val => {
              modal.find(`.modal-body input[name=${val}]`).val(meta[val])
            });
            ['type', 'status'].forEach(val => {
              modal.find(`.modal-body input[name=${val}][value=${meta[val]}]`).prop('checked', true);
            });
            modal.find(`.modal-body textarea[name=description]`).val(meta.description);
          }
          break;
        case "delete_meta_item":
          modal.find('.modal-title').text('Delete Meta');
          if (mid) modal.find('.modal-body input[name=mid]').val(mid);
          meta = mids.slice(1).reduce(function (total, val) {
            return total.find(v => v.mid == val);
          }, $app.metas);
          console.log($app.metas, mids, mid, meta);
          if (meta) {
            ['name', 'slug', 'ico', 'parent'].forEach(val => {
              modal.find(`.modal-body input[name=${val}]`).val(meta[val]).prop('disabled', true);
            });
            ['type', 'status'].forEach(val => {
              modal.find(`.modal-body input[name=${val}][value=${meta[val]}]`).prop('checked', true);
              modal.find(`.modal-body input[name=${val}]`).prop('disabled', true);
            });
            modal.find(`.modal-body textarea[name=description]`).val(meta.description).prop('disabled', true);
          }
          modal.find(`.modal-footer [name=delete]`).removeClass('d-none');
          break;
        default: break;
      }
    }
    //   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    //   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    // modal.find('.modal-title').text('New message to ' + recipient)

  });

})()
