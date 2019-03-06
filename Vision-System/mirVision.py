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


import paramiko # Used to set up ssh and sftp.
import os # Import to change directroy.

# For our project we will be assuming the owner of this camera is fake.
username = "fake"

# Change the working directory.
os.chdir("Vision-System")

# Connecting to the server.
sshclient = paramiko.SSHClient() # Create an ssh client.
sshclient.set_missing_host_key_policy(paramiko.AutoAddPolicy()) # Affirm that you trust the server being connected to.
sshclient.connect(hostname="skopossecurity.com", username="ftpuser", password="juicy") # Attempt a connection to the server.

####### EXAMPLE FILE UPLOAD #######
##### Get the current directory of the ftp server.
### stdin,stdout,stderr = sshclient.exec_command("ls")
###
##### Print the results of the current directory.
### print(stdout.readlines())
###
##### Create an ftp client then upload a test image.
### ftpclient = sshclient.open_sftp()
### ftpclient.put("testimage.jpg", username + "/testimage.jpg")
### ftpclient.close()
###################################

# Close the ssh client.
sshclient.close()