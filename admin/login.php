<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../config/db.php";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM employees WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);

        if($row['password'] == $password){

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name']=$row['name'];
            $_SESSION['role'] = $row['role'];

            if($row['role'] == 1){
                header("Location: dashboard.php");
            } else {
                header("Location: ../employee/dashboard.php"); 
            }
        } else {
            $error = "Invalid Password";
        }
    } else {
        $error = "Email Not Found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/login.css" >
</head>
<body>
    <section>
        
    <form method="POST">
        <h2>Sign In</h2>
    <label for="email">Email</label><br>
    <input type="email" name="email" placeholder="Email"><br><br>
    <label for="password">Password</label><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button type="submit"  name="login">Login</button><br>
</form>
</section>
</body>
</html>
<?php if(isset($error)) echo $error; ?>
