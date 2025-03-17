<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item"><a href="<?= site_url('master/kas'); ?>">Kas</a></li>
          <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
      </nav>
      <h1 class="h2">Ubah Kas</h1>        
    </div>

    <div class="row">
      <div class="col-md-12 text-danger">
        <?php 
          if(isset($errors)) {
            echo "<ul class='mb-3'>";
            foreach ($errors as $error){
              echo "<li>".esc($error)."</li>";
            }
            echo "</ul>";
          }
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <form method="post" action="<?= base_url('master/kas/ubah?kode='.$kas->kode_kas) ?>" >
          <div class="row mb-3">
            <label for="kode_departmen" class="col-sm-2 col-form-label">Departmen <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <select class="form-select select2" required name="kode_departmen" id="kode_departmen" autofocus>
                <option value=''>-- Pilih Departmen --</option>
                <?php  
                $pt = "";
                foreach($departmens as $departmen) {
                  if($pt != $departmen->nama_pt) {
                    if(!empty($pt)) echo "</optgroup>";
                    echo "<optgroup label='".$departmen->nama_pt."'>";
                    $pt=$departmen->nama_pt;
                  }
                  $selected = ($kas->kode_departmen==$departmen->kode_departmen) ? "selected" : "";
                  echo "<option value='".$departmen->kode_departmen."' $selected>".$departmen->nama."</option>";
                }
                echo "</optgroup>";
                ?>
              </select>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="nama" class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="nama" name="nama" maxlength="45" required value="<?= $kas->nama; ?>">
              <span class="text-muted">Maks. 45 karakter</span>
            </div>
          </div>

          <div class="row mb-3">
            <label for="nominal" class="col-sm-2 col-form-label">Nominal <span class="text-danger">*</span></label>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-text">Rp.</span>
                <input type="text" class="form-control number text-end" required id="nominal" name="nominal" value="<?= $kas->nominal; ?>">
              </div>
            </div>
          </div> 
          <input type="hidden" name="kode" value="<?= $kas->kode_kas; ?>">
          <button type="submit" name="btnSimpan" value="simpan" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('master/kas'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>