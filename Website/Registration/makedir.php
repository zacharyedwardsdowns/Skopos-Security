<?php

//
// Makes sftp directory for the user.
//

    // Variables for sftp access.
    $server = "skopossecurity.com";
    $user = "ftpuser";
    $pass = "juicy";

    // Set ssh connection or exit script if failed.
    $link = ssh2_connect($server, 22) or die("Unable to connect to $server");

    // Attempt to login.
    if (@ssh2_auth_password($link, $user, $pass)) 
    {
        echo "Connected as $user@$server";
    }
    else 
    {
        echo "Failed to connect as $user";
    }

    // Establish an sftp connection via ssh.
    $sftp = ssh2_sftp($link);

    // Creates a personal folder for the newly registerd account.
    ssh2_sftp_mkdir($sftp, $argv[1]);

    // Closes the connection.
    ssh2_disconnect($link);

?>