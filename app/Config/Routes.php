<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->get('/', 'Login::index', ['filter' => 'login']);
$routes->get('/login', 'Login::index', ['filter' => 'login']);
$routes->post('/login', 'Login::index');
$routes->get('/logout', 'Login::logout');

$routes->get('/home', 'Home::index', ['filter' => 'auth']);

$routes->match(['get','post'], '/setting/hakakses', 'setting\HakAkses::index', ['filter' => 'auth']);
$routes->match(['post'], '/setting/hakakses/ajaxProfil', 'setting\HakAkses::ajaxProfil', ['filter' => 'auth']);

$routes->match(['get','post'], '/master/pt', 'master\PT::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/pt/tambah', 'master\PT::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/pt/ubah', 'master\PT::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/master/bungapinjaman', 'master\BungaPinjaman::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/bungapinjaman/ubah', 'master\BungaPinjaman::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/master/departmen', 'master\Departmen::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/departmen/tambah', 'master\Departmen::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/departmen/ubah', 'master\Departmen::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/master/karyawanpt', 'master\KaryawanPT::index', ['filter' => 'auth']);
$routes->match(['post'],'/master/karyawanpt/ajaxAfdeling', 'master\KaryawanPT::ajaxAfdeling', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/karyawanpt/tambah', 'master\KaryawanPT::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/karyawanpt/ubah', 'master\KaryawanPT::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/master/kas', 'master\Kas::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/kas/tambah', 'master\Kas::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/master/kas/ubah', 'master\Kas::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/bon/daftar', 'bon\Daftar::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/bon/daftar/tambah', 'bon\Daftar::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/bon/daftar/ubah', 'bon\Daftar::ubah', ['filter' => 'auth']);

$routes->match(['get','post'],'/bon/persetujuan/tambah', 'bon\Persetujuan::tambah', ['filter' => 'auth']);

$routes->match(['get','post'], '/bon/pencatatan', 'bon\Pencatatan::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/bon/pencatatan/tambah', 'bon\Pencatatan::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/bon/pencatatan/ubah', 'bon\Pencatatan::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/pinjaman/tagihan', 'pinjaman\Tagihan::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/pinjaman/tagihan/tambah', 'pinjaman\Tagihan::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/pinjaman/tagihan/ubah', 'pinjaman\Tagihan::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/bon/realisasi', 'bon\Realisasi::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/bon/realisasi/tambah', 'bon\Realisasi::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/bon/realisasi/ubah', 'bon\Realisasi::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/bon/konfirmasi', 'bon\Konfirmasi::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/bon/konfirmasi/tambah', 'bon\Konfirmasi::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/bon/konfirmasi/ubah', 'bon\Konfirmasi::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/payroll/daftar', 'payroll\Daftar::index', ['filter' => 'auth']);
$routes->match(['post'], '/payroll/ajaxPayroll', 'payroll\Daftar::ajaxPayroll', ['filter' => 'auth']);
$routes->match(['post'], '/payroll/ajaxUploadCSV', 'payroll\Daftar::ajaxUploadCSV', ['filter' => 'auth']);
$routes->match(['get','post'],'/payroll/tambah', 'payroll\Daftar::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/payroll/ubah', 'payroll\Daftar::ubah', ['filter' => 'auth']);
$routes->match(['get','post'],'/payroll/lihat', 'payroll\Daftar::lihat', ['filter' => 'auth']);
$routes->match(['get','post'],'/payroll/pengajuan', 'payroll\Daftar::pengajuan', ['filter' => 'auth']);
$routes->match(['get','post'],'/payroll/download', 'payroll\Daftar::download', ['filter' => 'auth']);
$routes->match(['get','post'],'/payroll/pph21', 'payroll\PPh21::index', ['filter' => 'auth']);

$routes->match(['get','post'], '/kas/mutasi', 'kas\Mutasi::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/kas/mutasi/tambah', 'kas\Mutasi::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/kas/mutasi/ubah', 'kas\Mutasi::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/user/ubahpassword', 'user\UbahPassword::index', ['filter' => 'auth']);

$routes->match(['get','post'], '/user/daftar', 'user\Daftar::index', ['filter' => 'auth']);
$routes->match(['get','post'],'/user/daftar/tambah', 'user\Daftar::tambah', ['filter' => 'auth']);
$routes->match(['get','post'],'/user/daftar/ubah', 'user\Daftar::ubah', ['filter' => 'auth']);

$routes->match(['get','post'], '/laporan/jurnalumum', 'laporan\JurnalUmum::index', ['filter' => 'auth']);
$routes->match(['get'], '/laporan/jurnalumum/cetak', 'laporan\JurnalUmum::cetak', ['filter' => 'auth']);
$routes->match(['get','post'], '/laporan/payroll', 'laporan\Payroll::index', ['filter' => 'auth']);
$routes->match(['get'], '/laporan/payroll/cetak', 'laporan\Payroll::cetak', ['filter' => 'auth']);
$routes->match(['get','post'], '/laporan/bon', 'laporan\Bon::index', ['filter' => 'auth']);
$routes->match(['get'], '/laporan/bon/cetak', 'laporan\Bon::cetak', ['filter' => 'auth']);
$routes->match(['get','post'], '/laporan/kas', 'laporan\Kas::index', ['filter' => 'auth']);
$routes->match(['get'], '/laporan/kas/cetak', 'laporan\Kas::cetak', ['filter' => 'auth']);
$routes->match(['get','post'], '/laporan/karyawan', 'laporan\Karyawan::index', ['filter' => 'auth']);
$routes->match(['get'], '/laporan/karyawan/cetak', 'laporan\Karyawan::cetak', ['filter' => 'auth']);