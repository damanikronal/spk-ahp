<?php
$aksi="modul_admin/mod_karyawan/aksi_karyawan.php";
switch($_GET[act]){
  // Tampil Karyawan
  default:
    echo "<h2>Manajemen Data Karyawan</h2>
          <input type=button value='Tambah Karyawan' onclick=\"window.location.href='?modul=dtakary&act=tambah';\">
          <table>
          <tr><th>id alternatif</th><th>nip</th><th>nama lengkap</th><th>jabatan</th><th>divisi</th><th>aksi</th></tr>"; 
	// Paging
  	$hal = $_GET[hal];
	if(!isset($_GET['hal'])){ 
		$page = 1; 
		$hal = 1;
	} else { 
		$page = $_GET['hal']; 
	}
	$jmlperhalaman = 10;  // jumlah record per halaman
	$offset = (($page * $jmlperhalaman) - $jmlperhalaman);
    $tampil=mysql_query("SELECT id_alt,nip,nama_karyawan,jabatan,divisi FROM karyawan ORDER BY nip LIMIT $offset, $jmlperhalaman");
    while ($r=mysql_fetch_array($tampil)){
       echo "<tr>
             <td>$r[id_alt]</td>
			 <td>$r[nip]</td>
             <td>$r[nama_karyawan]</td>
			 <td>$r[jabatan]</td>
			 <td>$r[divisi]</td>
		     <td><a href=?modul=dtakary&act=edit&id=$r[nip]>Edit</a> | 
	               <a href=$aksi?modul=dtakary&act=hapus&id=$r[nip]>Hapus</a>
             </td></tr>";
      $no++;
    }
    echo "</table>";
	// membuat nomor halaman
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM karyawan"),0);
	$total_halaman = ceil($total_record / $jmlperhalaman);
	echo "<center>Halaman :<br/>"; 
	$perhal=4;
	if($hal > 1){ 
		$prev = ($page - 1); 
		echo "<a href=indexs.php?modul=dtakary&hal=$prev> << </a> "; 
	}
	if($total_halaman<=10){
	$hal1=1;
	$hal2=$total_halaman;
	}else{
	$hal1=$hal-$perhal;
	$hal2=$hal+$perhal;
	}
	if($hal<=5){
	$hal1=1;
	}
	if($hal<$total_halaman){
	$hal2=$hal+$perhal;
	}else{
	$hal2=$hal;
	}
	for($i = $hal1; $i <= $hal2; $i++){ 
		if(($hal) == $i){ 
			echo "[<b>$i</b>] "; 
			} else { 
		if($i<=$total_halaman){
				echo "<a href=indexs.php?modul=dtakary&hal=$i>$i</a> "; 
		}
		} 
	}
	if($hal < $total_halaman){ 
		$next = ($page + 1); 
		echo "<a href=indexs.php?modul=dtakary&hal=$next>>></a>"; 
	} 
	echo "</center><br/>";
    break;
  
  case "tambah":
    echo "<h2>Tambah Karyawan</h2>
          <form method=POST action='$aksi?modul=dtakary&act=input'>
          <table>
		  <tr><td>Id_Alt</td>     <td> : <input type=text name='id_alt'></td></tr>
          <tr><td>NIP</td>     <td> : <input type=text name='nip'></td></tr>
          <tr><td>Nama Lengkap</td> <td> : <input type=text name='nama_karyawan' size=30></td></tr>
		  <tr><td>Jabatan</td> <td> : <input type=text name='jabatan' size=30></td></tr>
		  <tr><td>Divisi</td> <td> : <input type=text name='divisi' size=30></td></tr>  
          <tr><td>Alamat</td>       <td> : <input type=text name='alamat' size=30></td></tr>
          <tr><td>Telepon</td>   <td> : <input type=text name='telp' size=20></td></tr>
		  <tr><td>Username</td>   <td> : <input type=text name='username' size=20></td></tr>
		  <tr><td>Password</td>   <td> : <input type=password name='password' size=20></td></tr>
		  <tr><td colspan=2><input type=submit value=Simpan>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
     break;
    
  case "edit":
    $edit=mysql_query("SELECT * FROM karyawan WHERE nip='$_GET[id]'");
    $r=mysql_fetch_array($edit);
	$edit1=mysql_query("SELECT * FROM pengguna WHERE nip='$_GET[id]'");
    $r1=mysql_fetch_array($edit1);

    echo "<h2>Edit Karyawan</h2>
          <form method=POST action=$aksi?modul=dtakary&act=update>
          <input type=hidden name=id value='$r[nip]'><input type=hidden name=id1 value='$r1[username]'>
          <table>
		  <tr><td>Id_Alt</td> <td> : <input type=text readonly name='id_alt' size=30 value='$r[id_alt]'></td></tr>
		  <tr><td>NIP</td> <td> : <input type=text readonly name='nip' size=30 value='$r[nip]'></td></tr>
          <tr><td>Nama Lengkap</td> <td> : <input type=text name='nama_karyawan' size=30  value='$r[nama_karyawan]'></td></tr>
		  <tr><td>Jabatan</td> <td> : <input type=text name='jabatan' size=30 value='$r[jabatan]'></td></tr>
          <tr><td>Divisi</td>       <td> : <input type=text name='divisi' size=30 value='$r[divisi]'></td></tr>
          <tr><td>Alamat</td>   <td> : <input type=text name='alamat' size=30 value='$r[alamat]'></td></tr>
		  <tr><td>Telp</td>   <td> : <input type=text name='telp' size=30 value='$r[telp]'></td></tr>
		  <tr><td>Username</td>     <td> : <input type=text readonly name='username' value='$r1[username]'></td></tr>
          <tr><td>Password</td>     <td> : <input type=text name='password'></td></tr>";

    if ($r1[level]=='admin'){
      echo "<tr><td>Level</td>     <td> : <input type=radio name='level' value='admin' checked> Admin   
                                           <input type=radio name='level' value='user'> User </td></tr>";
    }
    else{
      echo "<tr><td>Level</td>     <td> : <input type=radio name='level' value='admin'> Admin  
                                           <input type=radio name='level' value='user' checked> User </td></tr>";
    }
    
    echo "<tr><td colspan=2>*) Apabila password tidak diubah, dikosongkan saja.</td></tr>
          <tr><td colspan=2><input type=submit value=Update>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
    break;  
}
?>
