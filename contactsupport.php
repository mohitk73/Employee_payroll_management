<?php 
include('./config/auth.php');
requireRole([0,3]);
include('./config/db.php');
include('./includes/header.php');
$message='';
if(isset($_POST['submit'])){
    $employee_id=$_SESSION['user_id'];
    $name=htmlspecialchars($_POST['name']);
     $email=htmlspecialchars($_POST['email']);
      $subject=htmlspecialchars($_POST['subject']);
      $message=htmlspecialchars($_POST['message']);

      $sql="INSERT INTO queries(employee_id,name,email,subject,message)
       VALUES('$employee_id','$name','$email','$subject','$message')";
       if($conn->query($sql)==true){
        $message="Message send successfully";
       }
       else{
        echo "error". mysqli_error($conn);
       }
}

?>
<head>
    <link rel="stylesheet" href="../assets/css/contactsupport.css">
</head>
<main>
    <section>
        <h3>Contact Support</h3>
        <div class="contact-form">
            <p style="color: green;"><?= htmlspecialchars($message) ?></p>
            <h4>Send us a message</h4>
            <form method="POST" action="">
                <label for="name">Your Name:</label><br>
                <input type="text" id="name" name="name" required><br><br>
                <label for="email">Your Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>
                <label for="subject">Subject:</label><br>
                <input type="text" id="subject" name="subject" required><br><br>
                <label for="message">Message:</label><br>
                <textarea id="message" name="message" rows="5" required></textarea><br><br>
                <button type="submit" name="submit">Send Message</button>
            </form>
        </div>
        <div class="contact-support">
            <p>If you need any help, then please reach out to our support team:</p>
            <ul>
                <li>Email: admin@mind2web.io</li>
                <li>Phone: +91 7676765645</li>
            </ul>
        </div>
    </section>
    
</main>