<link rel='stylesheet' href='supports/style.css' />
<?php
require 'config.php';$d = new doCrud();
$qualified=1;
if($_GET['insert']):
$d->insertData('pasien');
elseif($_GET['update']):
$d->updateData('pasien',$_POST['id']);
$d->listDatum('pasien','','id='.$_POST['id']);
elseif($_GET['edit']): // edit contains ID of row
$d->editForm('pasien','',$_GET['edit']);)
elseif($_GET['show']):
$d->listTable('pasien','',5); // list tabular data
else:
$d->addForm('guest');
endif;
echo '<a href=?show=1>Show Data</a> -|- <a href=?add=1>Add Data</a>'
