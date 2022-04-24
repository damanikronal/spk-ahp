<?php
include "config/koneksi.php";

if ($_SESSION[leveluser]=='admin'){
  $sql=mysql_query("select * from menu where aktif='y' and status='admin' order by urutan");
}
else{
  $sql=mysql_query("select * from menu where status='user' and aktif='y' order by urutan"); 
} 
while ($m=mysql_fetch_array($sql)){  
  echo "<li><a href='$m[link]'>&#187; $m[menu]</a></li>";
}
?>
