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
import numpy as np

# Define limit on the numbers of file that can be made by type.
FOOTAGELIM = 2
IMAGELIM = 30
CLIPLIM = 10

# Establish an ssh connection to the server.
sshclient = paramiko.SSHClient() # Create an ssh client.
sshclient.set_missing_host_key_policy(paramiko.AutoAddPolicy()) # Affirm that you trust the server being connected to.
sshclient.connect(hostname="skopossecurity.com", username="ftpuser", password="juicy") # Attempt a connection to the server.

username = "fake" # Hard coding the user account for our prototype.

codec = cv2.VideoWriter_fourcc('M','J','P','G') # Define the codec used to compress video files.



###
### Camera operation functions.
###

# Deletes the given file or first file in the given list.
def Delete(file):

    # If a list then get its first element.
    if isinstance(file, list):
        file.sort()
        file = file[0]
    
    # Determine what path to delete from.
    if "footage" in file:
        path = "Footage/"
    elif "image" in file:
        path = "Images/"
    elif "clip" in file:
        path = "Clips/"
    else:
        print("Cant delete a file of this type.")
        sys.exit(1) # If no path matches then exit with error.

    os.chdir(path) # Change wd to path.
    os.system("rm -f " + file) # Remove the specified file.
    os.chdir("..") # Return to original directory.


# Returns a list of a users directory.
def UserDir(extension):

    sftpclient = sshclient.open_sftp() # Opens an sftp connection.
    sftpclient.chdir(username) # Moves to the user's directory.
    dirlist = sftpclient.listdir(".") # Gets a list of their directory.
    sftpclient.close() # Closes the sftp connection.

    dirlist = [file for file in dirlist if extension in file] # Removes all files not containing the desired extension.
    return dirlist


# Helps NameGen by not repeating footage fle code.
def GenHelper():
   # file = None
    # Grab a list of files from footage diretory.
    for files in os.walk("Footage"):
        for filename in files:
            if filename  is not ".":
                file = filename
        
    return file


# Geneates a unique file name and returns it.
def NameGen(type, extension):
    
    i = 0 # Incrimentor variable.

    if type == "footage": # If of type footage make cwd Footage
        file = GenHelper() # Get files in the footage directory.
       # if file is None:
        #    print(os.getcwd())
        #    file = open("Footage/Footage0." + extension,'w+')
        n = FOOTAGELIM # n is used to set the file limit for the given type.

        # If file is of size n then delete footage0, rename footage1 to footage0, then re-get files.
        if len(file) == n:
            file.sort()
            Delete(file)
            os.system("mv Footage/footage1.mkv Footage/footage0.mkv")
            file = GenHelper()

    elif type == "clip": # If of type clip make cwd Clips
        file = UserDir(extension) # Grabs file list from server.
        n = CLIPLIM

    elif type == "image": # If of type image make cwd Images
        file = UserDir(extension)
        n = IMAGELIM

    else:
        print ("Error creating file name.")
        sys.exit(1) # If none of the above exit with error.
    
    # If the file limit is reached then exit with error.
    if len(file) == n :
        print("No more space to generate files.")
        sys.exit(1)

    file.sort() # Sort file list alphabetically

    # Generate an unused filename.
    for i in range(n):

        # If all files have been checked generate a name then break.
        if i == len(file):
            namegen = type + str(i) + "." + extension
            break

        namegen = type + str(i) + '.' + extension # Generate a file name.

        # If the generated file name does not exist then use it and break.
        if namegen != file[i]:
            break

    return namegen


# Starts the recording of 5 minute footage segments.
def Record():

    outfile = NameGen("footage", "mkv") # Grab an unused file name.
    os.chdir("Footage") # Write to "the footage folder.

    camera = cv2.VideoCapture(0) # Set up a video feed from the camera
    vidout = cv2.VideoWriter(outfile, codec, 30, (640,480)) # File to write footage to at 30 fps.
    timer = time.time() + 300 # Set a 5 minute timer in seconds to record footage clip.
    state, Oframe = camera.read() # Get initial frame to compare to see if there has been motion.

    # Write video data while camera is recording and recording is less than 5 minutes.
    while(camera.isOpened() and time.time() <= timer):

        state, Cframe = camera.read()  # Read a frame from the camera.
        detected = MotionDetect(Oframe,Cframe) # Detect motion based on the original frame. (Returns a Boolean.)

        if detected is True: # If motion was detected then write an image of the detected frame.
            Image(Cframe)
            break

        Oframe = Cframe #move to next frame

        if state == True:
            vidout.write(Oframe); # If frame was read, write it to the output file.
        else:
            print ("Recording failed...")
            break # If frame was not read, end recording.
        

    stopRecord(camera, vidout) # End the recording.


# Detect motion.
def MotionDetect(Oframe,Cframe):

    f1_gray = cv2.cvtColor(Oframe,cv2.COLOR_BGR2GRAY) #convert to gray for motion detection
    f2_gray = cv2.cvtColor(Cframe,cv2.COLOR_BGR2GRAY) #convert to gray for motion detection
    
    Oframe_blur = cv2.GaussianBlur(f1_gray,(21,21),0) #blur it to reduce noice
    Cframe_blur = cv2.GaussianBlur(f2_gray,(21,21),0) #blur it to reduce noice

    diff = cv2.absdiff(Oframe_blur,Cframe_blur)
    thresh = cv2.threshold(diff, 20, 255, cv2.THRESH_BINARY)[1] #make colors at 20 or less = 255
    masked = cv2.bitwise_and(Oframe,Oframe, mask=thresh) #mask we only care about white pixels
     
       
    white_pixels = np.sum(thresh) / 255 #find all white pixels in the image
    rows, cols = thresh.shape # get the matrix of the image
    total = rows * cols # # of rows * # of columns

    if white_pixels > 0.01 * total: #if the image contains 1% white pixels than something has moved
        return True
    else:
        return False


# Write an image.
def Image(frame):

    name = NameGen('image', 'jpg')
    cv2.imwrite(name,frame)


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

Record()

sshclient.close() # Close the ssh client.
sys.exit(0) # Exit without error.