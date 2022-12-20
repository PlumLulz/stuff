# PHP/Python MySQL Dumper

This is a MySQL Dumper that uses a Python console to interact with the PHP file. This script also uses gevent to create a pool of workers to dump the database in chunks to increase the speed. This was a project that was near completion but there are areas that never got done.

Usage:
Upload the mysqlpy.php file to the target then run the Python script with the correct script password and MySQL server credentials. 

python3 mysql.py http://localhost/mysqlpy.php lol localhost root root 3306

![msdpy](https://i.imgur.com/AYAhMwj.png)

![msdpy](https://i.imgur.com/rRA76rG.png)

![msdpy](https://i.imgur.com/qrnRYC7.png)
