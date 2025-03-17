<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Laporan</li>
          <li class="breadcrumb-item active" aria-current="page">Pencatatan Jurnal Umum</li>
        </ol>
      </nav>
      <h1 class="h2">Laporan Pencatatan Jurnal Umum</h1>
      
    </div>
    <div class="row mb-3">
      <div class="col-md-12">
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Filter
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <form>
                  <div class="row">
                    <div class="col-md-6">                      
                      <div class="form-floating mb-3">
                        <select class="form-select" id="status" name="status" aria-label="Floating label select">
                          <option value="">-- Semua Status --</option>
                          <option value="Pencatatan" <?php if($status=="Pencatatan") echo "selected"; ?>>Pencatatan</option>
                          <option value="Selesai" <?php if($status=="Selesai") echo "selected"; ?>>Selesai</option>
                        </select>
                        <label for="status">Status Bon</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="periode" class='text-muted'>Periode Tanggal Pencatatan</label>
                      <div class="mt-2">
                      </div>
                      <div class="mt-2">
                        <input type="text" name="periode" id="periode" class="form-control daterange" value="<?php echo $tglawal." - ".$tglakhir; ?>" />

                        <input type="checkbox" class="form-check-input" <?php if($abaikan==1) echo "checked"; ?> id="abaikan" name="abaikan" value="1" > <label for="abaikan" class="form-check-label">Abaikan Periode Tanggal Pencatatan</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-12 text-end">
        <a target='_blank' href="<?= site_url('laporan/jurnalumum/cetak?'.$_SERVER['QUERY_STRING']); ?>" class='btn btn-outline-primary'><i class='fa fa-print'></i> Cetak</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Pencatatan Jurnal Umum</caption>
            <thead>
              <tr>
                <th style="min-width: 90px !important;">Tanggal Pencatatan</th>
                <th style="min-width: 90px !important;">Tanggal Pencatatan</th>
                <th style="min-width: 140px !important;">PT</th>
                <th style="max-width: 90px !important;">Kode Bon</th>
                <th style="max-width: 150px !important;">Status</th>
                <th style="min-width: 200px;">COA</th>
                <th style="min-width: 120px; max-width: 120px !important;" class="text-center">Debet</th>
                <th style="min-width: 120px; max-width: 120px !important;" class="text-center">Kredit</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                foreach($arr_jurnalumum as $kode_bon => $datas) {
                  foreach($datas as $key=> $data) {
                    if(!$key) continue;
                    echo "<tr>";
                      echo "<td>".date("Y-m-d H:i:s", strtotime($data->tanggal_pencatatan))."</td>";
                      echo "<td>";
                        if($data->tanggal_transaksi) echo date("Y-m-d H:i:s", strtotime($data->tanggal_transaksi));
                      echo "</td>";
                      echo "<td>".$data->nama_pt."</td>";
                      echo "<td>".$data->kode_bon."</td>";
                      echo "<td>".$data->status."</td>";
                      echo "<td>";
                      echo "<table class='table table-striped table-bordered nowrap'>";
                      foreach($datas as $coa) {
                        echo "<tr>";                     
                          echo "<td class='overflow-x-hidden'>".$coa->kode_coa." - ".$coa->nama_transaksi."</td>";
                        echo "</tr>";
                      }
                      echo "</table>";
                      echo "</td>";

                      echo "<td>";
                      echo "<table class='table table-striped table-bordered nowrap'>";
                      foreach($datas as $debet) {
                        echo "<tr>";
                          if($debet->posisi=="DEBET")
                            echo "<td class='text-end'>".number_format($debet->nominal, 0, ".", ",")."</td>";
                          else
                            echo "<td>&nbsp;</td>";
                        echo "</tr>";
                      }
                      echo "</table>";
                      echo "</td>";

                      echo "<td>";
                      echo "<table class='table table-striped table-bordered nowrap'>";
                      foreach($datas as $kredit) {
                        echo "<tr>";
                          if($kredit->posisi=="KREDIT")
                            echo "<td class='text-end'>".number_format($kredit->nominal, 0, ".", ",")."</td>";
                          else
                            echo "<td>&nbsp;</td>";
                        echo "</tr>";
                      }
                      echo "</table>";
                      echo "</td>";
                    echo "</tr>";
                  }
                }

              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
  </main>
</div>