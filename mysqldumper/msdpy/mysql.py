import gevent
import argparse
import readline
import json
import numpy as np
import shlex
from time import time
from gevent import monkey
from gevent.pool import Pool
from inc.header import header
from urllib.parse import urlparse
from prettytable import PrettyTable
from progress.bar import IncrementalBar

# Monkey patch before importing requests to avoid warnings
monkey.patch_all()

import requests

# Check to see if URL is in valid format
# By no means a perfect solution but it works for what we need
def valid_url(url):
    try:
        result = urlparse(url)
        return all([result.scheme, result.netloc, result.path])
    except:
        return False

# Argparse to make managing initial args easily
parse = argparse.ArgumentParser(description='MySQL Python/PHP Interface')
parse.add_argument('url', help='URL To File. Ex: http://test.com/mysql.php', type=str)
parse.add_argument('password', help='Password to authenticate PHP file', type=str)
parse.add_argument('mysqlhost', help='MySQL Host', type=str)
parse.add_argument('mysqluser', help='MySQL Username', type=str)
parse.add_argument('mysqlpass', help='MySQL Password', type=str)
parse.add_argument('mysqlport', help='MySQL Port', type=int)
args = parse.parse_args()

# Exit if not a valid URL
if valid_url(args.url) == False:
	print("Invalid URL format!")
	exit()

# Set args to global vars
url = args.url
password = args.password
mysqlhost = args.mysqlhost
mysqlusername = args.mysqluser
mysqlpassword = args.mysqlpass
mysqlport = args.mysqlport

# Create pool for workers
# 50 seems to be a good number of workers for the dumps
# Edit at your own risk. Can cause laginess on computer if there are too many workers
pool = Pool(50)

# Function for our workers to run
# Sends request POST data to server
def worker(url, pdata):
	req = requests.post(url, data=pdata)
	resp = req.text
	bar.next() 

# Generates all the request POST data for a table dump
# Creates a request for each chunk of the table and returns the final list of requests
def generate_request_array(pdata, chunks):
	requestdata = []
	i = 0
	while i <= chunks:
		pdata.update({"split_table": 0, "currentchunk": i})
		requestdata.append(pdata.copy())
		i += 1
	return requestdata

# Download file from server
def download(sourcefile):
	with open("./dumps/"+sourcefile, "ab+") as outfile:
		urlp = urlparse(url)
		surl = "%s://%s%s/dumps/%s" % (urlp.scheme, urlp.netloc, urlp.path.rsplit("/", 1)[0], sourcefile)
		req = requests.get(surl, stream=True)
		clen = int(req.headers.get('Content-Length')) / 1024
		bar = IncrementalBar("Downloading file '%s'" % (sourcefile), max=clen, suffix='%(percent)d%%')
		for chunk in req.iter_content(1024):
			outfile.write(chunk)
			bar.next()
		bar.finish()

# Used to confirm if user would like to do certain actions before processing request
def confirm(message):
    while True:
        c = input(message+" [y/n]")
        if c == "y":
            return True
            break
        if c == "n":
            return False
            break

# Start of post data that requires the essential data for all requests sent to server
# This POST data will be copied and updated with extra data required for other actions as we go
postdata = {"userpassword": password, "mysqlhost": mysqlhost, "mysqlusername": mysqlusername, "mysqlpassword": mysqlpassword, "mysqlport": mysqlport}

# Establish connection with server and validate password and MySQL info
req = requests.post(url, data=postdata)
try:
	resp = req.json()
except ValueError:
	print("No JSON was received. Check all parameters to make sure they are correct.")
	print(req.text)
	exit()
if resp['response_code'] == 0:
	print("Something happened. Response message: "+resp['response'])
elif resp['response_code'] == 1:
	# Connection was established and MySQL information was correct
	header()
	print(resp['response'])
	print("Type 'help' for more assistance.")
	currentdb = None
	while True:
		try:
			inputline = mysqlusername+"@"+mysqlhost+"[\033[0;31m"+currentdb+"\033[00m]> " if currentdb is not None else mysqlusername+"@"+mysqlhost+"> "
			input_var = input(inputline)
			postdata2 = postdata.copy()
			if input_var[0:14] == 'show databases':
				args = input_var[14:]
				parser = argparse.ArgumentParser(description='Show all databases on MySQL server.', prog='show databases')
				parser.add_argument("-size", help='Display estimated size of database.', action='store_true')
				
				try:
					parsed = parser.parse_args(shlex.split(args))
					if parsed.size:
						flag = "-size"
					else: 
						flag = 0

					postdata2.update({"get_databases": flag})
					req = requests.post(url, data=postdata2)
					resp = req.json()
					table = PrettyTable()
					if resp['response_code'] == 1:
						table.field_names = ["Database"]
						table.align = "l"
						for line in resp['response'].splitlines():
							table.add_row([line])
						print(table)
					elif resp['response_code'] == 0:
						table.field_names = ["Database", "Size"]
						table.align = "l"
						for line in resp['response'].splitlines():
							spl = line.split(":")
							table.add_row([spl[0], spl[1]])	
						print(table)
					elif resp['response_code'] == 5:
						# JSON error
						print (resp['response'])
				except SystemExit:
					None
			elif input_var[0:11] == 'show tables':
				args = input_var[11:]
				parser = argparse.ArgumentParser(description='Show all tables from a database.', prog='show tables')
				if currentdb == None:
					parser.add_argument("database", help='Database to show tables from.')
				g = parser.add_mutually_exclusive_group()
				g.add_argument("-count", help='Display row count of table.', action='store_true')
				g.add_argument("-size", help='Display estimated size of table.', action='store_true')

				try:
					parsed = parser.parse_args(shlex.split(args))
					if currentdb == None:
						database = parsed.database
					else:
						database = currentdb
					if parsed.count:
						flag = "-count"
					elif parsed.size:
						flag = "-size"
					else:
						flag = 0

					postdata2.update({"get_tables": flag, "database": database})
					req = requests.post(url, data=postdata2)
					resp = req.json()
					table = PrettyTable()
					if resp['response_code'] == 0:
						table = resp['response']
						print (table)
					elif resp['response_code'] == 1:
						table.field_names = ["Tables in "+database]
						table.align = "l"
						for line in resp['response'].splitlines():
							table.add_row([line])
						print (table)
					elif resp['response_code'] == 2:
						table.field_names = ["Tables in "+database, "Row Count"]
						table.align = "l"
						for line in resp['response'].splitlines():
							spl = line.split(":")
							table.add_row([spl[0], '{:,}'.format(int(spl[1]))])
						print (table)
					elif resp['response_code'] == 3:
						table.field_names = ["Tables in "+database, "Size of Table"]
						table.align = "l"
						for line in resp['response'].splitlines():
							spl = line.split(":")
							table.add_row([spl[0], spl[1]])
						print (table)
					elif resp['response_code'] == 5:
						# JSON error
						print(resp['response'])
				except SystemExit:
					None
			elif input_var[0:10] == 'dump table':
				args = input_var[10:]
				parser = argparse.ArgumentParser(description='Dump table from a database.', prog='dump table')
				parser.add_argument("table", help='Table to dump.')
				if currentdb == None:
					parser.add_argument("database", help='Database to dump table from.')

				try:
					parsed = parser.parse_args(shlex.split(args))
					if currentdb == None:
						database = parsed.database
					else:
						database = currentdb
					table = parsed.table

					postdata2.update({"dump_table": 0, "db": database, "table": table})
					req = requests.post(url, data=postdata2)
					resp = req.json()
					if resp['response_code'] == 0:
						# Error something happened with MySQL Query; print MySQL Error
						print(resp['response'])
					elif resp['response_code'] == 2:
						# Table under 10,000 rows was dumped
						print(resp['response'])
						postdata2 = postdata.copy()

						# Compress table dump
						filename = "%s_%s.sql" % (database, table)
						writefile = "%s_%s.sql.gz" % (database, table)
						jsondump = json.dumps({"files": [filename]})
						postdata2.update({"compress": "", "source_file": jsondump, "write_file": writefile})
						req = requests.post(url, data=postdata2)

						if confirm("Download '%s'?" % (writefile)):
							download(writefile)
					elif resp['response_code'] == 1:
						# Table was > 10,000 so it needs to be broke into chunks
						# Table will be dumped by 10,000 row increments
						chunks = np.floor(int(resp['response']) / 10000)
						# Remove dump_table from POST data list because we need to split the table now
						postdata2.pop("dump_table")
						# Generate request for each chunk of the DB
						requestdata = generate_request_array(postdata2, chunks)
						t = time()
						jobs, files = [], []
						bar = IncrementalBar("Dumping table '%s'" % (table), max=len(requestdata), suffix='%(percent)d%%')
						# Add each request to the pool for workers to handle
						i = 0
						for l in requestdata:
							jobs.append(pool.spawn(worker, url, l))
							files.append("%s_%s_%s.sql" % (database, table, i))
							i += 1
						pool.join()
						bar.finish()

						# Split files list into chunks of 20 to send to server
						# This is much faster than sending a request for each file to be compressed
						filescombine = [files[i:i+20] for i in range(0, len(files),20)]

						# Compress chunks into one file
						bar = IncrementalBar("Compressing table '%s'" % (table), max=len(filescombine), suffix='%(percent)d%%')
						for filename in filescombine:
							writefile = "%s_%s.sql.gz" % (database, table)
							jsondump = json.dumps({"files": filename})
							postdata2.update({"compress" : "", "source_file": jsondump, "write_file": writefile})
							req = requests.post(url, data=postdata2)
							resp = req.json()
							bar.next()
						bar.finish()
						t1 = time()
						print ("Runtime: "+str(t1-t))

						if confirm("Download '%s'?" % (writefile)):
							download(writefile)
					elif resp['response_code'] == 5:
						# JSON error
						print(resp['response'])
				except SystemExit:
					None
			elif input_var[0:13] == 'dump database':
				args = input_var[13:]
				parser = argparse.ArgumentParser(description='Dump database.', prog='dump database')
				if currentdb == None:
					parser.add_argument("database", help='Database to dump.')
				
				try:
					parsed = parser.parse_args(shlex.split(args))

					if currentdb == None:
						database = parsed.database
					else:
						database = currentdb

					# Get tables with row counts to start generating request data
					postdata2.update({"get_tables": "-count", "database": database})
					req = requests.post(url, data=postdata2)
					resp = req.json()
					if resp['response_code'] == 0:
						print (resp['response'])
					elif resp['response_code'] == 2:
						# Generate request data based off table row count
						requestdata = []
						postdata2.pop("get_tables")
						postdata2.pop("database")
						for line in resp['response'].splitlines():
							spl = line.split(":")
							table = spl[0]
							rowcount = spl[1]
							postdata3 = postdata2.copy()
							if int(rowcount) < 10000:
								# Table does not need to be split
								postdata3.update({"dump_table": 0, "db": database, "table": table})
								requestdata.append(postdata3.copy())
							else:
								# Table needs to be split into chunks
								postdata3.update({"table": table, "db": database})
								data = generate_request_array(postdata3, np.floor(int(rowcount) / 10000))
								requestdata.extend(data)
						
						# Start creating a pool of workers to send request data
						t = time()
						jobs, files = [], []
						bar = IncrementalBar('Dumping DB ('+database+')', max=len(requestdata), suffix='%(percent)d%%')
						i = 0
						for l in requestdata:
							jobs.append(pool.spawn(worker, url, l))
							if "currentchunk" not in l:
								files.append(l['db']+"_"+l['table']+".sql")
							else:
								files.append(l['db']+"_"+l['table']+"_"+str(l['currentchunk'])+".sql")
							i += 1
						pool.join()
						bar.finish()

						# Split files list into chunks of 20 to send to server
						# This is much faster than sending a request for each file to be compressed
						filescombine = [files[i:i+20] for i in range(0, len(files),20)]

						# Compress each file chunk into one .sql.gz file
						bar = IncrementalBar('Compress DB into one file', max=len(filescombine), suffix='%(percent)d%%')
						for filename in filescombine:
							writefile = "%s.sql.gz" % (database)
							jsondump = json.dumps({"files": filename})
							postdata2.update({"compress" : "", "source_file": jsondump, "write_file": writefile})
							req = requests.post(url, data=postdata2)
							resp = req.json()
							bar.next()

						bar.finish()
						t1 = time()
						print ("Runtime: "+str(t1-t))

						if confirm("Download '%s'?" % (writefile)):
							download(writefile)
					elif resp['response_code'] == 5:
						# JSON error
						print(resp['response'])
				except SystemExit:
					None
			elif input_var[0:12] == 'use database':
				args = input_var[12:]
				parser = argparse.ArgumentParser(description='Set database to use in session.', prog='use database')
				parser.add_argument("database", help='Database to use in session.')

				try:
					parsed = parser.parse_args(shlex.split(args))
					if parsed.database.lower() == "none":
						currentdb = None
					else:
						postdata2.update({"get_databases": 0})
						req = requests.post(url, data=postdata2)
						resp = req.json()
						if parsed.database in resp["response"].splitlines():
							currentdb = parsed.database
							print("Set current database to: %s" % (currentdb))
						else:
							print("'%s' is not a valid database to use." % (parsed.database))
				except SystemExit:
					None
			elif input_var[0:5] == 'query':
				args = input_var[5:]
				parser = argparse.ArgumentParser(description='Execute SQL query.', prog='query')
				parser.add_argument("sqlquery", help='SQL query to execute.')
				if currentdb == None:
					parser.add_argument("database", help='Database to execute query from.')
				parser.add_argument("-delimiter", help='Split results by delimiter instead of in a table.')
				g = parser.add_mutually_exclusive_group(required=True)
				g.add_argument("-f", help='Download query result to file. (Good for large returns)')
				g.add_argument("-p", help='Print query result to screen. (Good for small returns)', action='store_true')


				try:
					parsed = parser.parse_args(shlex.split(args))
					if currentdb == None:
						database = parsed.database
					else:
						database = currentdb
					postdata2.update({"sql_query": 0, "query": parsed.sqlquery, "database": database})
					req = requests.post(url, data=postdata2)
					resp = req.json()
					if resp['response_code'] == 0:
						# Error
						print(resp['response'])
					elif resp['response_code'] == 2:
						# No data returned but rows were affected
						print("Number of rows affected: %s" % (resp['response']))
					elif resp['response_code'] == 1:
						data = json.loads(resp['response'])

						# Create table and list for field names
						if parsed.delimiter == None:
							table = PrettyTable()
							table.field_names = list(data[0].keys())
							table.align = "l"
						else:
							table = parsed.delimiter.join(list(data[0].keys()))+"\n"
						for line in data:
							if parsed.delimiter == None:
								table.add_row(list(line.values()))
							else:
								table += parsed.delimiter.join(list(map(str, line.values())))+"\n"
						if parsed.p:
							print("Row count: %s" % (len(data)))
							print(table)
						elif parsed.f:
							with open(parsed.f, "w+") as outfile:
								outfile.write("Row count: %s\n" % (len(data)))
								outfile.write(str(table))
							print("Wrote query results to: %s" % (parsed.f))
					elif resp['response_code'] == 5:
						# JSON error
						print(resp['response'])
				except SystemExit:
					None
			elif input_var == 'help':
				table = PrettyTable()
				table.field_names = ["Command", "Description", "Flags"]
				table.align = "l"
				table.add_row(["show databases", "Display all databases on MySQL server.", "-size"])
				table.add_row(["show tables", "Display all tables from a database.", "*database*, -size, -count"])
				table.add_row(["dump table", "Dump table from a database.", "table, *database"])
				table.add_row(["dump database", "Dump entire database.", "*database"])
				table.add_row(["use database", "Set database to use for session.", "database"])
				table.add_row(["query", "Execute SQL query. Print or write to file results.", "*database, -delimiter, -f, -p"])
				table.add_row(["clear", "Clears console.", "No flags"])

				print(table)
				print("* = Not needed if a database is selected for current session.")
			elif input_var[0:5] == "clear":
				print("\x1b[2J\x1b[-1;-1H", end="")
		except KeyboardInterrupt:
			break
