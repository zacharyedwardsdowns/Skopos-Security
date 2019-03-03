# The MIR Vision System for the Skopos Security Project
# 
# Authors:
#   - Zachary Edwards Downs (Remedee)
#   - Mia Ward (mialynward)
#   - Sinan Fathulla (spear29)
#   - Merrell Reed (mreed)
#   - Todd Robbinson (ToddRob0)
# 
# ~ Expected Functionalities ~
# 
# +Recording of video while in the active state.
# +Cutting of video into 5 minute chunks.
# +Deletion of video older than 15 minutes.
# +Writing of images and video to an FTP server.
# +Verification of succesful upload.
# +Detection of motion.
# +Object recognition of:
#    - Humans
#    - Dogs
#    - Cats
#    - Cars
#    - Boxes 
# +Object tracking.
# +Alerting of human motion.
# +Alerting of box removal from camera's vision.
# +The taking of a picture when human motion is detected.
# +The recording of a clip when human motion is detected.
# +Writing of the clip to the FTP server once motion ends.
# +Entering of high alert mode if clip reaches 15 minutes.
# +Queueing clip for deletion as soon as upload is verfied in high alert mode.



from ftplib import FTP # Import for ftp server.
import os # Import to change directroy.

# Necessary information to connect to the ftp server.
ftpServer = "skopossecurity.com"
ftpUser = "ftpuser"
ftpPass = "juicy"

# Opens file for writing logs in the Vision System directory.
os.chdir("Vision System")
log = open("vision-log.txt","w")

# Attempts a connection to the the ftp server.
if FTP(ftpServer):
  ftp = FTP(ftpServer) # Sets the server for future commands.
  log.write("Connected to the ftp server for " + ftpServer + ".\n")
else:
  log.write("Connection to the ftp server for " + ftpServer + " failed...\n")

# Attempts to login to the server.
if ftp.login(ftpUser, ftpPass):
  log.write("Succesfully logged into the server!\n")
else:
  log.write("Login to the server failed...\n")

log.close() # Closes log file.
ftp.close() # Closes the ftp connection.