<link rel='stylesheet' href='supports/style.css' />
<?php
/*
create table guest(
id int not null auto_increment primary key, 
name varchar(200), 
address varchar(255), 
email varchar(50)
);
 */
require 'config.php';$d = new doCrud();
$qualified=1;
if($_GET['insert']):
$d->insertData('guest');
elseif($_GET['update']):
$d->updateData('guest',$_POST['id']);
$d->listDatum('guest','','id='.$_POST['id']);
elseif($_GET['edit']): // edit contains ID of row
$d->editForm('guest','',$_GET['edit']);)
elseif($_GET['show']):
$d->listTable('guest','',5); // list tabular data
else:
$d->addForm('guest');
endif;
echo '<a href=?show=1>Show Data</a> -|- <a href=?add=1>Add Data</a>'
