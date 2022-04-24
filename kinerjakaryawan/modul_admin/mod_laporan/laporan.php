<?php
$queri=mysql_query("SELECT * FROM hasil_evaluasi");
$hasil=mysql_num_rows($queri);
if ($hasil){
    echo "<h2>Rekomendasi Ranking Karyawan Berprestasi</h2>
          <table>
          <tr><th>nip</th><th>nama lengkap</th><th>jabatan</th><th>divisi</th><th>nilai</th><th>aksi</th></tr>"; 
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
    $tampil=mysql_query("SELECT h.nip,k.nama_karyawan,k.jabatan,k.divisi,h.total_nilai FROM karyawan k, hasil_evaluasi h
						 WHERE k.jabatan != 'manajer' AND h.nip=k.nip
						 ORDER BY h.total_nilai DESC LIMIT $offset, $jmlperhalaman");
    while ($r=mysql_fetch_array($tampil)){
       echo "<tr>
             <td>$r[nip]</td>
             <td>$r[nama_karyawan]</td>
			 <td>$r[jabatan]</td>
			 <td>$r[divisi]</td>
			 <td>$r[total_nilai]
             </td></tr>";
    }
    echo "</table>";
	// membuat nomor halaman
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM hasil_evaluasi"),0);
	$total_halaman = ceil($total_record / $jmlperhalaman);
	echo "<center>Halaman :<br/>"; 
	$perhal=4;
	if($hal > 1){ 
		$prev = ($page - 1); 
		echo "<a href=indexs.php?modul=laporan&hal=$prev> << </a> "; 
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
				echo "<a href=indexs.php?modul=laporan&hal=$i>$i</a> "; 
		}
		} 
	}
	if($hal < $total_halaman){ 
		$next = ($page + 1); 
		echo "<a href=indexs.php?modul=laporan&hal=$next>>></a>"; 
	} 
	echo "</center><br/>";
}
else{
	echo "Silahkan hitung kembali skor penilaian karyawan<br/>";
	echo "<form method='post' action='?modul=evaluasi'>
	<input type=submit value=Ulang></form>
	";
}

?>
