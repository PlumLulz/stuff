from colorama import Fore, Back, Style
def header():
	print(
		"\n" +
		Fore.BLUE + ' :::' + 
		Fore.RED + '=======  ' +
		Fore.BLUE + '::: ' +
		Fore.RED + '=== ' +
		Fore.BLUE + ':::' +
		Fore.RED + '===  ' +
		Fore.BLUE + ':::' +
		Fore.RED + '====   ' +
		Fore.BLUE + ':::         :::' +
		Fore.RED + '====  ' +
		Fore.BLUE + '::: ' +
		Fore.RED + '==='
		)
	print (
		Fore.BLUE + ' ::: ' + 
		Fore.WHITE + '=== === ' +
		Fore.BLUE + '::: ' +
		Fore.WHITE + '=== ' +
		Fore.BLUE + ':::     :::  ' +
		Fore.WHITE + '===  ' +
		Fore.BLUE + ':::         :::  ' +
		Fore.WHITE + '=== ' +
		Fore.BLUE + '::: ' +
		Fore.WHITE + '===' 
		)
	print(Fore.RED + ' === === ===  =====   =====  === ====  ===         =======   ===== ')
	print(Fore.WHITE + ' ===     ===   ===       === ========  ===      == ===        ===')
	print(Fore.RED + ' ===     ===   ===   ======   ==== === ======== == ===        ===  ')
	print(Style.RESET_ALL)
