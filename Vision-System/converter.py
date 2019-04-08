# Program to convert all mkvs in a user's folder into webm videos.

import os

# Define lists
directories=[]
mkvs=[]

# Move to the specified user directory.
os.chdir('fake')

# Grab the files in the directory.
for direcs in os.walk("."):
        for direc in direcs:
            if '.' not in direc:
                directories.append(direc)

# Store the file list into tmp.
tmp = directories[1]

# Put all mkv files into mkvs list.
for i in range(len(tmp)):
    if 'mkv' in tmp[i]:
        mkvs.append(tmp[i])

# For each mkv, convert it into a webm, and then remove the mkv.
for i in range(len(mkvs)):
    webm = mkvs[i]
    webm = webm[:-3]
    webm += 'webm'
   
    os.system("ffmpeg -i {0} {1}".format(mkvs[i], webm))
    os.system('rm {0}'.format(mkvs[i]))
