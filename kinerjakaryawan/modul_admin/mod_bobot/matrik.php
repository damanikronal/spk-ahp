<?php
$aksi="modul_admin/mod_bobot/aksi_matrik.php";
switch($_GET[act]){
  // Tampil Matrik Perbandingan Kriteria
  default:
    $queri=mysql_query("SELECT * FROM konsistensi");
	$cr=mysql_num_rows($queri);
	if ($cr){
	    echo "<h2>Matrik Perbandingan Kriteria</h2>";
		echo "
			<table>
          <tr><th>kriteria 1</th><th>kriteria 2</th><th>nilai</th></tr>"; 
		$tampil=mysql_query("SELECT * FROM matrik_kriteria ORDER BY id_kriteria");
		while ($r=mysql_fetch_array($tampil)){
		   echo "<tr><td>$r[id_kriteria]</td>
				 <td>$r[id_bandingan]</td>
				 <td>$r[nilai]
				 </td></tr>";
		}
		echo "</table>";
		echo "<br>";
		echo "<h2>Matrik Normalisasi Kriteria</h2>";
		echo "
			<table>
          <tr><th>kriteria 1</th><th>kriteria 2</th><th>nilai</th></tr>"; 
		$tampil1=mysql_query("SELECT * FROM matrik_normalisasi_kriteria ORDER BY id_kriteria");
		while ($r1=mysql_fetch_array($tampil1)){
		   echo "<tr><td>$r1[id_kriteria]</td>
				 <td>$r1[id_bandingan]</td>
				 <td>$r1[nilai]
				 </td></tr>";
		}
		echo "</table>";
		echo "<br>";
		echo "<h2>Bobot Akhir Kriteria</h2>";
		echo "
			<table>
          <tr><th>kriteria</th><th>nilai</th></tr>"; 
		$tampil2=mysql_query("SELECT nama_kriteria, bobot FROM bobot_kriteria ORDER BY id_kriteria");
		while ($r2=mysql_fetch_array($tampil2)){
		   echo "<tr><td>$r2[nama_kriteria]</td>
				 <td>$r2[bobot]
				 </td></tr>";
		}
		echo "</table>";
		echo "<br>";
		$jlhkriteria = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM kriteria"),0); // Jumlah Kriteria
		if ($jlhkriteria == 1){
			echo "Consistency Ratio tidak perlu dihitung karena matrik 1X1<br/>";
			echo "<form method='post' action='$aksi?modul=bobot&act=lanjutkan'>
				<input type=submit value=Lanjut></form>
				<form method='post' action='$aksi?modul=bobot&act=hitunglagi'>
					<input type=submit value=Ulang></form>";
		} else if ($jlhkriteria == 2){
			echo "Consistency Ratio tidak perlu dihitung karena matrik 2X2<br/>";
			echo "<form method='post' action='$aksi?modul=bobot&act=lanjutkan'>
				<input type=submit value=Lanjut></form>
				<form method='post' action='$aksi?modul=bobot&act=hitunglagi'>
					<input type=submit value=Ulang></form>";
		}
		else if ($jlhkriteria > 2){
			$tampil3 = mysql_query("SELECT * FROM konsistensi");
			$r3=mysql_fetch_array($tampil3);
			echo "Consistency Ratio (CR) : $r3[cr] <br>";
			if ($r3[cr] <= 0.1){
				echo "Consistency Ratio dapat diterima (<=0.1)<br/>";
				echo "<form method='post' action='$aksi?modul=bobot&act=lanjutkan'>
					<input type=submit value=Lanjut></form>
					<form method='post' action='$aksi?modul=bobot&act=hitunglagi'>
					<input type=submit value=Ulang></form>";
			}
			else{
				echo "Consistency Ratio tidak dapat diterima (>0.1)<br/>";
				echo "Silahkan perbaiki kembali skala perbandingan kriteria penilaian<br/>";
				echo "<form method='post' action='$aksi?modul=bobot&act=hitunglagi'>
					<input type=submit value=Ulang></form>
				";
			}
		}
		echo "<br>";
	}
	else{
		echo "Belum dilakukan perbandingan antar kriteria. 
		<br><a href=?modul=bobot&act=buatperbandingan>Buat Perbandingan Kriteria</a>";
	}
    break;
  
  // Matrik Perbandingan
  case "buatperbandingan":
    echo "<h2>Matrik Perbandingan Berpasangan</h2>
	<br>Skor yang dipakai adalah Skala Perbandingan Saaty (1-9)
	     <br>
	     <table>
          <tr><td>1</td><td> : sama penting</td></tr>
		  <tr><td>3</td><td> : sedikit lebih penting</td></tr>
		  <tr><td>5</td><td> : lebih penting</td></tr>
		  <tr><td>7</td><td> : sangat penting</td></tr>
		  <tr><td>9</td><td> : mutlak sangat penting</td></tr>
		  <tr><td>2,4,6,8</td><td> : nilai antara dua nilai pertimbangan yg berdekatan</td></tr>
         </table>";
    echo "<form method='post' action='$aksi?modul=bobot&act=matrikperbandingan'>
	<img src='images/contoh.jpg' width='513' height='34'> <br> 
		  <table>
          <tr><th>kriteria 1</th><th>kriteria 2</th><th>nilai (bobot kriteria)</th></tr>"; 
    $jlhkriteria = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM kriteria"),0); // Jumlah Kriteria
	for ($i=1; $i<=$jlhkriteria; $i++){
		$queri1=mysql_query("SELECT * FROM kriteria WHERE id_kriteria='$i' ORDER BY id_kriteria ASC");
		$kriteria1=mysql_fetch_array($queri1);
		for ($j=$i+1; $j<=$jlhkriteria; $j++){
		$queri2=mysql_query("SELECT * FROM kriteria WHERE id_kriteria='$j' ORDER BY id_kriteria ASC");
		$kriteria2=mysql_fetch_array($queri2);
			echo "<tr>";
			echo "<td><input type='radio' name='Matriks".$i.$j."' value='kanan'>&nbsp;$kriteria1[nama_kriteria]<input type=hidden name='id_kriteria".$i.$j."' value='$r[id_kriteria]'></td>
				  <td><input type='radio' name='Matriks".$i.$j."' value='kiri'>&nbsp;$kriteria2[nama_kriteria]<input type=hidden name='id_kriteria".$i.$j."' value='$r[id_kriteria]'></td>
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
    echo "</table>
	<input type='submit' name='Submit' value='Submit'><input type=button value=Batal onclick=self.history.back()></form>";
	break;
}
?>