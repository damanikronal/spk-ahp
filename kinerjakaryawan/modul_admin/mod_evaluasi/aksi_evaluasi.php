<?php
session_start();
include "../../config/koneksi.php";

$modul=$_GET[modul];
$acts=$_GET[acts];
$jlhalternatif=$_POST[jlhalternatif];
$id_kriteria=$_POST[id_kriteria];
// Input nilai
if ($modul=='evaluasi' AND $acts=='input'){
  // Membuat Matrik Perbandingan Antar Alternatif Untuk Setiap Kriteria
  // =========================================================
  // Step 1 : Menyimpan Nilai Diagonal Matrik Perbandingan = 1
  for ($i=1; $i<=$$jlhalternatif; $i++){
	for ($j=$i; $j<$i+1; $j++){
		$M[$i][$j] = 1;
		$diagonal = $M[$i][$j];
		//mysql_query("INSERT INTO matrik_kriteria(id_kriteria,id_bandingan,nilai) VALUES('$i', '$j', '$diagonal')");
	}
  }
  // Step 2 : Matriks perbandingan kanan atas
  for ($i=1; $i<=$jlhalternatif; $i++){
	for ($j=$i+1; $j<=$jlhalternatif; $j++){
		$u = $_POST['Matriks'.$i.$j];
		if ($u == 'kanan'){
			$tem[$i][$j] = $_POST['M'.$i.$j];
		}
		elseif ($u == 'kiri'){
			$tem[$i][$j] = round ((1 / $_POST['M'.$i.$j]),2);
		}
		$M[$i][$j] = $tem[$i][$j];
		$kananatas = $M[$i][$j];
		//mysql_query("INSERT INTO matrik_kriteria(id_kriteria,id_bandingan,nilai) VALUES('$i', '$j', '$kananatas')");
	}
  }
  // Step 3 : Mencari nilai matriks kiri bawah
  for ($i=$jlhalternatif; $i>0; $i--){ // matriks kiri bawah
	$M[$i][$i] = 1;
	for ($j=$i-1; $j>0; $j--){
		$M[$j][$i] = $M[$j][$i];
		$M[$i][$j] = round (($M[$i][$i] / $M[$j][$i]),2);
		$kiribawah = $M[$i][$j];
		//mysql_query("INSERT INTO matrik_kriteria(id_kriteria,id_bandingan,nilai) VALUES('$i', '$j', '$kiribawah')");
	}
  }
  
  // Sintesis
  // =========================================================
  // 1. Buat matrik transpose MT[i][j]
	for ($i=1;$i<=$jlhalternatif;$i++){
		for ($j=1;$j<=$jlhalternatif;$j++){
			$MT[$i][$j] = $M[$j][$i];
		}
	}
	// 2. Jumlahkan setiap baris matrik transpose MT
	for ($i=1;$i<=$jlhalternatif;$i++){
		$total=0;
		for ($j=1;$j<=$jlhalternatif;$j++){
			$total = $total+$MT[$i][$j];
		}
		$sum[$i]=$total;
	}
	// 3. Buat Matrik Normalisasi MC[i][j]
	for ($i=1;$i<=$jlhalternatif;$i++){
		for ($j=1;$j<=$jlhalternatif;$j++){
			$MC[$i][$j] = round (($MT[$i][$j] / $sum[$i]),3);
			$normalisasi = $MC[$i][$j];
			//mysql_query("INSERT INTO matrik_normalisasi_kriteria(id_kriteria,id_bandingan,nilai) VALUES('$i', '$j', '$normalisasi')");
		}
	}
	// 4. Jumlahkan setiap baris matrik MC[i][j]
	for ($i=1;$i<=$jlhalternatif;$i++){
		$totalc=0;
		for ($j=1;$j<=$jlhalternatif;$j++){
			$totalc = $totalc+$MC[$j][$i];
		}
		$sumc[$i]=$totalc;
	}
	// Prioritas alternatif
	for ($i=1;$i<=$jlhalternatif;$i++){
		$ave[$i] = round (($sumc[$i] / $jlhalternatif),3);
		$nilai = $ave[$i];
		//$persentase[$i]=$ave[$i]*100;
		$kueri=mysql_query("SELECT nip FROM karyawan WHERE id_alt='$i'");
		$nip=mysql_fetch_array($kueri);
		mysql_query("INSERT INTO evaluasi(nip,id_alt,id_kriteria,nilai) VALUES('$nip[nip]','$i','$id_kriteria','$nilai')");
	}
	
	// Menghitung Rasio Konsistensi (CR)
  // =========================================================
  // 1. Matrik MB[i][j]
	for ($i=1;$i<=$jlhalternatif;$i++){
		for ($j=1;$j<=$jlhalternatif;$j++){
			$MB[$i][$j] = round (($MT[$i][$j] * $ave[$i]),3);
		}
	}
	// 2. Jumlahkan tiap baris matrik MB[j][i]
	for ($i=1;$i<=$jlhalternatif;$i++){
		$totalb=0;
		for ($j=1;$j<=$jlhalternatif;$j++){
			$totalb = $totalb+$MB[$j][$i];
		}
		$sumb[$i]=$totalb;
	}
	// 3. Bagi sumb dengan ave
	for ($i=1;$i<=$jlhalternatif;$i++){
		$tot[$i] = round (($sumb[$i] / $ave[$i]),3);
	}
	// 4. Jumlahkan semua nilai tot
	$jumlah=0;
	for ($i=1;$i<=$jlhalternatif;$i++){
		$jumlah += $tot[$i];
	}
	// 5. Hitung lamda maks
	$lamda = round (($jumlah/$jlhalternatif),3);
	// 6. Hitung CI
	$ci = round ((($lamda-$jlhalternatif)/($jlhalternatif-1)),3);
	// 7. Hitung CR
	switch ($jlhalternatif){
		case 3:
			$ir = 0.58;
			$cr = round (($ci/$ir),3);
			break;
		case 4:
			$ir = 0.90;
			$cr = round (($ci/$ir),3);
			break;
		case 5:
			$ir = 1.12;
			$cr = round (($ci/$ir),3);
			break;
		case 6:
			$ir = 1.24;
			$cr = round (($ci/$ir),3);
			break;
		case 7:
			$ir = 1.32;
			$cr = round (($ci/$ir),3);
			break;
		case 8:
			$ir = 1.41;
			$cr = round (($ci/$ir),3);
			break;
		case 9:
			$ir = 1.45;
			$cr = round (($ci/$ir),3);
			break;
		case 10:
			$ir = 1.49;
			$cr = round (($ci/$ir),3);
			break;
	}
	mysql_query("INSERT INTO konsistensi(cr,id_kriteria) VALUES('$cr','$id_kriteria')");
  header('location:../../indexs.php?modul='.$modul);
}

// hitung lagi
elseif ($modul=='evaluasi' AND $acts=='hitunglagi'){
  mysql_query("DELETE FROM konsistensi WHERE id_kriteria='$_GET[idkriteria]'");
  mysql_query("DELETE FROM evaluasi WHERE id_kriteria='$_GET[idkriteria]'");
  header('location:../../indexs.php?modul='.$modul);
}

// Update nilai
elseif ($modul=='evaluasi' AND $acts=='update'){
  for($i=1;$i<=$jlhkriteria;$i++){
  	$nilai = $_POST['nilai'.$i];
	$id_kriteria = $_POST['id_kriteria'.$i];
	mysql_query("UPDATE evaluasi SET nip = '$_POST[nip]', id_kriteria = '$id_kriteria', nilai = '$nilai' WHERE nip = '$_POST[nip]' AND id_kriteria = '$id_kriteria'");  
  }
  //Total Nilai
  $total=0;
  for($i=1;$i<=$jlhkriteria;$i++){
  	$nilai = $_POST['nilai'.$i];
	$id_kriteria = $_POST['id_kriteria'.$i];
	$tampil=mysql_query("SELECT bobot FROM bobot_kriteria WHERE id_kriteria='$id_kriteria'");
	$r=mysql_fetch_array($tampil);
	$skor_total=$nilai*$r[bobot];
	$total += $skor_total;
  }
  mysql_query("INSERT INTO hasil_evaluasi(nip, total_nilai) VALUES('$_POST[nip]', '$total')");
  header('location:../../indexs.php?modul='.$modul);
}
?>

