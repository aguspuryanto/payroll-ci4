<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="hapusModalLabel">Hapus Payroll</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluids">
            <div class="row">
              <div class="col-md-12 mb-3">
                <p id='pesan-hapus'></p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="kode" id="hid-hapus-kode">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-danger" name="btnSimpanHapus" id="btnSimpanHapus" value="Hapus Payoll">Ya, Hapus Payroll Ini</button>
        </div>
      </form>
    </div>
  </div>
</div>