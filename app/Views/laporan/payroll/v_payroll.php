<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Laporan</li>
          <li class="breadcrumb-item active" aria-current="page">Payroll</li>
        </ol>
      </nav>
      <h1 class="h2">Laporan Payroll</h1>
      
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
                          <option value="Tagihan" <?php if($status=="Tagihan") echo "selected"; ?>>Tagihan</option>
                          <option value="Pengajuan Bon" <?php if($status=="Pengajuan Bon") echo "selected"; ?>>Pengajuan Bon</option>
                          <option value="Selesai" <?php if($status=="Selesai") echo "selected"; ?>>Selesai</option>
                        </select>
                        <label for="status">Status Payroll</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="periode" class='text-muted'>Periode Tanggal Pengajuan</label>
                      <div class="mt-2">
                      </div>
                      <div class="mt-2">
                        <input type="text" name="periode" id="periode" class="form-control daterange" value="<?php echo $tglawal." - ".$tglakhir; ?>" />

                        <input type="checkbox" class="form-check-input" <?php if($abaikan==1) echo "checked"; ?> id="abaikan" name="abaikan" value="1" > <label for="abaikan" class="form-check-label">Abaikan Periode Tanggal Pengajuan</label>
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
        <a target='_blank' href="<?= site_url('laporan/payroll/cetak?'.$_SERVER['QUERY_STRING']); ?>" class='btn btn-outline-primary'><i class='fa fa-print'></i> Cetak</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered caption-top display nowrap" width="100%" id="datatable">
            <caption class="bg-dark text-light p-2">Payroll</caption>
            <thead>
              <tr>
                <th style="max-width: 70px !important;">Kode</th>
                <th style="min-width: 100px !important;">Tgl Pengajuan</th>
                <th style="min-width: 120px !important;">PT</th>
                <th style="max-width: 100px !important;">Status</th>
                <th style="max-width: 100px !important;">Kode Bon</th>
                <th style="min-width: 120px !important;">Gaji Pokok</th>
                <th style="min-width: 120px !important;">Gaji Jabatan Tetap</th>
                <th style="min-width: 120px !important;">Tunjangan</th>
                <th style="min-width: 120px !important;">Lembur</th>
                <th style="min-width: 120px !important;">Lain-lain</th>
                <th style="min-width: 120px !important;">THR</th>
                <th style="min-width: 120px !important;">Gratifikasi</th>
                <th style="min-width: 120px !important;">Rapel</th>
                <th style="min-width: 120px !important;">BPJS Kes</th>
                <th style="min-width: 120px !important;">BPJS TK</th>
                <th style="min-width: 120px !important;">Pinjaman</th>
                <th style="min-width: 120px !important;">Gaji Nett</th>
                <th style="min-width: 120px !important;">BPJS Kes PT</th>
                <th style="min-width: 120px !important;">BPJS TK PT</th>
                <th style="min-width: 120px !important;">PPH21</th>
              </tr>
            </thead>
            <tbody>
              <?php   
                $arr_total = array("total_gaji_pokok"=>0, "total_gaji_jabatan"=>0, "total_tunjangan"=>0
                  , "total_lembur"=>0, "total_lain_lain"=>0, "total_thr"=>0, "total_gratifikasi"=>0
                  , "total_rapel"=>0, "total_bpjs_kes"=>0, "total_bpjs_tk"=>0, "total_pinjaman"=>0
                  , "total_gaji_net"=>0, "total_bpjs_kes_pt"=>0, "total_bpjs_tk_pt"=>0, "total_pph21"=>0
                );
                if($payrolls!=false) {
                  foreach($payrolls as $payroll) {
                    echo "<tr>";
                      echo "<td class='text-end'>".$payroll->kode_payroll."</td>";
                      echo "<td>
                        <div>".date("Y-m-d", strtotime($payroll->tanggal_pengajuan))."</div>
                        </td>";
                      
                      echo "<td>".$payroll->nama_pt."</td>";
                      echo "<td>".$payroll->status."</td>";
                      echo "<td class='text-end'>".$payroll->kode_bon."</td>";
                      echo "<td class='text-end'>".number_format($payroll->total_gaji_pokok,0,".", ",")."</td>"; $arr_total["total_gaji_pokok"]+=$payroll->total_gaji_pokok;
                      echo "<td class='text-end'>".number_format($payroll->total_gaji_jabatan,0,".", ",")."</td>"; $arr_total["total_gaji_jabatan"]+=$payroll->total_gaji_jabatan;
                      echo "<td class='text-end'>".number_format($payroll->total_tunjangan,0,".", ",")."</td>"; $arr_total["total_tunjangan"]+=$payroll->total_tunjangan;
                      echo "<td class='text-end'>".number_format($payroll->total_lembur,0,".", ",")."</td>"; $arr_total["total_lembur"]+=$payroll->total_lembur;
                      echo "<td class='text-end'>".number_format($payroll->total_lain_lain,0,".", ",")."</td>"; $arr_total["total_lain_lain"]+=$payroll->total_lain_lain;
                      echo "<td class='text-end'>".number_format($payroll->total_thr,0,".", ",")."</td>"; $arr_total["total_thr"]+=$payroll->total_thr;
                      echo "<td class='text-end'>".number_format($payroll->total_gratifikasi,0,".", ",")."</td>"; $arr_total["total_gratifikasi"]+=$payroll->total_gratifikasi;
                      echo "<td class='text-end'>".number_format($payroll->total_rapel,0,".", ",")."</td>"; $arr_total["total_rapel"]+=$payroll->total_rapel;
                      echo "<td class='text-end'>".number_format($payroll->total_bpjs_kes,0,".", ",")."</td>"; $arr_total["total_bpjs_kes"]+=$payroll->total_bpjs_kes;
                      echo "<td class='text-end'>".number_format($payroll->total_bpjs_tk,0,".", ",")."</td>"; $arr_total["total_bpjs_tk"]+=$payroll->total_bpjs_tk;
                      echo "<td class='text-end'>".number_format($payroll->total_pinjaman,0,".", ",")."</td>"; $arr_total["total_pinjaman"]+=$payroll->total_pinjaman;
                      echo "<td class='text-end'>".number_format($payroll->total_gaji_net,0,".", ",")."</td>"; $arr_total["total_gaji_net"]+=$payroll->total_gaji_net;
                      echo "<td class='text-end'>".number_format($payroll->total_bpjs_kes_pt,0,".", ",")."</td>"; $arr_total["total_bpjs_kes_pt"]+=$payroll->total_bpjs_kes_pt;
                      echo "<td class='text-end'>".number_format($payroll->total_bpjs_tk_pt,0,".", ",")."</td>"; $arr_total["total_bpjs_tk_pt"]+=$payroll->total_bpjs_tk_pt;
                      echo "<td class='text-end'>".number_format($payroll->total_pph21,0,".", ",")."</td>"; $arr_total["total_pph21"]+=$payroll->total_pph21;
                    echo "</tr>";
                  }
                } 
              ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td colspan='5' class='text-end'>TOTAL</td>
                <?php  
                  foreach($arr_total as $value) {
                    echo "<td class='text-end'>".number_format($value,0,".", ",")."</td>";
                  }
                ?>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    
  </main>
</div>