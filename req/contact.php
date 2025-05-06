<?php  
if (isset($_POST['email'], $_POST['full_name'], $_POST['message'])) {

    $email     = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $message   = trim($_POST['message']);

    // simple validation
    if ($email === '') {
        header("Location: ../index.php?error=" . urlencode("Email is required") . "#contact");
        exit;
    }
    if ($full_name === '') {
        header("Location: ../index.php?error=" . urlencode("Full name is required") . "#contact");
        exit;
    }
    if ($message === '') {
        header("Location: ../index.php?error=" . urlencode("Message is required") . "#contact");
        exit;
    }

    // prepare email
    $to      = "you@yourdomain.com";           // ← change to your support/admin address
    $subject = "New contact form message from $full_name";
    $body    = "Name: $full_name\nEmail: $email\n\nMessage:\n$message\n";
    $headers = "From: $full_name <$email>\r\nReply-To: $email\r\n";

    // send mail
    if (mail($to, $subject, $body, $headers)) {
        header("Location: ../index.php?success=" . urlencode("Message sent successfully") . "#contact");
        exit;
    } else {
        header("Location: ../index.php?error=" . urlencode("Failed to send message, please try again later") . "#contact");
        exit;
    }

} else {
    // direct access — redirect home
    header("Location: ../index.php");
    exit;
}
