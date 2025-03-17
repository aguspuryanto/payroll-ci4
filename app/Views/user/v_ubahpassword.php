<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item">user</li>
          <li class="breadcrumb-item active" aria-current="page">Ubah Password</li>
        </ol>
      </nav>
      <h1 class="h2">Ubah password</h1>        
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
        <form method="post">
          <div class="row mb-3">
            <label for="pwd_lama" class="col-sm-2 col-form-label">Password Lama <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <input type="password" class="form-control" name="pwd_lama" id="pwd_lama" required autofocus>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="pwd_baru" class="col-sm-2 col-form-label">Password Baru <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <input type="password" class="form-control" name="pwd_baru" id="pwd_baru" required placeholder="Min. 6 karakter">
            </div>
          </div>
          <div class="row mb-3">
            <label for="pwd_baru2" class="col-sm-2 col-form-label">Ulangi Password Baru <span class="text-danger">*</span></label>
            <div class="col-sm-3">
              <input type="password" class="form-control" name="pwd_baru2" id="pwd_baru2" required placeholder="Min. 6 karakter">
            </div>
          </div> 
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