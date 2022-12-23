# PHP/Python MySQL Dumper

This is a MySQL Dumper that uses a Python console to interact with the PHP file. This script also uses gevent to create a pool of workers to dump the database in chunks to increase the speed. This was a project that was near completion but there are areas that never got done.

## Speed Comparison
These tests were done on the same host with the same 2GB database. Times are taken from the start to the end of file compression. 

MSDv2.2: 3:37s
MSDpy: 2:01s

Usage:
Upload the mysqlpy.php file to the target then run the Python script with the correct script password and MySQL server credentials. 

python3 mysql.py http://localhost/mysqlpy.php lol localhost root root 3306

![msdpy](https://i.imgur.com/AYAhMwj.png)

![msdpy](https://i.imgur.com/rRA76rG.png)

![msdpy](https://i.imgur.com/qrnRYC7.png)
