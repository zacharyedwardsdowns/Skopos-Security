###
###
### STILL NEEDS TO TAKE USER NAME INTO ACCOUNT. (do after logacction php)
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

# Function to generate a unique hash.
def genHash():
    # Generate a random integer for encoding.
    randnum = random.randint(1000000, 1000000000)

    # Encode the integer.
    hashgen = hashids.encode(randnum)

    return hashgen

# Connect to the database.
link = pymysql.connect(host='localhost', user='root', password='juicy', db='skopos')
cursor = link.cursor()

# Create loop condition.
duplicate = True


# Loop until a unique hash is generated.
while (duplicate == True):
    # Generate a hash.
    hashid = genHash()

    # Test for duplicates.
    sql = ("SELECT sessionID FROM sessions WHERE sessionID=%s", (hashid))
    cursor.execute(*sql)

    if (cursor.rowcount == 0):
        duplicate = False
  

# Insert the unique hash into the database.
sql = ("INSERT INTO sessions VALUES('fake', %s)", (hashid))
cursor.execute(*sql)
link.commit()

# Close the connection to the database.
link.close()