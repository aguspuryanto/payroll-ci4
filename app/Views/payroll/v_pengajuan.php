<?php  

use App\Models\PinjamanTagihanModel;
$pinjamanTagihanModel = new PinjamanTagihanModel();

?>
<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Payroll</li>
          <li class="breadcrumb-item"><a href="<?= site_url('payroll/daftar'); ?>">Daftar</a></li>
          <li class="breadcrumb-item active" aria-current="page">Pengajuan</li>
        </ol>
      </nav>
      <h1 class="h2">Pengajuan Payroll</h1>        
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
        <form method="post" action="<?= base_url('payroll/pengajuan?kode='.$payroll->kode_payroll) ?>" >
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
            <label for="pt" class="col-sm-2 col-form-label">Status </label>
            <div class="col-sm-4">
              <p class="form-control-plaintext"><?= $payroll->status ?></p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="karyawan" class="col-sm-2 col-form-label">Karyawan yang Mengajukan <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <select class="form-select select2" required name="karyawan" id="karyawan">
                <option value=''>-- Pilih Karyawan --</option>
                <?php  
                foreach ($karyawans as $karyawan) {
                  echo "<option value='".$karyawan->kode_pt_karyawan."'>";
                    echo "[".$karyawan->nip."] ".$karyawan->nama_lengkap;
                  echo "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="tanggal_pengajuan_bon" class="col-sm-2 col-form-label">Tanggal Pengajuan Bon</label>
            <div class="col-sm-2">
              <p class="form-control-plaintext"><?= date("Y-m-d") ?></p>
              <input type="hidden" class="form-control" required id="tanggal_pengajuan_bon" name="tanggal_pengajuan_bon" value="<?= date("Y-m-d"); ?>">
            </div>
          </div>  
          <div class="row mb-3">
            <label for="kas" class="col-sm-2 col-form-label">Kas Asal <span class="text-danger">*</span></label>
            <div class="col-sm-4">
              <select class="form-select select2" required name="kas" id="kas">
                <option value=''>-- Pilih Kas Asal --</option>
                <?php  
                foreach ($kass as $kas) {
                  echo "<option value='".$kas->kode_kas."'>";
                    echo  $kas->nama;
                  echo "</option>";
                }
                ?>
              </select>
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
                    <th style="min-width: 100px;">BPJS TK JKM</th>
                    <th style="min-width: 100px;">BPJS TK JKK</th>
                    <th style="min-width: 100px;">BPJS TK JP</th>
                    <th style="min-width: 100px;">BPJS TK JHT</th>
                  </tr>
                </thead>
                <tbody class="body-payroll">
                  <tr>                    
                    <?php  
                    $total_total=0;
                    if(empty($detils)) {
                      echo "<td colspan='17' class='fst-italic'>Belum ada data</td>";
                    } else {
                      $total_nilai_pinjaman = 0;
                      foreach($detils as $detil) {
                        $where = array("pt_karyawan.isaktif"=>1, 'bon.kode_bon_status >'=>0, 'bon.kode_bon_status <'=>5, 'bon.kode_pt_karyawan'=>$detil->kode_pt_karyawan, 'pinjaman_tagihan.status'=>'Tagihan');
                        $pinjaman = $pinjamanTagihanModel->getPinjamanTagihan($where)->first();

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
                        
                        $nilai_pinjaman = 0; $kode_pinjaman_tagihan = null; $gaji_nett=$detil->gaji_nett;
                        if(!empty($pinjaman)) {
                          $nilai_pinjaman = $pinjaman->nominal;
                          $gaji_nett-=$nilai_pinjaman;
                          $kode_pinjaman_tagihan = $pinjaman->kode_pinjaman_tagihan;
                          $total_nilai_pinjaman+=$nilai_pinjaman;
                        }
                        echo "<td class='text-end'>".number_format($nilai_pinjaman,0,".",",");
                          echo "<input type='hidden' name='kode_pinjaman[$detil->kode_payroll_detil]' value='$kode_pinjaman_tagihan'>";
                          echo "<input type='hidden' name='nilai_pinjaman[$detil->kode_payroll_detil]' value='$nilai_pinjaman'>";
                        echo "</td>";
                        echo "<td class='text-end'>".number_format($gaji_nett,0,".",",");
                          echo "<input type='hidden' name='gaji_nett[$detil->kode_payroll_detil]' value='$gaji_nett'>";
                        echo "</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_kes_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk_jkm_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk_jkk_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk_jp_pt,0,".",",")."</td>";
                        echo "<td class='text-end'>".number_format($detil->bpjs_tk_jht_pt,0,".",",")."</td>";
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
                    <td class="text-end footer-total" tag="pinjaman"><?= number_format($total_nilai_pinjaman,0,".",",") ?></td>
                      <input type='hidden' name='total_nilai_pinjaman' value='<?= ($total_nilai_pinjaman); ?>'>
                    <td class="text-end footer-total" tag="nett"><?= number_format($payroll->total_gaji_net - $total_nilai_pinjaman,0,".",","); ?>
                      <input type='hidden' name='total_gaji_nett' value='<?= ($payroll->total_gaji_net - $total_nilai_pinjaman); ?>'>
                    </td>
                    <td class="text-end footer-total" tag="bpjs_kes_pt"><?= number_format($payroll->total_bpjs_kes_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk_jkm_pt"><?= number_format($payroll->total_bpjs_tk_jkm_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk_jkk_pt"><?= number_format($payroll->total_bpjs_tk_jkk_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk_jp_pt"><?= number_format($payroll->total_bpjs_tk_jp_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_tk_jht_pt"><?= number_format($payroll->total_bpjs_tk_jht_pt,0,".",",") ?></td>
                  </tr>
                </tfoot>
              </table>              
            </div>
          </div>  
          <div class="row mt-5 mb-3">
            <div class="col-sm-12">
              <input type="checkbox" name="sudah" value="sudah" id="sudah" required> <label for="sudah">Data di atas sudah benar <span class='text-danger'>*</span></label>
            </div>
          </div>
          <input type="hidden" name="kode" value="<?= $payroll->kode_payroll; ?>">
          <button type="submit" name="btnSimpan" value="simpan" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <a href='<?= base_url('payroll/daftar'); ?>' title="Kembali">&#171; Kembali</a>
      </div>
    </div>
  </main>
</div>