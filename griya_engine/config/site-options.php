<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['results_per_page'] = 15;
$config['request_status'] = array('pending'=>'pending','rejected'=>'rejected','approved'=>'approved','delivered'=>'delivered');
$config['allowed_level_obat'] = array('superadmin','kasir');
$config['allowed_level_obat_admin'] = array('superadmin');
$config['allowed_level_tindakan'] = array('ticket-user','superadmin');
$config['allowed_level_tindakan_admin'] = array('logistic-admin','superadmin');
$config['allowed_level_pegawai'] = array('superadmin');
$config['allowed_level_pegawai_admin'] = array('superadmin');
$config['allowed_level_permintaanobat_admin'] = array('superadmin');
$config['allowed_level_jabatan_admin'] = array('superadmin');
$config['allowed_level_supplier_admin'] = array('superadmin');
$config['allowed_level_customer_admin'] = array('superadmin');
$config['allowed_level_depo'] = array('superadmin','depo');
$config['allowed_level_kamarobat'] = array('superadmin');
$config['allowed_level_apotik_admin'] = array('superadmin');
$config['allowed_level_kasir_admin'] = array('superadmin');
$config['allowed_level_pengeluaranklinik_admin'] = array('superadmin');

$config['max_days_to_cancel_transaction'] = 3;

$config['edc_methods'] = array(
    'bcacard'=>'BCA Card',
    'bcadebit'=>'BCA Debit',
    'bcavisamaster'=>'BCA Visa/Master',
    'mandiridebit'=>'Mandiri Debit',
    'mandirivisamaster'=>'Mandiri Visa/Master'
);

$config['edc_tax'] = array(
    'bcadebit'=>0,
    'bcacard'=>1.5,
    'bcavisamaster'=>2.5,
    'mandiridebit'=>0,
    'mandirivisamaster'=>2.5
);


$config['thumb_width'] = 150;
$config['thumb_height'] = 150;
$config['image_width'] = 1024;
$config['image_height'] = 1024;


?>