What is Itik (ETK)?
Itik is Indonesian term for duck, while ETK means Easy ToolKit. So, this framework hopefully empowers the new (and newbie) programmer in PHP web-based applications to do some prototyping way quicker and faster.

The Itik consists of just one main class and file, and some other supporting files for styling, basic layout, basic JS interactions and basic configuration.

The main class, doCrud() is the central idea of Itik. Basically it will handle some main routines for accessing tables on MySQL database (Create-Read-Update-Delete) in such an incredibly easy way, just not like any other CRUD framework you've might seen/used before.

Let's take a quick shot:

<?php 
require 'config.php'; 
$d = new doCrud(); 
if($_GET['insert']):
$d->insertData('guest');
else:
$d->addForm('guest'); 
endif;
// EOF, no closing PHP tag is needed

Voila! Save that file as eg. guest.php and access it via browser, and you'll get a table-less form for inputing guest table.

Editing some rows previously saved is a piece of cake:

<?php 
require 'config.php'; 
$d = new doCrud(); 
$qualified=1; 
$d->editForm('guest',$_GET['edit']); 
// EOF

See? But wait! That's only gives you editing form with some saved values, what about updating? Here you are, as simple as it's read:

<?php 
require 'config.php'; 
$d = new doCrud(); 
if($_GET['update']): 
$qualified=1; 
$d->updateForm('guest',$_POST['id']); 
endif; 
// EOF

Just some lines of code and you've got a complete CRUD. Of course here you can add some security routines for authentication etc and storing them in session vars.

Ok, for you who still in doubt, here is a complete example on how to read, edit and add values from/to table:

<link rel='stylesheet' href='supports/style.css'>
<?php 
require 'config.php'; 
$d = new doCrud(); 
$qualified=1; // quick and easy hack for security. See notes below.
if($_GET['insert']):
$d->insertData('guest');
elseif($_GET['update']): 
$d->updateData('guest',$_POST['id']); 
elseif($_GET['edit']): // edit contains ID of row 
$d->editForm('guest','',$_GET['edit']); 
elseif($_GET['show']):
$d->listTable('guest','',5); // list tabular data
else: 
$d->addForm('guest'); 
endif;
echo "<a href=?show=1>Show Data</a> -|- <a href=?add=1>Add Data</a>"; 
// EOF

That example omits some security issues which you can implement yourselves. You can of course edit the styling and interaction via CSS and it's preloaded jquery. Look and observe the companion sample application for further details on styling and JS customizations.

This Itik framework is an OpenSource project and thus you need no give me some money to use it (anyway I'll appreciate some donation if this framework really helps you). The only requirement is just please don't remove this file and or some signature on the crud.php, but if some donation is made, you can do whatever you like with this package, haha.

Drop me some words on sol642@gmail.com for further discussions.

Cheers.

Notes on companion example:
1. This framework comes with one sub-folder (supports) and four regular files: config.php, kunjungan.php, guest.php and readme.txt.
2. The file kunjungan.php contains a complete example plus UI modification using jQuery, enjoy, but however, it's still in Indonesian for now (google translate for quick help :). The guest.php, is a bare minimum example. Note that all requirement is filled in config.php. In every table you need an id field as a primary key and autoincrement.
3. Field name become labels and input names, so a good field name makes your form easier to read. However, you could use alias array to map some field names to make good labels. Please consult kunjungan.php for example.
4. Styling could be done via supports/style.css, add or edit as you need.
5. Included jquery is quite old (1.3.2), you can replace it with the newer version if you like.
6. Again, no control of security provided! Use it at your very own risk! I myself used to use some extra security filters in some of my apps using this class, though I've tested it using SQL Inject Me, and nothing seems dangerous, but better to be more careful.