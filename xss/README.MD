# XSS Payloads

These are XSS payloads that I have created over the years. 

## mlg_payload.txt
This payload was wrote for an XSS vulnerability in the Major League Gaming forum. It was inspired by the "samy is my hero" Myspace worm from Samy Kamkar. Upon visting a profile that is infected it would replace text on your profile with a message, infect your profile with the worm code, and add me to your friends list. Once a few profiles got infected it quickly got out of hand and was brought to the devs attention. They shortly patched the vulnerability in the profile page. Since this was patched and no longer works I am posting it here for reference.

## kontrolfreek_payload.txt
This payload was wrote for an XSS vulnerability in the kontrolfreek website. The first payload in the file simply sends the users info back to a remote server. The second payload gave you the ability to hijack accounts by changing their email to an email you control for a password reset. This was reported to the company and they were able to patch the vulnerability. 

## vbulletin_plugin_payload.txt
This payload is able to upload a plugin to a vBulletin forum from an XSS vulnerability. The admin is required to be logged in for this payload to work. The payload also has the ability to search the footer links for the admin panel URL if it is not already known. 
