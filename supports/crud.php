<?php
// maybe no need to edit these. this class uses old and very basic OOP, so PHP4 is OK to go
class doCrud {
var $cid;
var $resdb;
var $ress;
var $recs ;
var $fields = array();
var $types = array();

function init(){
	$this->cid=mysql_connect(HOST,USER,PASW);
	$this->resdb = mysql_select_db(DBAS,$this->cid);
	return $this->resdb;
}

function execute($sql){
	global $debug;
	if(!$this->cid) $this->init();
	$this->ress = mysql_query($sql) or die(mysql_error());
	if($debug) echo '<pre>'.$sql.'</pre>';
	return $this->ress;
}

function numRows(){
	return mysql_num_rows($this->ress);
}

function numFields(){
	return mysql_num_fields($this->ress);
}

function nextRec(){
	return mysql_fetch_array($this->ress);
}

function getVal($table,$f1,$crit){
	global $debug;
	$q = "select $f1 from $table where $crit";
	if($debug) echo "<pre>$q</pre>";
	$this->execute($q);
	while($ds = $this->nextRec()){
	$this->recs = $ds[$f1];
	}
	return $this->recs;
}

function getFields($table){
	$this->execute("select * from $table limit 1");
	for($i=0;$i<$this->numFields();++$i){
		$fnames[] = mysql_field_name($this->ress,$i);
		$ftypes[] = mysql_field_type($this->ress,$i);
	}
	$this->fields = $fnames;
	$this->types = $ftypes;
}

function addForm($table,$param=NULL){
	// Tambah is Add, baru is New
	echo "<h3>Tambah Data ".ucwords(str_replace('_',' ',$table))." Baru</h3>\n";
	$this->getFields($table);
	$i=0;
	$type = $this->types;
	echo "<form id=\"$table\" method=\"post\" action=\"$table.php?insert=1\">\n";
	foreach($this->fields as $fd){
		++$i;
		if($type[$i-1]=='date') $class_fd='date_input'; else $class_fd = $fd;
		if(!empty($param[$fd])){
		echo "<div><label id=\"lbl_$fd\" class=\"form\">".$param[$fd]." <span><input name=\"$fd\" id=\"$class_fd\" value=\"$_GET[$fd]\"></span></label></div>\n";
		} else {
		echo "<div><label id=\"lbl_$fd\" class=\"form\">".ucwords(str_replace('_',' ',$fd))." <span><input name=\"$fd\" id=\"$class_fd\" value=\"$_GET[$fd]\"></span></label></div>\n";
		}		
	}
	echo "<div><label class=\"form\"><span><button type=\"submit\">Simpan</button></label></div>\n";
	echo "</form>\n";
}

function insertData($table){
	foreach($_POST as $key=>$val){
		if(eregi('tgl',$key) or eregi('tanggal',$key)) {
			$vul=explode('-',$val);
			$val=$vul[2]."-".$vul[1]."-".$vul[0];
		}
        //echo "<li>".$key."-".implode(';',$val);
		$ky[]=$key;
		if(is_array($val)) $val = implode(";",$val);
		$val = str_replace('/',';',$val);
		$vl[]="'".$val."'";
	}
	$q = "insert into $table (".implode(",",$ky).") values (".implode(",",$vl).")";	
	if($debug) echo $q;
	$this->execute($q);
}

function editForm($table,$param=NULL,$id){
	global $qualified;
	if(!$qualified) die("Sorry, not allowed!");
	echo "<h3>Edit ".ucwords(str_replace('_',' ',$table))."</h3>\n";
	echo "<form id=\"$table\" method=\"POST\" action=\"?update=1\">\n";
	$this->getFields($table);
	$this->execute("select * from $table where id='$id'");
	$type = $this->types;
	$i=0;
	$dt=$this->nextRec();
	echo "<input type=\"hidden\" name=\"id\" value=\"".$dt[id]."\">\n";
	foreach($this->fields as $fd){
		++$i;
		if(eregi('tgl',$fd) or eregi('tanggal',$fd)) {
			$class_fd = 'date_input'; 
			$tgl = explode('-',$dt[$fd]);
			$dt[$fd] = $tgl[2].'-'.$tgl[1].'-'.$tgl[0]; 
		}
		else $class_fd = $fd;
		if(!empty($param[$fd])){
		echo "<div><label id=\"lbl_$fd\" class=\"form\">".$param[$fd]." <span><input name=\"$fd\" id=\"$class_fd\" value=\"".$dt[$fd]."\"></span></label></div>\n";	
		}else{
		echo "<div><label id=\"lbl_$fd\" class=\"form\">".ucwords(str_replace('_',' ',$fd))." <span><input name=\"$fd\" id=\"$class_fd\" value=\"".$dt[$fd]."\"></span></label></div>\n";	
		}
	}
	echo "<div><label class=\"form\">Delete<span><input type=checkbox value=1 name=del></span></label></div>\n";
	echo "<div><label class=\"form\"><span><button type=\"submit\">Submit</button></span></label></div>\n";
	echo "</form>\n";
}

function updateData($table,$id){
	global $debug;
	if($_POST[del]) $this->execute("delete from $table where id='$_POST[id]'");
	else
	foreach($_POST as $key=>$val){
		if ($debug) echo "<li>$key ---- $val</li>\n";
		if(eregi('tgl',$key) or eregi('tanggal',$key)) {
			$vl=explode('-',$val);
			$val=$vl[2].'-'.$vl[1].'-'.$vl[0];
		}
		$this->execute("update $table set $key='$val' where id='$_POST[id]'");
		//echo "update $table set $key='$val' where id='$_POST[id]'";
	}	
}

function listTable($table,$param=NULL,$lim) {
	global $offset;
	global $qualified;
	$off = $offset;
	if(!$off) $off = 0;
	echo "<h3>Daftar ".ucwords(str_replace('_',' ',$table))."</h3>\n";
	$this->execute("select count(*) as jum from $table");
	$jml = $this->nextRec();
	if(empty($param)){
	$this->getFields($table);
	$fields=implode(',',$this->fields);
	$this->execute("select * from $table limit $off,$lim");
	} else {
	$key = join(',',array_keys($param));
	$fields = join(',',array_values($param));
	$this->execute("select id,$key from $table limit $off,$lim");
	}
	echo "<table class=\"list\" border=\"1\">";
	echo "<tr><th>".str_replace(',','</th><th>',$fields)."</th>";
	if($qualified)echo "<th>Action</th>\n";
	while($rec=$this->nextRec()){
		echo "<tr>";
		if(empty($param)){
		$fds = $this->fields;
		for($i=0;$i<count($fds);$i++) {
			if(eregi('tgl',$fds[$i]) or eregi('tanggal',$fds[$i]))
				echo "<td>".date('d-m-Y',strtotime($rec[$fds[$i]]))."</td>";
			else
				echo "<td>".$rec[$fds[$i]]."</td>";
		}		
		}else{
		foreach($param as $key=>$val){
			if(eregi('tgl',$key) or eregi('tanggal',$key))
				echo "<td>".date('d-m-Y',strtotime($rec[$key]))."</td>";
			else
				echo "<td>".$rec[$key]."</td>";
		}	
		}		
		if($qualified) echo "<td align=\"center\"><a href=?edit=$rec[id]>Edit</a></td>";
		echo "</tr>\n";	
	}
	echo "</table>";
	$off1 = $off - $lim;
	$off2 = $off + $lim;
	echo "<hr /><div class=\"navbot\">";
	$out=parse_url($_SERVER['REQUEST_URI']);
	$q = explode('&',$out['query']);
	$qs = $out['path'].'?'.$q[0];
	if($off1>-1) echo "<a href=\"$qs&offset=$off1\">Prev</a> ";
	if($jml[0]>=$off2) echo "<a href=\"$qs&offset=$off2\">Next</a>";
	echo "</div>";
}

function listDatum($table,$fields,$crit) {
	global $qualified;
	echo "<h3>Detil ".ucwords($table)."</h3>\n";
	if(empty($fields)){
	$this->getFields($table);
	$fields=implode(',',$this->fields);
	$this->execute("select * from $table where $crit");	
	}else{
	$this->execute("select id,$fields from $table where $crit");
	}
	if($this->numRows()==0) {
		echo "Data tidak ada."; // no datum found
		//$this->addForm('pasien');
	} else {
	echo "<table width=400 border=\"1\" class=list>";
	echo "<tr><td bgcolor='silver'>".ucwords(str_replace(',',' <br> ',str_replace('_',' ',$fields)))."</td>";
	$fds = explode(",",$fields);
	
	while($rec=$this->nextRec()){
	echo "<td>";
		for($i=0;$i<count($fds);$i++) {
			if(ereg('-',$rec[$fds[$i]])){
			$rs=split('-',$rec[$fds[$i]]);
			$rec[$fds[$i]] = $rs[2].'-'.$rs[1].'-'.$rs[0];
			}
			echo $rec[$fds[$i]]."<br>";
		}
		if($qualified) echo "<a href=\"?edit=$rec[id]\">Edit</a>";
	}
	echo "</tr>\n";	
	echo "</table>";
}
}

function makeForm($table,$term,$param){
	echo "<form action=\"?$term=1\" method=\"post\">";
	foreach($param as $key){
		echo "<div><label class=\"form\">".ucwords($key)."<span><input type=\"text\" name=\"$key\"></span></label></div>";
	}
	echo "<div><label class=\"form\">&nbsp;<span><button type=submit>$term</button></span></label>";
	echo "</form>";
}
// End of object, done some minor revision by solmich...
}
// EOF, no PHP tag ending is needed
