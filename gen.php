<?php
error_reporting(0);
require 'config.php';
//var_dump($argv);
switch($argv[1]){
case 'crud':
    if(empty($argv[2])) die('\nSyntax: gen.php crud table_name\n\n');
    $file=fopen($argv[2].'.php','w+');
    fputs($file,"<link rel='stylesheet' href='supports/style.css' />\n");
    fputs($file,"<?php\n");
    fputs($file,"require 'config.php';");
    fputs($file,"\$d = new doCrud();\n"); 
    fputs($file,"\$qualified=1;\n"); 
    fputs($file,"if(\$_GET['insert']):\n");
    fputs($file,"\$d->insertData('".$argv[2]."');\n");
    fputs($file,"elseif(\$_GET['update']):\n"); 
    fputs($file,"\$d->updateData('".$argv[2]."',\$_POST['id']);\n"); 
    fputs($file,"\$d->listDatum('".$argv[2]."','','id='.\$_POST['id']);\n");
    fputs($file,"elseif(\$_GET['edit']): // edit contains ID of row\n"); 
    fputs($file,"\$d->editForm('".$argv[2]."','',\$_GET['edit']);\n"); 
    fputs($file,"elseif(\$_GET['show']):\n");
    fputs($file,"\$d->listTable('".$argv[2]."','',5); // list tabular data\n");
    fputs($file,"else:\n"); 
    fputs($file,"\$d->addForm('".$argv[2]."');\n"); 
	fputs($file,"endif;\n");
	fputs($file,"echo '<a href=?show=1>Show Data</a> -|- <a href=?add=1>Add Data</a>';\n"); 

break;
default:
    echo "\nSyntax: gen.php crud table_name\n\n";
break;
}
