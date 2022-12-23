#!/usr/bin/env python
import os
import sys
import importlib
import readline
import requests
from exploits.includes.termcolor import colored
from exploits.includes.prettytable import PrettyTable
from exploitsdb import *

version = '1.5'
header = """\
                             vBconsole v"""+version+"""
                               __---__
                            _-       _--______
                       __--( /     \ )XXXXXXXXXXXXX_
                     --XXX(   O   O  )XXXXXXXXXXXXXXX-
                    /XXX(       U     )        XXXXXXX\\
                  /XXXXX(              )--_  XXXXXXXXXXX\\
                 /XXXXX/ (      O     )   XXXXXX   \XXXXX\\
                 XXXXX/   /            XXXXXX   \__ \XXXXX----
                 XXXXXX__/          XXXXXX         \__----  -
         ---___  XXX__/          XXXXXX      \__         ---
           --  --__/   ___/\  XXXXXX            /  ___---=
             -_    ___/    XXXXXX              '--- XXXXXX
               --\/XXX\ XXXXXX                      /XXXXX
                 \XXXXXXXXX                        /XXXXX/
                  \XXXXXX                        _/XXXXX/
                    \XXXXX--__/              __-- XXXX/
                     --XXXXXXX---------------  XXXXX--
                        \XXXXXXXXXXXXXXXXXXXXXXXX-
                          --XXXXXXXXXXXXXXXXXX-
                    * * * * * Who ya gonna call? * * * * * \
"""

if __name__ == '__main__':
	print colored(header, 'red', attrs=['bold'])
	count()

	while True:
		try:
			consoleinput = raw_input('vBconsole> ')
			if consoleinput[0:6] == 'search':
				searchval = consoleinput.split(' ')[1:]
				search(" ".join(searchval))
			elif consoleinput == 'show all':
				showall()
			elif consoleinput == 'help':
				helptable = PrettyTable()
				helptable.field_names = ["Command", "Description"]
				helptable.add_row(["show all", "Shows all exploits available"])
				helptable.add_row(["search {VALUE}", "Will search exploits for match of {VALUE}"])
				helptable.add_row(["use {EXPLOIT}", "Will load {EXPLOIT} to the console for use"])
				helptable.add_row(["exit", "Exits the console"])
				helptable.align["Command"] = "l"
				helptable.align["Description"] = "l"
				print(helptable)
			elif consoleinput[0:3] == 'use':
				useval = consoleinput.split(' ')[-1]
				loadexploit = useval.split('/')[-1]
				try:
					exec("from exploits."+loadexploit+" import initiate")
					initiate()
				except ImportError,e:
					print ('Could not load exploit: '+useval)
					print(e)
			elif consoleinput == 'exit':
				print ("Goodbye ^_^")
				break
		except KeyboardInterrupt:
				print ("\nGoodbye ^_^")
				break
