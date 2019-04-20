# Zachary Edwards Downs | Script to send emails alerts to the user of a skopos security camera.

# Import sys, smtplib, random, and hashids.
from hashids import Hashids
import random
import smtplib
import sys

# Function to send the defined email bodt.
def sendmail(TO, SUBJECT, TEXT):

    # Gmail sign in infromation.
    gmailSender = 'skopos.mailer@gmail.com'
    gmailPass = '-----'

    # Set up the gmail smtp.
    mail = smtplib.SMTP('smtp.gmail.com', 587)
    mail.ehlo()
    mail.starttls()

    # Sign in to gmail.
    mail.login(gmailSender, gmailPass)

    # Create the body of the email.
    BODY = '\r\n'.join(['To: %s' % TO,
                        'From: %s' % gmailSender,
                        'Subject: %s' % SUBJECT,
                        '', TEXT])

    # Attempt to send the email, otherwise error.
    try:
        mail.sendmail(gmailSender, [TO], BODY)
        print ('Success: Email Sent!')
    except:
        print ('ERROR: Could not send email...')

    # Close the smtp connection.
    mail.quit()



# Function to generate an email confirmation code.
def ConfirmGen():

    # Use a salt in generation.
    hashids = Hashids(salt="70h7J!lhJkjhfhhh381233UjhfgGfdHHH", min_length=8)

    # Generate a random integer for encoding.
    randnum = random.randint(1000000, 1000000000)

    # Encode the integer.
    codegen = hashids.encode(randnum)

    return codegen



# If structure to determine what email to send.
if sys.argv[1] == '0':

    # Define email contents.
    TO = sys.argv[2]
    SUBJECT = 'Human Motion Detected by Your Skopos Security Camera'
    TEXT = """Please login at https://skopossecurity.com to view a snapshot of when the motion began. 
Once the video is finished recording it can be viewed on your user home page.

- Skopos Security Team"""

    # Send the mail.
    sendmail(TO, SUBJECT, TEXT)

elif sys.argv[1] == '1':

    # Generate a confirmation code.
    code = ConfirmGen()

    # Define email contents.
    TO = sys.argv[2]
    SUBJECT = 'Confirm Your Email Address'
    TEXT = """This email was sent because someone requested this address be linked to an account at https://skopossecurityy.com.
If this was you then use this verification code to complete registration:

""" + code + """

- Skopos Security Team"""

    # Send the mail.
    sendmail(TO, SUBJECT, TEXT)
