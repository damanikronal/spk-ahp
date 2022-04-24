<?php
$aksi="modul_admin/mod_kriteria/aksi_kriteria.php";
switch($_GET[act]){
  // Tampil Kriteria
  default:
    echo "<h2>Manajemen Data Kriteria Penilaian</h2>
          <input type=button value='Tambah Kriteria' 
          onclick=\"window.location.href='?modul=kriteria&act=tambah';\">
          <table>
          <tr><th>id</th><th>kriteria</th><th>aksi</th></tr>"; 
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
    $tampil=mysql_query("SELECT * FROM kriteria ORDER BY id_kriteria ASC LIMIT $offset, $jmlperhalaman");
    while ($r=mysql_fetch_array($tampil)){
       echo "<tr><td>$r[id_kriteria]</td>
             <td>$r[nama_kriteria]</td>
             <td><a href=?modul=kriteria&act=edit&id=$r[id_kriteria]>Edit</a> | 
	               <a href=$aksi?modul=kriteria&act=hapus&id=$r[id_kriteria]>Hapus</a>
             </td></tr>";
    }
    echo "</table>";
	// membuat nomor halaman
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM kriteria"),0);
	$total_halaman = ceil($total_record / $jmlperhalaman);
	echo "<center>Halaman :<br/>"; 
	$perhal=4;
	if($hal > 1){ 
		$prev = ($page - 1); 
		echo "<a href=indexs.php?modul=kriteria&hal=$prev> << </a> "; 
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
				echo "<a href=indexs.php?modul=kriteria&hal=$i>$i</a> "; 
		}
		} 
	}
	if($hal < $total_halaman){ 
		$next = ($page + 1); 
		echo "<a href=indexs.php?modul=kriteria&hal=$next>>></a>"; 
	} 
	echo "</center><br/>";
    break;
  
  // Form Tambah Kategori
  case "tambah":
    echo "<h2>Tambah Kriteria</h2>
          <form method=POST action='$aksi?modul=kriteria&act=input'>
          <table>
          <tr><td>Id</td><td> : <input type=text name='id_kriteria'></td></tr>
		  <tr><td>Kriteria</td><td> : <input type=text name='nama_kriteria'></td></tr>
          <tr><td colspan=2><input type=submit name=submit value=Simpan>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
     break;
  
  // Form Edit Kategori  
  case "edit":
    $edit=mysql_query("SELECT * FROM kriteria WHERE id_kriteria='$_GET[id]'");
    $r=mysql_fetch_array($edit);

    echo "<h2>Edit Kriteria</h2>
          <form method=POST action=$aksi?modul=kriteria&act=update>
          <input type=hidden name=id value='$r[id_kriteria]'>
          <table>
          <tr><td>Id</td><td> : <input type=text readonly name='id_kriteria' value='$r[id_kriteria]'></td></tr>
		  <tr><td>Kriteria</td><td> : <input type=text name='nama_kriteria' value='$r[nama_kriteria]'></td></tr>
          <tr><td colspan=2><input type=submit value=Update>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
    break;  
}
?>

