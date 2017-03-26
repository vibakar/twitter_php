<?php

include 'functions.php';
if (isset($_GET['action']) == "loginsignup") {
    if (isset($_POST['loginactive'])) {
        if ($_POST['loginactive'] == "0") {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $check_sql = "SELECT * FROM user WHERE email='$email'";
            $check_result = mysqli_query($con, $check_sql);
            $count = mysqli_num_rows($check_result);
            if ($count > 0) {
                echo "Email already exists";
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);
                $insert_sql = "INSERT INTO user(email,password) VALUES('$email','$password')";
                $insert_result = mysqli_query($con, $insert_sql);
                if ($insert_result) {
                    $_SESSION['id'] = mysqli_insert_id($con);
                    echo "1";
                } else {
                    echo 'Failed to Sign up..Try again later';
                }
            }
        } else {
            if (isset($email)) {
                $email = $_POST['email'];
            } else {
                $email = "";
            }
            if (isset($password)) {
                $password = $_POST['password'];
            } else {
                $password = "";
            }
            $sql = "SELECT * FROM user WHERE email='$email'";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $row = mysqli_fetch_array($result);
                $email = $row['email'];
                $dbpassword = $row['password'];
                $id = $row['id'];
                if (password_verify($password, $dbpassword)) {
                    $_SESSION['id'] = $id;
                    echo '1';
                } else {
                    echo "Email or Password does not exists";
                }
            } else {
                echo "Email or Password does not exists";
            }
        }
    }
}

if ($_GET['action'] == 'togglefollow') {
    $id = $_SESSION['id'];
    $userid = $_POST['userid'];
    $query = "SELECT * FROM isfollowing WHERE follower=$id AND isfollowing=$userid";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $table_id = $row['id'];
        mysqli_query($con, "DELETE FROM isfollowing WHERE id=$table_id");
        echo '1';
    } else {
        mysqli_query($con, "INSERT INTO isfollowing(follower,isfollowing) VALUES($id,$userid)");
        echo '2';
    }
}


if ($_GET['action'] == 'tweet') {
    $email = "";
    $password = "";
    $loginactive = "";
    $id = $_SESSION['id'];
    $mytweet = $_POST['tweetcontent'];
    if (!$_POST['tweetcontent']) {
        echo 'Tweet is empty';
    } elseif (strlen($_POST['tweetcontent']) > 140) {
        echo 'Tweet is too long';
    } else {

        $sql = "INSERT INTO tweets(tweet,userid,datetime) VALUES('$mytweet',$id,now())";
        $result = mysqli_query($con, $sql);
        echo '1';
    }
}
?>
