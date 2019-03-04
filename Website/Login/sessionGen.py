###
###
### UNFINISHED. DO NOT USE.
###
###

# Import hashids for generatinf a custom hash for login checking.
from hashids import Hashids
import pymysql
import random
import os

# Grab the salt.
os.chdir("Website")
thesalt = open("salts","r")

# Use a salt for encryption.
hashids = Hashids(salt=thesalt.readline(), min_length=32)

# Generate a random integer for encoding.
randnum = 890564388 #random.randint(1000000, 1000000000)

print(randnum)

# Connect to the database.
link = pymysql.connect(host='localhost', user='root', password='juicy', db='skopos')
cursor = link.cursor()

# Encode the integer.
hashid = hashids.encode(randnum)

# Test for duplicates.
sql = ("SELECT sessionID FROM sessions WHERE sessionID=%s", (hashid))
cursor.execute(*sql)

if (cursor.rowcount == 0):
    print (hashid)

    # Decode for test.
    test = hashids.decode(hashid)

    print(test)

    sql = ("INSERT INTO sessions VALUES('fake', %s)", (hashid))
    cursor.execute(*sql)
    link.commit()
    link.close()