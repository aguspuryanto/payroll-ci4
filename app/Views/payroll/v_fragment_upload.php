<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" id="frmUpload">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadModalLabel">Upload CSV</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluids">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="file" class="form-label" id="label-upload">Upload CSV</label>
                <input type="file" name="file" id="file" class="form-control" required accept="text/csv" >
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="kode" id="hid-upload-kode">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" name="btnSimpanUpload" id="btnSimpanUpload" value="Upload CSV">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>