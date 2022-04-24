<?php
$aksi="modul_admin/mod_evaluasi/aksi_evaluasi.php";
$jlhkriteria=mysql_result(mysql_query("SELECT COUNT(*) as Num FROM kriteria"),0);
$jlhalternatif = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM karyawan WHERE jabatan != 'Manajer'"),0); // Jumlah Alternatif
switch($_GET[acts]){
  // Tampil Karyawan
  default:
  $queri=mysql_query("SELECT * FROM konsistensi");
	$cr=mysql_num_rows($queri);
	if ($cr){
    echo "<h2>Perbandingan Karyawan (Alternatif) Untuk Setiap Kriteria</h2>
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
    $tampil=mysql_query("SELECT * FROM kriteria ORDER BY id_kriteria LIMIT $offset, $jmlperhalaman");
    while ($r=mysql_fetch_array($tampil)){
       echo "<tr>
             <td>$r[id_kriteria]</td>
             <td>$r[nama_kriteria]</td>
		     <td><a href=?modul=evaluasi&acts=edit&id=$r[id_kriteria]>Evaluasi Alternatif/Pilihan</a>
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
		echo "<a href=indexs.php?modul=evaluasi&hal=$prev> << </a> "; 
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
				echo "<a href=indexs.php?modul=evaluasi&hal=$i>$i</a> "; 
		}
		} 
	}
	if($hal < $total_halaman){ 
		$next = ($page + 1); 
		echo "<a href=indexs.php?modul=evaluasi&hal=$next>>></a>"; 
	} 
	echo "</center><br/>";
	}else{
		echo "Belum dilakukan perbandingan antar kriteria. 
		<br><a href=?modul=bobot&act=buatperbandingan>Buat Perbandingan Kriteria</a>";
	}
    break;
    
  case "edit":
    $edit=mysql_query("SELECT * FROM kriteria WHERE id_kriteria='$_GET[id]'");
    $r1=mysql_fetch_array($edit);
	$cek=mysql_query("SELECT * FROM evaluasi WHERE id_kriteria='$_GET[id]'");
    $jlh=mysql_num_rows($cek);
	$queri=mysql_query("SELECT * FROM konsistensi WHERE id_kriteria='$_GET[id]'");
	$cr=mysql_num_rows($queri);
	if ($cr){
		echo "<h2>Perbandingan Karyawan (Alternatif) Untuk Kriteria $r1[nama_kriteria]</h2>";
		echo "
			<table>
          <tr><th>id alternatif</th><th>nip</th><th>nilai</th></tr>"; 
		$tampil2=mysql_query("SELECT id_alt, nip, nilai FROM evaluasi WHERE id_kriteria='$_GET[id]' ORDER BY id_alt, nip ASC");
		while ($r2=mysql_fetch_array($tampil2)){
		   echo "<tr>
		   		 <td>$r2[id_alt]</td>
				 <td>$r2[nip]</td>
				 <td>$r2[nilai]
				 </td></tr>";
		}
		echo "</table>";
		echo "<br>";
		$tampil3 = mysql_query("SELECT * FROM konsistensi WHERE id_kriteria='$_GET[id]'");
		$r3=mysql_fetch_array($tampil3);
		echo "Consistency Ratio (CR) : $r3[cr] <br>";
		if ($r3[cr] <= 0.1){
			echo "Consistency Ratio dapat diterima (<=0.1)<br/>";
			echo "<form method='post' action='$aksi?modul=evaluasi&acts=hitunglagi&idkriteria=$_GET[id]'>
			<input type=submit value=Ulang></form><input type=button value=Kembali onclick=self.history.back()>";
		}
		else{
			echo "Consistency Ratio tidak dapat diterima (>0.1)<br/>";
			echo "Silahkan perbaiki kembali skala perbandingan alternatif penilaian<br/>";
			echo "<form method='post' action='$aksi?modul=evaluasi&acts=hitunglagi&idkriteria=$_GET[id]'>
				<input type=submit value=Ulang><input type=button value=Kembali onclick=self.history.back()></form>
				";
		}
		echo "<br>";
	}else {
		$id = $_GET[id];
		echo "<h2>Perbandingan Karyawan (Alternatif) Untuk Kriteria $r1[nama_kriteria]</h2>
          <form method=POST action=$aksi?modul=evaluasi&acts=input><input type=hidden name=id_kriteria value='$id'>
		  <br>Skor yang dipakai adalah Skala Perbandingan Saaty (1-9)
	     <br>
	     <table>
          <tr><td>1</td><td> : sama penting</td></tr>
		  <tr><td>3</td><td> : sedikit lebih penting</td></tr>
		  <tr><td>5</td><td> : lebih penting</td></tr>
		  <tr><td>7</td><td> : sangat penting</td></tr>
		  <tr><td>9</td><td> : mutlak sangat penting</td></tr>
		  <tr><td>2,4,6,8</td><td> : nilai antara dua nilai pertimbangan yg berdekatan</td></tr>
         </table>
		 <img src='images/contoh.jpg' width='513' height='34'>
          <table>
		  <tr><th>alternatif 1</th><th>alternatif 2</th><th>nilai (bobot alternatif)</th></tr>
		  ";
		for ($i=1; $i<=$jlhalternatif; $i++){
			$queri1=mysql_query("SELECT * FROM karyawan WHERE id_alt='$i' AND jabatan != 'Manajer' ORDER BY nip ASC");
			$alternatif1=mysql_fetch_array($queri1);
			for ($j=$i+1; $j<=$jlhalternatif; $j++){
			$queri2=mysql_query("SELECT * FROM karyawan WHERE id_alt='$j' AND jabatan != 'Manajer' ORDER BY nip ASC");
			$alternatif2=mysql_fetch_array($queri2);
				echo "<tr>";
				echo "<td><input type='radio' name='Matriks".$i.$j."' value='kanan'>&nbsp;$alternatif1[nama_karyawan]<input type=hidden name='id_alt".$i.$j."' value='$r[id_alt]'></td>
					  <td><input type='radio' name='Matriks".$i.$j."' value='kiri'>&nbsp;$alternatif2[nama_karyawan]<input type=hidden name='id_alt".$i.$j."' value='$r[id_alt]'></td>
					  <td><select name='M".$i.$j."'>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
					  </select></td>
					 ";
				echo "</tr>";
			}
		}
		echo "
			  <tr><td colspan=2><input type=submit value=Submit>
								<input type=button value=Batal onclick=self.history.back()><input type=hidden name=jlhalternatif value='$jlhalternatif'></td></tr>
			  </table>";
		
	   echo "</form>";
	}
    break;
}
?>
