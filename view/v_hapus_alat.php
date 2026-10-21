<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_alat.php";

$alat = new M_alat($koneksi);

$id = $_GET['id'];

$alat->hapus_data($id);

header("Location: v_alat.php");

exit;

?>