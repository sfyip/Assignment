import re
import urllib2
import pycurl
import sys
import time
import os
import hashlib
import random

##########################################
#Tested on PYTHON 2.7.4
##########################################

try:
    os.remove("sql2.txt")
except OSError:
    pass


rand_name1 = ["Franky", "John", "Michael", "Peter", "May", "Alice", "Frank", "Ivan", "Richard", "Billy", "Ada", "Bill", "Ken", "Thomas", "Susan"]
rand_name2 = ["Ho","Wong", "Chung", "Chan", "Lee", "Yuen", "Tang", "Lai"]
rand_time = ["19:00", "18:30", "20:30", "13:30", "13:00", "12:30", "11:30", "18:45", "20:30", "19:45", "21:00"]
rand_response = ["", "Quiet", "Smoking", "Window seat", "", ""]

with open("sql2.txt", "a") as sqlfile:
    for id in range(1, 51):
        username = "custid"+ str(id)
        password = "123456"
        email = "custid"+ str(id)+"@gmail.com"
	tel = str(random.randint(92432445, 97543556))
        fullname = rand_name1[random.randint(0, 14)] + str(" ") + rand_name2[random.randint(0, 7)]

        h = hashlib.new('sha256')
        h.update(username+password+'IamSalt1234!@#$%^&*()')
        h.hexdigest()
        
        sqlStr = "INSERT INTO customer (username, password, fullname, email, tel, activate) VALUES (\'"+username+"\',\'"+h.hexdigest()+"\',\'"+fullname + "\',\'"+ email+"\',\'"+tel+"\', 1);\n";
        sqlfile.write(sqlStr)


    for id in range(1, 200):
        cust_id = str(random.randint(0, 50))
        rest_id = str(random.randint(0, 100))
        person = str(random.randint(1, 10))
        timeslot = "2014-" + str(random.randint(6, 10)) + "-" + str(random.randint(1, 25)) + " " + str(rand_time[random.randint(0, 10)])

	if random.randint(0, 1) == 0 :
            receive_email = "1"
        else:
            receive_email = "0"

        special_request = rand_response[random.randint(0, 5)]
        
        response = random.randint(0, 2)
        if response == 0:
            response = "00"
        elif response == 1:
            response = "01"
        else:
            response = "10"
        
        sqlStr = "INSERT INTO resv (cust_id, rest_id, timeslot, person, special_request, receive_email, response) VALUES (\'"+cust_id + "\',\'"+rest_id + "\',\'"+ timeslot+ "\',\'"+ person+"\',\'"+ special_request+"\',b\'"+receive_email+"\',b\'"+response+"\');\n";
        sqlfile.write(sqlStr)
