<?php

    # Begin a session on login.
    session_start();

    # Get a unique sessionID from the sessionGen python script.
    # Make it a $_SESSION variable so that it is accesible accross pages.
    #UNTESTED UNTESTED UNTESTED UNTESTED UNTESTED UNTESTED UNTESTED UNTESTED
    $_SESSION["sessionID"] = shell_exec("python3 Website/Login/sessionGen.py");
    #UNTESTED UNTESTED UNTESTED UNTESTED UNTESTED UNTESTED UNTESTED UNTESTED

?>