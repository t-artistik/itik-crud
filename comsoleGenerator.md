# gen.php, A Simple CLI CRUD Generator #

Itik framework includes a generator file to run via console, it's gen.php. For now this generator only generate a skeleton CRUD file, for example, say you want to create a CRUD page for table 'passage', so here's how you create it using gen.php:
```
$ php gen.php crud passage
```
Passage is the table name you created before running the gen.php. Next version maybe i'll create the (mysql) table at once from within the gen.php.