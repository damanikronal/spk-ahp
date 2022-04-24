<?php
session_start();
include "../../config/koneksi.php";

$modul=$_GET[modul];
$act=$_GET[act];

// Hapus Karyawan
if ($modul=='dtakary' AND $act=='hapus'){
  mysql_query("DELETE FROM karyawan WHERE nip='$_GET[id]'");
  mysql_query("DELETE FROM pengguna WHERE nip='$_GET[id]'");
  mysql_query("DELETE FROM evaluasi WHERE nip='$_GET[id]'");
  mysql_query("DELETE FROM hasil_evaluasi WHERE nip='$_GET[id]'");
  header('location:../../indexs.php?modul='.$modul);
}

// Input Karyawan
elseif ($modul=='dtakary' AND $act=='input'){
  $pass=md5($_POST[password]);
  mysql_query("INSERT INTO karyawan(
  								 id_alt,	
  								 nip,
                                 nama_karyawan,
								 jabatan,
								 divisi,
                                 alamat, 
                                 telp) 
	                       VALUES(
						        '$_POST[id_alt]',
								'$_POST[nip]',
                                '$_POST[nama_karyawan]',
								'$_POST[jabatan]',
								'$_POST[divisi]',
                                '$_POST[alamat]',
                                '$_POST[telp]')");
  mysql_query("INSERT INTO pengguna(
                                 id_alt,
								 nip,
  								 username,
								 password
								 ) 
	                       VALUES('$_POST[nip]',
                                '$_POST[username]',
								'$pass'
								)");
  header('location:../../indexs.php?modul='.$modul);
}

// Update Karyawan
elseif ($modul=='dtakary' AND $act=='update'){
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
	mysql_query("UPDATE karyawan SET
                                  nama_karyawan   = '$_POST[nama_karyawan]',
								  jabatan			 = '$_POST[jabatan]',
								  divisi		 = '$_POST[divisi]',
                                  alamat          = '$_POST[alamat]',
                                  telp         = '$_POST[telp]'  
                           WHERE  nip       = '$_POST[id]'");
    mysql_query("UPDATE pengguna SET password = '$pass' 
                           WHERE  username       = '$_POST[id1]'");
  }
  header('location:../../indexs.php?modul='.$modul);
}
?>

