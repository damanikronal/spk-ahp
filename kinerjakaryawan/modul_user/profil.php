<?php
$aksi="modul_user/aksi_profil.php";
$edit=mysql_query("SELECT k.nip,k.nama_karyawan,k.jabatan,k.divisi,k.alamat,k.telp,p.username,p.password
                  FROM karyawan k, pengguna p 
				  WHERE p.username='$_SESSION[namauser]' AND p.nip=k.nip
				  ");
$r=mysql_fetch_array($edit);

    echo "<h2>Edit Karyawan</h2>
          <form method=POST action=$aksi?modul=profil&act=update>
          <input type=hidden name=id value='$r[nip]'><input type=hidden name=id1 value='$r[username]'>
          <table>
		  <tr><td>NIP</td> <td> : <input type=text readonly name='nip' size=30 value='$r[nip]'></td></tr>
          <tr><td>Nama Lengkap</td> <td> : <input type=text name='nama_karyawan' size=30  value='$r[nama_karyawan]'></td></tr>
		  <tr><td>Jabatan</td> <td> : <input type=text name='jabatan' size=30 value='$r[jabatan]'></td></tr>
          <tr><td>Divisi</td>       <td> : <input type=text name='divisi' size=30 value='$r[divisi]'></td></tr>
          <tr><td>Alamat</td>   <td> : <input type=text name='alamat' size=30 value='$r[alamat]'></td></tr>
		  <tr><td>Telp</td>   <td> : <input type=text name='telp' size=30 value='$r[telp]'></td></tr>
		  <tr><td>Username</td>     <td> : <input type=text readonly name='username' value='$r[username]'></td></tr>
          <tr><td>Password</td>     <td> : <input type=text name='password'></td></tr>";
    
    echo "<tr><td colspan=2>*) Apabila password tidak diubah, dikosongkan saja.</td></tr>
          <tr><td colspan=2><input type=submit value=Update>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
?>

