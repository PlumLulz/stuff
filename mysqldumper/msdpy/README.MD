# PHP/Python MySQL Dumper

This is a MySQL Dumper that uses a Python console to interact with the PHP file. This script also uses gevent to create a pool of workers to dump the database in chunks to increase the speed. As of December 2022 this project was updated.

## Speed Comparison
These tests were done on the same host with the same 2GB and 5.3GB databases. Times are taken from the start to the end of file compression. 

## 2GB
msd2.2: 3:19\
msdpy: 1:10

## 5.3GB
msd2.2: 13:46\
msdpy: 5:53

Usage:
Upload the mysqlpy.php file to the target then run the Python script with the correct script password and MySQL server credentials. 

python3 mysql.py http://localhost/mysqlpy.php lol localhost root root 3306

Main screen and help command.
![msdpy](https://i.imgur.com/OWpo7XE.png)

Show databases command with -size flag.
![msdpy](https://i.imgur.com/TnCKt8a.png)

Show tables command with -count flag.
![msdpy](https://i.imgur.com/WXmZJJ9.png)

Use database command, dump table, and dump database commands.
![msdpy](https://i.imgur.com/c635777.png)
