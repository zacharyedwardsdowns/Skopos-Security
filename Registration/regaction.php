<?php
    // Defines the variable captcha.
    $captcha;

    // Checks for a captcha response and stores results in captcha.
    if(isset($_POST['g-recaptcha-response']))
        $captcha=$_POST['g-recaptcha-response'];

    // If captcha was not filled out, redirect user to the registration page.
    if(!$captcha)
    {
        $url = 'https://skopossecurity.com/register.html';
        header( "Location: $url" );
        exit;
    }

    // If there is a captcha check if the response was valid.
    $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ld0eY0UAAAAANKl-ICEf7Cf_nJA0bmtzzh_tU62&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

    // If captcha is valid redirect to the userpage, otherwise tell them robots to get off your lawn!
    if($response['success'] == false)
    {
        echo '<h2>Damn robots! Get off my webpage!</h2>';
    }
    else
    {
        $url = 'https://skopossecurity.com';
        header( "Location: $url" );
    }
?>