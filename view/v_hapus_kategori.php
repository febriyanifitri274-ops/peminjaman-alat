<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_kategori.php";

$kategori = new M_kategori($koneksi);

$id = $_GET['id'];

$kategori->hapus_data($id);

header("Location: v_kategori.php");
exit;

?>