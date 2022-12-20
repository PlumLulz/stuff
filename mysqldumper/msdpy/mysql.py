import gevent
import argparse
import readline
import numpy as np
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
	exit()
if resp['response_code'] == 0:
	print("Something happened. Response message: "+resp['response'])
elif resp['response_code'] == 1:
	# Connection was established and MySQL information was correct
	header()
	print(resp['response'])
	# currentdb = None
	while True:
		try:
			# inputline = mysqlusername+"@"+mysqlhost+"["+currentdb+"]> " if currentdb is not None else mysqlusername+"@"+mysqlhost+"> "
			input_var = input(mysqlusername+"@"+mysqlhost+"$ ")
			postdata2 = postdata.copy()
			if input_var[0:14] == 'show databases':
				show = input_var.split(' ')[-1]
				postdata2.update({"get_databases": show})
				req = requests.post(url, data=postdata2)
				resp = req.json()
				table = PrettyTable()
				if resp['response_code'] == 1:
					table.field_names = ["Database"]
					table.align = "l"
					for line in resp['response'].splitlines():
						table.add_row([line])
				else:
					table.field_names = ["Database", "Size"]
					table.align = "l"
					for line in resp['response'].splitlines():
						spl = line.split(":")
						table.add_row([spl[0], spl[1]])	
				print(table)
			elif input_var[0:11] == 'show tables':
				database = input_var.split(' ')[-2] if '-' in input_var else input_var.split(' ')[-1]
				count = input_var.split(' ')[-1] if '-' in input_var else 0
				postdata2.update({"get_tables": count, "database": database})
				req = requests.post(url, data=postdata2)
				resp = req.json()
				table = PrettyTable()
				if resp['response_code'] == 0:
					table = resp['response']
				elif resp['response_code'] == 1:
					table.field_names = ["Tables in "+database]
					table.align = "l"
					for line in resp['response'].splitlines():
						table.add_row([line])
				elif resp['response_code'] == 2:
					table.field_names = ["Tables in "+database, "Row Count"]
					table.align = "l"
					for line in resp['response'].splitlines():
						spl = line.split(":")
						table.add_row([spl[0], '{:,}'.format(int(spl[1]))])
				elif resp['response_code'] == 3:
					table.field_names = ["Tables in "+database, "Size of Table"]
					table.align = "l"
					for line in resp['response'].splitlines():
						spl = line.split(":")
						table.add_row([spl[0], spl[1]])
				print (table)
			elif input_var[0:10] == 'dump table':
				database = input_var.split(' ')[-1]
				table = input_var.split(' ')[-3]
				postdata2.update({"dump_table": 0, "db": database, "table": table})
				req = requests.post(url, data=postdata2)
				resp = req.json()
				if resp['response_code'] == 0:
					# Error something happened with MySQL Query; print MySQL Error
					print(resp['response'])
				if resp['response_code'] == 2:
					# Table under 10,000 rows was dumped
					print(resp['response'])
					if confirm("Download table dump?"):
						print("Download file now")
					else:
						print("Don't download file")
				elif resp['response_code'] == 1:
					# Table was > 10,000 so it needs to be broke into chunks
					# Table will be dumped by 10,000 row increments
					chunks = np.floor(int(resp['response']) / 10000)
					# Remove dump_table from POST data list because we need to split the table now
					postdata2.pop("dump_table")
					# Generate request for each chunk of the DB
					requestdata = generate_request_array(postdata2, chunks)
					i = 0
					t = time()
					jobs = []
					bar = IncrementalBar('Dumping Table ('+table+')', max=len(requestdata), suffix='%(percent)d%%')
					# Add each request to the pool for workers to handle
					for l in requestdata:
						jobs.append(pool.spawn(worker, url, l))
						i += 1
					pool.join()
					t1 = time()
					t2 = t1-t
					bar.finish()
					print ("Runtime: "+str(t2))
			elif input_var[0:13] == 'dump database':
				database = input_var.split(' ')[-1]
				postdata2.update({"get_tables": "-count", "database": database})
				req = requests.post(url, data=postdata2)
				resp = req.json()
				if resp['response_code'] == 0:
					print (resp['response'])
				elif resp['response_code'] == 2:
					requestdata = []
					postdata2.pop("get_tables")
					postdata2.pop("database")
					for line in resp['response'].splitlines():
						spl = line.split(":")
						table = spl[0]
						rowcount = spl[1]
						postdata3 = postdata2.copy()
						if int(rowcount) < 10000:
							postdata3.update({"dump_table": 0, "db": database, "table": table})
							requestdata.append(postdata3.copy())
						else:
							postdata3.update({"table": table, "db": database})
							data = generate_request_array(postdata3, np.floor(int(rowcount) / 10000))
							requestdata.extend(data)
					i = 0
					t = time()
					jobs = []
					files = []
					bar = IncrementalBar('Dumping DB ('+database+')', max=len(requestdata), suffix='%(percent)d%%')
					for l in requestdata:
						jobs.append(pool.spawn(worker, url, l))
						if "currentchunk" not in l:
							files.append(l['db']+"_"+l['table']+".sql")
						else:
							files.append(l['db']+"_"+l['table']+"_"+str(l['currentchunk'])+".sql")
						i += 1
					pool.join()
					bar.finish()
					bar = IncrementalBar('Compress DB into one file', max=len(files), suffix='%(percent)d%%')
					for filename in files:
						postdata2.update({"compress" : "", "source_file": filename, "write_file": database+".sql.gz"})
						req = requests.post(url, data=postdata2)
						resp = req.json()
						bar.next()
					bar.finish()
					t1 = time()
					t2 = t1-t
					print ("Runtime: "+str(t2))
			# elif input_var[0:12] == 'use database':
			# 	database = input_var.split(' ')[-1]
			# 	currentdb = database
		except KeyboardInterrupt:
			print ("\nBye")
			break