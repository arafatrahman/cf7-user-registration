<?php
/*
Plugin Name: Contact Form 7 User Registration Extension Add-on
Description: Adds user registration after a Contact Form 7 submission and sends an email with login details.
Version: 1.0.0
Author: AR Riyad
Author URI: https://github.com/arafatrahman
*/

add_action( 'wpcf7_mail_sent', 'my_user_registration_function' );

function my_user_registration_function( $contact_form ) {
// Retrieve form data
    $submission = WPCF7_Submission::get_instance();
    if ( $submission ) {
        $data = $submission->get_posted_data();
    }

    // Check if email or username is already in use
    $username = $data['your-name'];
    $email = $data['your-email'];
    if ( username_exists( $username ) || email_exists( $email ) || empty($email) || empty( $username)) {
        return;
    }

    // Use form data to register a new user
    $password = wp_generate_password();

    $userdata = array(
        'user_login' => $username,
        'user_email' => $email,
        'user_pass'  => $password,
    );

    $user_id = wp_insert_user( $userdata );

    // Send an email with login details
    $subject = 'Your Login Details';
    $message = 'Username: ' . $username . "\r\n";
    $message .= 'Password: ' . $password . "\r\n";
    $message .= 'Login URL: ' . wp_login_url() . "\r\n";

    wp_mail( $email, $subject, $message );
}
