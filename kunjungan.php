<html>
<head>
<title>Kunjungan</title>
<link rel=stylesheet href='supports/style.css'>
<script src='supports/jquery.js'></script>
</head>
<body>
<?php
/*
CREATE TABLE IF NOT EXISTS `desa`(
    `kode` varchar(10) NOT NULL,
    `nama` varchar(1255) NOT NULL
);
// then insert some data
CREATE TABLE IF NOT EXISTS `kunjungan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `kdesa` varchar(10) NOT NULL,
  `umum_l` int(11) NOT NULL,
  `umum_p` int(11) NOT NULL,
  `askes_l` int(11) NOT NULL,
  `askes_p` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);
*/
require 'config.php';
$d = new doCrud();

// pilih kode,nama desa untuk dropdown
$d->execute("select kode,nama from desa order by nama");
$opsi['']='_';
while($rec=$d->nextRec()){
$opsi[$rec['kode']]=$rec['nama'];
$opsi1.="<option value=".$rec['kode'].">".$rec['nama']."</option>";
}
$dt=json_encode($opsi);
// 
// alias field, menampilkan label agar berbeda dengan nama field
$alias = array(
	'tanggal'=>'Tanggal',
	'kdesa'=>'Kode Kelurahan',
	'umum_l'=>'Jml Px Umum (L)',
	'umum_p'=>'Jml Px Umum (P)',
	'askes_l'=>'Jml Px ASKES (L)',
	'askes_p'=>'Jml Px ASKES (P)'
		);

// masukan data
if($_GET['insert']==1){
	$d->insertData('kunjungan');
}

// update data
elseif($_GET['update']==1){
	$d->updateData('kunjungan',$_POST['id']);
}

// daftar masukan
elseif($_GET['lihat']==1){
	$qualified = 1;
	$qs = 'lihat';
	$d->listTable('kunjungan',$alias,'5'); // 5 adalah jumlah baris yg akan ditampilkan
}

// edit data
elseif(!empty($_GET['edit'])){
	$qualified = 1;
	$d->editForm('kunjungan',$alias,$_GET['edit']);
}
else
// form masukan data
$d->addForm('kunjungan',$alias);
echo "<a href=\"?lihat=1\">Lihat data</a> -|- ";
echo "<a href=\"?tambah=1\">Tambah data</a>";

// javascript berikut ini mengubah input kdesa menjadi dropdown (termasuk dalam form edit, memunculkan pilihan nilai dari database di posisi pertama/selected)
?>
</body>
<script type="text/javascript">
ds=$("#kdesa").val();
if(ds.length==0) ds='_';
dx=<?=$dt?>;
$("#kdesa").replaceWith("<select name='kdesa'><option value="+ds+">"+eval("dx."+ds)+"</option><?=$opsi1?></select>");
</script>
</html>
