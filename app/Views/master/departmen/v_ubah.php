<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item"><a href="<?= site_url('master/departmen'); ?>">Departmen</a></li>
          <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
      </nav>
      <h1 class="h2">Ubah Departmen</h1>        
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
        <form method="post" action="<?= base_url('master/departmen/ubah?kode='.$departmen->kode_departmen) ?>" enctype="multipart/form-data">
          <div class="row mb-3">
            <label for="kode_pt" class="col-sm-2 col-form-label">PT <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <select class="form-select select2" required name="kode_pt" id="kode_pt" autofocus>
                <option value=''>-- Pilih PT --</option>
                <?php  
                $kebun = "";
                foreach($pts as $pt) {
                  if($kebun != $pt->nama_kebun) {
                    if(!empty($kebun)) echo "</optgroup>";
                    echo "<optgroup label='".$pt->nama_kebun."'>";
                    $kebun=$pt->nama_kebun;
                  }
                  $selected = ($departmen->kode_pt==$pt->kode_pt) ? "selected" : "";
                  echo "<option value='".$pt->kode_pt."' $selected>".$pt->nama."</option>";
                }
                echo "</optgroup>";
                ?>
              </select>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="nama" class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="nama" name="nama" maxlength="45" autofocus required value="<?= $departmen->nama; ?>">
              <span class="text-muted">Maks. 45 karakter</span>
            </div>
          </div>  
          <input type="hidden" name="kode" value="<?= $departmen->kode_departmen; ?>">
          <button type="submit" name="btnSimpan" value="simpan" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('master/departmen'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>