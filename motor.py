#!/usr/bin/python
import json, serial, cgi, cgitb, time, sys

cgitb.enable()

print "Content-Type: application/json"
print

ret = {}
req = cgi.FieldStorage()
ind = 0
key = 'program[0][]'
msg = ''

while key in req:
	instruction = req[key]
	msg += str(instruction[0].value) + ' ' + str(instruction[1].value) + ','
	ind = ind + 1
	key = 'program[' + str(ind) + '][]'
msg += "!"

ret['msg'] = msg
ret['msglen'] = len(msg)
ser = serial.Serial('/dev/usb.arduino', 9600)
ret['ser'] = str(ser)

time.sleep(2)
ret['written'] = ser.write(msg)
time.sleep(1)

resp = ser.readline()
ret['response'] = resp
ser.close()

print json.dumps(ret)
