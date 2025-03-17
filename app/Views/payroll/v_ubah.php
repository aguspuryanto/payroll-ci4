<div class="row">
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= site_url('home'); ?>">Home</a></li>
          <li class="breadcrumb-item">Payroll</li>
          <li class="breadcrumb-item"><a href="<?= site_url('payroll/daftar'); ?>">Daftar</a></li>
          <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
      </nav>
      <h1 class="h2">Ubah Payroll</h1>        
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
        <form method="post" action="<?= base_url('payroll/ubah?kode='.$payroll->kode_payroll) ?>" >
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
                    <th style="min-width: 100px;">BPJS JKM PT</th>
                    <th style="min-width: 100px;">BPJS JKK PT</th>
                    <th style="min-width: 100px;">BPJS JP PT</th>
                    <th style="min-width: 100px;">BPJS JHT PT</th>
                  </tr>
                </thead>
                <tbody class="body-payroll">
                  <tr>                    
                    <?php  
                    $total_total=0;
                    if(empty($detils)) {
                      echo "<td colspan='19' class='fst-italic'>Belum ada data</td>";
                    } else {
                      foreach($detils as $detil) {
                        echo "<tr>";
                        echo "<td>".$detil->no_urut_pt_karyawan."</td>";
                        echo "<td>".$detil->nik_pt_karyawan."<input type='hidden' class='nik' nik='$detil->nik_pt_karyawan' value='$detil->kode_payroll_detil'></td>";
                        echo "<td>".$detil->nama_lengkap_pt_karyawan."</td>";
                        echo "<td>".$detil->jabatan_pt_karyawan."</td>";
                        echo "<td class='text-end'><span class='gaji_pokok' kode='$detil->kode_payroll_detil'>".number_format($detil->gaji_pokok,0,".",",")."</span><input type='hidden' name='gaji_pokok[$detil->kode_payroll_detil]' value='$detil->gaji_pokok'></td>";
                        echo "<td class='text-end'><span class='gaji_jabatan_tetap' kode='$detil->kode_payroll_detil'>".number_format($detil->gaji_jabatan_tetap,0,".",",")."</span><input type='hidden' name='gaji_jabatan_tetap[$detil->kode_payroll_detil]' value='$detil->gaji_jabatan_tetap'></td>";
                        echo "<td class='text-end'><input type='text' tag='tunjangan' class='form-control number tunjangan text-end payroll' name='tunjangan[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->tunjangan'></td>";
                        echo "<td class='text-end'><input type='text' tag='lembur' class='form-control number lembur text-end payroll' name='lembur[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->lembur'></td>";
                        echo "<td class='text-end'><input type='text' tag='lainlain' class='form-control number lainlain text-end payroll' name='lainlain[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->lain_lain'></td>";
                        echo "<td class='text-end'><input type='text' tag='thr' class='form-control number thr text-end payroll' name='thr[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->thr'></td>";
                        echo "<td class='text-end'><input type='text' tag='gratifikasi' class='form-control number gratifikasi text-end payroll' name='gratifikasi[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->gratifikasi'></td>";
                        echo "<td class='text-end'><input type='text' tag='rapel' class='form-control number rapel text-end payroll' name='rapel[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->rapel'></td>";
                        $total = $detil->gaji_pokok+$detil->gaji_jabatan_tetap+$detil->tunjangan+$detil->lembur+$detil->lain_lain+$detil->thr+$detil->gratifikasi+$detil->rapel;
                        $total_total += $total;
                        echo "<td class='text-end'><span class='total' kode='$detil->kode_payroll_detil'>".number_format($total, 0, ".", ",")."</span></td>";
                        echo "<td class='text-end'><input type='text' tag='bpjs_kes' class='form-control number bpjs_kes text-end bpjs' name='bpjs_kes[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->bpjs_kes'></td>";
                        echo "<td class='text-end'><input type='text' tag='bpjs_tk' class='form-control number bpjs_tk text-end bpjs' name='bpjs_tk[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->bpjs_tk'></td>";
                        echo "<td class='text-end'><span class='pinjaman' kode='$detil->kode_payroll_detil'>".number_format($detil->pinjaman,0,".",",")."</span><input type='hidden' name='pinjaman[$detil->kode_payroll_detil]' value='$detil->pinjaman'><input type='hidden' value='$detil->kode_pinjaman_tagihan' name='kode_pinjaman_tagihan[$detil->pinjaman]'></td>";
                        echo "<td class='text-end'><span class='nett' kode='$detil->kode_payroll_detil'>".number_format($detil->gaji_nett,0,".",",")."</span></td>";
                        echo "<td class='text-end'><input type='text' tag='bpjs_kes_pt' class='form-control number bpjs_kes_pt text-end bpjs_pt' name='bpjs_kes_pt[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->bpjs_kes_pt'></td>";
                        echo "<td class='text-end'><input type='text' tag='bpjs_jkm_pt' class='form-control number bpjs_jkm_pt text-end bpjs_pt' name='bpjs_jkm_pt[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->bpjs_jkm_pt'></td>";
                        echo "<td class='text-end'><input type='text' tag='bpjs_jkk_pt' class='form-control number bpjs_jkk_pt text-end bpjs_pt' name='bpjs_jkk_pt[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->bpjs_jkk_pt'></td>";
                        echo "<td class='text-end'><input type='text' tag='bpjs_jp_pt' class='form-control number bpjs_jp_pt text-end bpjs_pt' name='bpjs_jp_pt[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->bpjs_jp_pt'></td>";
                        echo "<td class='text-end'><input type='text' tag='bpjs_jht_pt' class='form-control number bpjs_jht_pt text-end bpjs_pt' name='bpjs_jht_pt[$detil->kode_payroll_detil]' kode='$detil->kode_payroll_detil' value='$detil->bpjs_jht_pt'></td>";
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
                    <td class="text-end footer-total" tag="bpjs_jkm_pt"><?= number_format($payroll->total_bpjs_jkm_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_jkk_pt"><?= number_format($payroll->total_bpjs_jkk_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_jp_pt"><?= number_format($payroll->total_bpjs_jp_pt,0,".",",") ?></td>
                    <td class="text-end footer-total" tag="bpjs_jht_pt"><?= number_format($payroll->total_bpjs_jht_pt,0,".",",") ?></td>
                  </tr>
                </tfoot>
              </table>              
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