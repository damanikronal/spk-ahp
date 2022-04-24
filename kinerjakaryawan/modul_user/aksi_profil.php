<?php
session_start();
include "../config/koneksi.php";

$modul=$_GET[modul];
$act=$_GET[act];

// Update Karyawan
if ($modul=='profil' AND $act=='update'){
  if (empty($_POST[password])) {
    mysql_query("UPDATE karyawan SET 
                                  nama_karyawan   = '$_POST[nama_karyawan]',
								  jabatan			 = '$_POST[jabatan]',
								  divisi		 = '$_POST[divisi]',
                                  alamat          = '$_POST[alamat]',
                                  telp         = '$_POST[telp]'  
                           WHERE  nip       = '$_POST[id]'");
  }
  // Apabila password diubah
  else{
    $pass=md5($_POST[password]);
	mysql_query("UPDATE karyawan SET nip       = '$_POST[nip]',
                                  nama_karyawan   = '$_POST[nama_karyawan]',
								  jabatan			 = '$_POST[jabatan]',
								  divisi		 = '$_POST[divisi]',
                                  alamat          = '$_POST[alamat]',
                                  telp         = '$_POST[telp]'  
                           WHERE  nip       = '$_POST[id]'");
    mysql_query("UPDATE pengguna SET password = '$pass' 
                           WHERE  username       = '$_POST[id1]'");
  }
  header('location:../indexs.php?modul='.$modul);
}
?>

