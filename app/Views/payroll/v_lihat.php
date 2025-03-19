<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Payroll</li>
          <li class="breadcrumb-item"><a href="<?= site_url('payroll/daftar'); ?>">Daftar</a></li>
          <li class="breadcrumb-item active" aria-current="page">Lihat</li>
        </ol>
      </nav>
      <h1 class="h2">Lihat Payroll</h1>        
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
        <div class="row mb-3">
          <label for="pt" class="col-sm-2 col-form-label">Kode Payroll </label>
          <div class="col-sm-4">
            <p class="form-control-plaintext"><?= $payroll->kode_payroll ?></p>
          </div>
        </div> 
          <div class="row mb-3">
            <label for="pt" class="col-sm-2 col-form-label">PT </label>
            <div class="col-sm-4">
              <p class="form-control-plaintext"><?= $payroll->nama_pt ?></p>
            </div>
          </div>         
          <div class="row mb-3">
            <label for="tanggal_pengajuan" class="col-sm-2 col-form-label">Tanggal Pengajuan</label>
            <div class="col-sm-2">
              <p class="form-control-plaintext"><?= date("Y-m-d", strtotime($payroll->tanggal_pengajuan)); ?></p>
            </div>
          </div>       
          <div class="row mb-3">
            <label for="tanggal_pengajuan" class="col-sm-2 col-form-label">Tanggal Pengajuan Bon</label>
            <div class="col-sm-2">
              <p class="form-control-plaintext"><?php if(!empty($payroll->tanggal_pengajuan_bon)) echo date("Y-m-d", strtotime($payroll->tanggal_pengajuan_bon)); ?></p>
            </div>
          </div>        
          <div class="row mb-3">
            <label for="tanggal_pengajuan" class="col-sm-2 col-form-label">Tanggal Pelunasan</label>
            <div class="col-sm-2">
              <p class="form-control-plaintext"><?php if(!empty($payroll->tanggal_lunas)) echo date("Y-m-d", strtotime($payroll->tanggal_lunas)); ?></p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="pt" class="col-sm-2 col-form-label">Kode Bon </label>
            <div class="col-sm-4">
              <p class="form-control-plaintext"><?= $payroll->kode_bon ?></p>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="pt" class="col-sm-2 col-form-label">Status </label>
            <div class="col-sm-4">
              <p class="form-control-plaintext"><?= $payroll->status ?></p>
            </div>
          </div> 
          <div class="row mb-3">
            <label for="keterangan" class="col-sm-2 col-form-label">Detil</label>
            <div class="col-sm-12 table-responsive">
              <table class="table table-hover table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="min-width: 80px;">No Urut</th>
                    <th style="min-width: 90px;">NIK</th>
                    <th style="min-width: 200px; ">Nama</th>
                    <th style="min-width: 120px;">Jabatan</th>
                    <th style="min-width: 110px;">Gaji Pokok</th>
                    <th style="min-width: 100px;">Gaji Jabatan Tetap</th>
                    <th style="min-width: 120px;">Tunjangan</th>
                    <th style="min-width: 120px;">Lembur</th>
                    <th style="min-width: 120px;">Lain-lain</th>
                    <th style="min-width: 120px;">THR</th>
                    <th style="min-width: 120px;">Gratifikasi</th>
                    <th style="min-width: 120px;">Rapel</th>
                    <th style="min-width: 100px;">Total</th>
                    <th style="min-width: 100px;">BPJS Kes</th>
                    <th style="min-width: 100px;">BPJS TK</th>
                    <th style="min-width: 100px;">Pinjaman Pegawai</th>
                    <th style="min-width: 100px;">Gaji Nett</th>
                    <th style="min-width: 100px;">BPJS Kes PT</th>
                    <th style="min-width: 100px;">BPJS TK JKM PT</th>
                    <th style="min-width: 100px;">BPJS TK JKK PT</th>
                    <th style="min-width: 100px;">BPJS TK JP PT</th>
                    <th style="min-width: 100px;">BPJS TK JHT PT</th>
                    <th style="min-width: 100px;">PPH21</th>
                  </tr>
                </thead>
                <tbody class="body-payroll">
                  <tr>                    
                    <?php  
                    $total_total=0;
                    if(empty($detils)) {
                      echo "<td colspan='20' class='fst-italic'>Belum ada data</td>";
                    } else {
                      foreach($detils as $detil) {
                        echo "<tr>";
                        echo "<td>".$detil->no_urut_pt_karyawan."</td>";
                        echo "<td>".$detil->nik_pt_karyawan."</td>";
                        echo "<td>".$detil->nama_lengkap_pt_karyawan."</td>";
                        echo "<td>".$detil->jabatan_pt_karyawan."</td>";
                        echo "<td class='text-end'>".number_format($detil->gaji_pokok,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->gaji_jabatan_tetap,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->tunjangan,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->lembur,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->lain_lain,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->thr,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->gratifikasi,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->rapel,0,".",",")."</td>";
                        $total = $detil->gaji_pokok+$detil->gaji_jabatan_tetap+$detil->tunjangan+$detil->lembur+$detil->lain_lain+$detil->thr+$detil->gratifikasi+$detil->rapel;
                        $total_total += $total;
                        echo "<td class='text-end'>".number_format($total,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_kes,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->pinjaman,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->gaji_nett,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_kes_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk_jkm_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk_jkk_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk_jp_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk_jht_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->pph21,0,".",",")."</td>";
                        echo "</tr>";
                      }
                    }
                    ?>
                  </tr>
                </tbody>
                <tfoot class="footer-payroll fw-bold">
                  <tr>
                    <td class="text-end" colspan="4">TOTAL</td>
                    <td class="text-end footer-total" tag="gaji_pokok"><?= number_format($payroll->total_gaji_pokok,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="gaji_jabatan_tetap"><?= number_format($payroll->total_gaji_jabatan,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="tunjangan"><?= number_format($payroll->total_tunjangan,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="lembur"><?= number_format($payroll->total_lembur,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="lainlain"><?= number_format($payroll->total_lain_lain,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="thr"><?= number_format($payroll->total_thr,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="gratifikasi"><?= number_format($payroll->total_gratifikasi,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="rapel"><?= number_format($payroll->total_rapel,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="total"><?= number_format($total_total,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_kes"><?= number_format($payroll->total_bpjs_kes,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk"><?= number_format($payroll->total_bpjs_tk,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="pinjaman"><?= number_format($payroll->total_pinjaman,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="nett"><?= number_format($payroll->total_gaji_net,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_kes_pt"><?= number_format($payroll->total_bpjs_kes_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk_pt"><?= number_format($payroll->total_bpjs_tk_jkm_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk_pt"><?= number_format($payroll->total_bpjs_tk_jkk_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk_pt"><?= number_format($payroll->total_bpjs_tk_jp_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk_pt"><?= number_format($payroll->total_bpjs_tk_jht_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="pph21"><?= number_format($payroll->total_pph21,0,".",",") ?></td>
                  </tr>
                </tfoot>
              </table>              
            </div>
          </div>  
          
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('payroll/daftar'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>