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

###
### Initializtation section.
###

import paramiko # Used to set up ssh and sftp.
import cv2 # For reading and writing from camera + motion detection and object tracking/recognition.

# Establish an ssh connection to the server.
sshclient = paramiko.SSHClient() # Create an ssh client.
sshclient.set_missing_host_key_policy(paramiko.AutoAddPolicy()) # Affirm that you trust the server being connected to.
sshclient.connect(hostname="skopossecurity.com", username="ftpuser", password="juicy") # Attempt a connection to the server.

codec = cv2.VideoWriter_fourcc('M','J','P','G') # Define the codec used to compress video files.


###
### Camera operation functions.
###

#
#def NameGen():
    

#
def Record():
    camera = cv2.VideoCapture(0) # Set up a video feed from the camera
    vidout = cv2.VideoWriter('output.mkv', codec, 30, (640,480))

    while(camera.isOpened()):
        ret, frame = camera.read()

        if ret == True:

            vidout.write(frame);
            cv2.imshow('frame', frame)
            if cv2.waitKey(1) & 0xFF == ord('q'):
                break
        else:
            stopRecord();
            break

#
def stopRecord():
    camera.release()
    vidout.release()
    cv2.destroyAllWindows()

# Uploads images and videos to a user's folder on the server.
def Uplaod(filename, extension):

    file = filename + "." + extension # Combines filename and extension.

    sftpclient = sshclient.open_sftp() # Opens an sftp connection.
    sftpclient.put(file, username + "/" + file) # Writes file to the user's folder.
    sftpclient.close() # Closes the sftp connection.

###
### Cleanup before exit.
###

Record()

# Close the ssh client.
sshclient.close()