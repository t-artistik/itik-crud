<?
// edit these:
define(HOST,'localhost');
define(USER,'root');
define(PASW,'');
define(DBAS,"test");

require_once("supports/crud.php");

$m['Januari']='01';
$m['Februari']='02';
$m['Maret']='03';
$m['April']='04';
$m['Mei']='05';
$m['Juni']='06';
$m['Juli']='07';
$m['Agustus']='08';
$m['September']='09';
$m['Oktober']='10';
$m['November']='11';
$m['Desember']='12';

$b['01']="Januari";
$b['02']="Februari";
$b['03']="Maret";
$b['04']="April";
$b['05']="Mei";
$b['06']="Juni";
$b['07']="Juli";
$b['08']="Agustus";
$b['09']="September";
$b['10']="Oktober";
$b['11']="November";
$b['12']="Desember";

$bulan  = "<option>Januari</option>";
$bulan .= "<option>Februari</option>";
$bulan .= "<option>Maret</option>";
$bulan .= "<option>April</option>";
$bulan .= "<option>Mei</option>";
$bulan .= "<option>Juni</option>";
$bulan .= "<option>Juli</option>";
$bulan .= "<option>Agustus</option>";
$bulan .= "<option>September</option>";
$bulan .= "<option>Oktober</option>";
$bulan .= "<option>November</option>";
$bulan .= "<option>Desember</option>";
// EOF