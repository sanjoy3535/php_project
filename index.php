<style>
    h5 {
        color: red;
    }
</style>

<?php 
require_once('database.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signin'])) {
    $username = htmlspecialchars($_POST['name']);
    $useremail = htmlspecialchars($_POST['email']);
    $userpassword = htmlspecialchars($_POST['password']);
    $usercontact = htmlspecialchars($_POST['contact']);

    if (empty($username) || empty($useremail) || empty($userpassword) || empty($usercontact)) {
        echo "<script>alert('Please fill all the fields.')</script>";
    } else {
        $check_queryy = "SELECT count(*) FROM register WHERE email = :em";
        $check_query = $smtp->prepare($check_queryy);
        $check_query->bindParam(':em', $useremail);
        $check_query->execute();

        if ($check_query->fetchColumn() > 0) {
            echo "<script>alert('This email is already registered.');
            
            window.location.href='index.php';
            </script>";
            exit;
        } else {
            $password_hash = password_hash($userpassword, PASSWORD_DEFAULT);
            $insert_data = "INSERT INTO register (name, email, password, contact, status) VALUES (:one, :two, :three, :four, 'pending')";
            $insert_datax = $smtp->prepare($insert_data);
            $insert_datax->bindParam(':one',$username);
            $insert_datax->bindParam(':two',$useremail);
            $insert_datax->bindParam(':three', $password_hash);
            $insert_datax->bindParam(':four',$usercontact);
            $insert_datax->execute();

            echo "<h5>Registration successful! Please check your email for verification.</h5>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='author'content='sanjoy'/>
    <!-- <meta http-equiv="refresh"content='3;url=https://www.google.com'> -->

    <title>Document</title>
<style type='text/css'>
    form{
        height:350px;
        width:300px;
        margin:Auto;
        display:Flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        border:2px solid red
    }
    label{
        width:200px
    }
</style>

</head>
<body>
    <form action=""method='post'>
<label for="">Name</label>
<input type="text"name='name'placeholder='Enter your name'>
<br>
<label for="">Email</label>
<input type="email"name='email'placeholder='Enter your email'>
<br>
<br>
<label for="">Password</label>
<input type="password"name='password'placeholder='Enter your password'>
<br>
<label for="">Your contact</label>
<input type="text"name='contact'placeholder="Enter your number">
<br>
<input type="submit"name='signin'value='Sign In'>
</form>
</body>
</html>