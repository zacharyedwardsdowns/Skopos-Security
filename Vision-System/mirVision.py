# The MIR Vision System for the Skopos Security Project
# 
# Authors:
#   - Zachary Edwards Downs (Remedee)
#   - Merrell Reed (mreed)

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

###
### Initializtation section.
###

import paramiko # Used to set up ssh and sftp.
import time # Used to time footage recordings.
import cv2 # For reading and writing from camera + motion detection and object tracking/recognition.
import sys # Used for exiting upon error.
import os # For changing the directory.

# Define limit on the numbers of file that can be made by type.
FOOTAGELIM = 5
IMAGELIM = 30
CLIPLIM = 10

# Establish an ssh connection to the server.
sshclient = paramiko.SSHClient() # Create an ssh client.
sshclient.set_missing_host_key_policy(paramiko.AutoAddPolicy()) # Affirm that you trust the server being connected to.
sshclient.connect(hostname="skopossecurity.com", username="ftpuser", password="juicy") # Attempt a connection to the server.

codec = cv2.VideoWriter_fourcc('M','J','P','G') # Define the codec used to compress video files.


###
### Camera operation functions.
###

#
def NameGen(type):
    
    if type == "footage": # If of type footage make cwd Footage
        os.chdir("Footage")
    elif type == "clip": # If of type clip make cwd Clips
        os.chdir("Clips")
    elif type == "image": # If of type image make cwd Images
        os.chdir("Images")
    else:
        print ("Error creating file name.")
        sys.exit(1) # If none of the above exit with eroor.
    
    # Grab a list of files from current diretory.
    for files in os.walk("."):
        for filename in files:
            if filename  is not ".":
                file = filename

    # Generate an unused filename
    for i in range(n):

    os.chdir("..")

# Starts the recording of 5 minute footage segments.
def Record():

    os.chdir("Footage") # Write to "the footage folder.
    camera = cv2.VideoCapture(0) # Set up a video feed from the camera
    vidout = cv2.VideoWriter('output.mkv', codec, 30, (640,480)) # File to write footage to at 30 fps.
    timer = time.time() + 300 # Set a 5 minute timer in seconds to record footage clip
    
    # Write video data while camera is recording and recording is less than 5 minutes.
    while(camera.isOpened() and time.time() <= timer):

        state, frame = camera.read()  # Read a frame from the camera.

        if state == True:
            vidout.write(frame); # If frame was read, write it to the output file.
        else:
            print ("Recording failed...")
            break # If frame was not read, end recording.

    stopRecord(camera, vidout) # End the recording.

# When called removes recording components.
def stopRecord(camera, vidout):
    camera.release() # Release the camera.
    vidout.release() # Release the video file.
    os.chdir("..") # Return to the files directory.

# Uploads images and videos to a user's folder on the server.
def Uplaod(filename, extension):

    file = filename + "." + extension # Combines filename and extension.

    sftpclient = sshclient.open_sftp() # Opens an sftp connection.
    sftpclient.put(file, username + "/" + file) # Writes file to the user's folder.
    sftpclient.close() # Closes the sftp connection.

###
### Cleanup before exit.
###

NameGen("footage")

sshclient.close() # Close the ssh client.
sys.exit(0) # Exit without error.