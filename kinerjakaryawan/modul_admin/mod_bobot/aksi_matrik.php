<?php
session_start();
include "../../config/koneksi.php";

$modul=$_GET[modul];
$act=$_GET[act];
$jlhkriteria = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM kriteria"),0); // Jumlah Kriteria

// Hitung Matrik Perbandingan
if ($modul=='bobot' AND $act=='matrikperbandingan'){
  // Membuat Matrik Perbandingan
  // =========================================================
  // Step 1 : Menyimpan Nilai Diagonal Matrik Perbandingan = 1
  for ($i=1; $i<=$jlhkriteria; $i++){
	for ($j=$i; $j<$i+1; $j++){
		$M[$i][$j] = 1;
		$diagonal = $M[$i][$j];
		mysql_query("INSERT INTO matrik_kriteria(id_kriteria,id_bandingan,nilai) VALUES('$i', '$j', '$diagonal')");
	}
  }
  // Step 2 : Matriks perbandingan kanan atas
  for ($i=1; $i<=$jlhkriteria; $i++){
	for ($j=$i+1; $j<=$jlhkriteria; $j++){
		$u = $_POST['Matriks'.$i.$j];
		if ($u == 'kanan'){
			$tem[$i][$j] = $_POST['M'.$i.$j];
		}
		elseif ($u == 'kiri'){
			$tem[$i][$j] = round ((1 / $_POST['M'.$i.$j]),2);
		}
		$M[$i][$j] = $tem[$i][$j];
		$kananatas = $M[$i][$j];
		mysql_query("INSERT INTO matrik_kriteria(id_kriteria,id_bandingan,nilai) VALUES('$i', '$j', '$kananatas')");
	}
  }
  // Step 3 : Mencari nilai matriks kiri bawah
  for ($i=$jlhkriteria; $i>0; $i--){ // matriks kiri bawah
	$M[$i][$i] = 1;
	for ($j=$i-1; $j>0; $j--){
		$M[$j][$i] = $M[$j][$i];
		$M[$i][$j] = round (($M[$i][$i] / $M[$j][$i]),2);
		$kiribawah = $M[$i][$j];
		mysql_query("INSERT INTO matrik_kriteria(id_kriteria,id_bandingan,nilai) VALUES('$i', '$j', '$kiribawah')");
	}
  }
  
  // Sintesis
  // =========================================================
  // 1. Buat matrik transpose MT[i][j]
	for ($i=1;$i<=$jlhkriteria;$i++){
		for ($j=1;$j<=$jlhkriteria;$j++){
			$MT[$i][$j] = $M[$j][$i];
		}
	}
	// 2. Jumlahkan setiap baris matrik transpose MT
	for ($i=1;$i<=$jlhkriteria;$i++){
		$total=0;
		for ($j=1;$j<=$jlhkriteria;$j++){
			$total = $total+$MT[$i][$j];
		}
		$sum[$i]=$total;
	}
	// 3. Buat Matrik Normalisasi MC[i][j]
	for ($i=1;$i<=$jlhkriteria;$i++){
		for ($j=1;$j<=$jlhkriteria;$j++){
			$MC[$i][$j] = round (($MT[$i][$j] / $sum[$i]),3);
			$normalisasi = $MC[$i][$j];
			mysql_query("INSERT INTO matrik_normalisasi_kriteria(id_kriteria,id_bandingan,nilai) VALUES('$i', '$j', '$normalisasi')");
		}
	}
	// 4. Jumlahkan setiap baris matrik MC[i][j]
	for ($i=1;$i<=$jlhkriteria;$i++){
		$totalc=0;
		for ($j=1;$j<=$jlhkriteria;$j++){
			$totalc = $totalc+$MC[$j][$i];
		}
		$sumc[$i]=$totalc;
	}
	// Prioritas kriteria
	for ($i=1;$i<=$jlhkriteria;$i++){
		$ave[$i] = round (($sumc[$i] / $jlhkriteria),3);
		$nilai = $ave[$i];
		//$persentase[$i]=$ave[$i]*100;
		mysql_query("UPDATE bobot_kriteria SET bobot = '$nilai' WHERE id_kriteria = '$i'");
	}
	
	// Menghitung Rasio Konsistensi (CR)
  // =========================================================
  // 1. Matrik MB[i][j]
	for ($i=1;$i<=$jlhkriteria;$i++){
		for ($j=1;$j<=$jlhkriteria;$j++){
			$MB[$i][$j] = round (($MT[$i][$j] * $ave[$i]),3);
		}
	}
	// 2. Jumlahkan tiap baris matrik MB[j][i]
	for ($i=1;$i<=$jlhkriteria;$i++){
		$totalb=0;
		for ($j=1;$j<=$jlhkriteria;$j++){
			$totalb = $totalb+$MB[$j][$i];
		}
		$sumb[$i]=$totalb;
	}
	// 3. Bagi sumb dengan ave
	for ($i=1;$i<=$jlhkriteria;$i++){
		$tot[$i] = round (($sumb[$i] / $ave[$i]),3);
	}
	// 4. Jumlahkan semua nilai tot
	$jumlah=0;
	for ($i=1;$i<=$jlhkriteria;$i++){
		$jumlah += $tot[$i];
	}
	// 5. Hitung lamda maks
	$lamda = round (($jumlah/$jlhkriteria),3);
	// 6. Hitung CI
	$ci = round ((($lamda-$jlhkriteria)/($jlhkriteria-1)),3);
	// 7. Hitung CR
	switch ($jlhkriteria){
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
	mysql_query("INSERT INTO konsistensi(cr) VALUES('$cr')");
  header('location:../../indexs.php?modul='.$modul);
}

// Lanjut ke evaluasi karyawan
elseif ($modul=='bobot' AND $act=='lanjutkan'){
  header('location:../../indexs.php?modul=evaluasi');
}

// hitung lagi
elseif ($modul=='bobot' AND $act=='hitunglagi'){
  mysql_query("DELETE FROM konsistensi");
  mysql_query("DELETE FROM matrik_kriteria");
  mysql_query("DELETE FROM matrik_normalisasi_kriteria");
  mysql_query("DELETE FROM hasil_evaluasi");
  header('location:../../indexs.php?modul='.$modul);
}

?>

