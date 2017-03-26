<?php

session_start();
$con = mysqli_connect("localhost", "root", "", "twitter");
if (!$con) {
    die("failed to connect with db");
}

if (isset($_GET['function']) && $_GET['function'] == "logout") {
    session_unset();
}

function time_since($since) {

    $chunks = array(
        array(60 * 60 * 24 * 365, 'year'),
        array(60 * 60 * 24 * 30, 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24, 'day'),
        array(60 * 60, 'hour'),
        array(60, 'minute'),
        array(1, 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
    return $print;
}

function displayTweets($type) {
    global $con;

    $sid = $_SESSION['id'];

    if ($type == 'public') {
        $whereclause = "";
    } else if ($type == 'isfollowing') {
        $query = "SELECT * FROM isfollowing WHERE follower=$sid";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) == 0) {
            echo "No tweets to display";
            die();
        }
        $whereclause = "";

        while ($row = mysqli_fetch_assoc($result)) {
            if ($whereclause == "") {
                $whereclause = " WHERE";
            } else {
                $whereclause.= " OR";
            }
            $whereclause.=" userid=" . $row['isfollowing'];
        }
    } elseif ($type == "yourtweets") {
        $whereclause = " WHERE userid=" . $sid;
    } elseif ($type == "search") {
        $search_content = $_GET['q'];
        echo 'Showing search results for "' . $search_content . '":';
        $whereclause = " WHERE tweet LIKE '%$search_content%'";
    }elseif (is_numeric($type)) {
         $userquery = "SELECT * FROM user WHERE id=$type";
         $userqueryresult = mysqli_query($con, $userquery);
         $user = mysqli_fetch_assoc($userqueryresult);
         echo '<h2>'.$user['email']."'s tweets".'</h2>';
        $whereclause=" WHERE userid=".$type;
    }
    $query1 = "SELECT * FROM tweets " . $whereclause . " ORDER BY datetime DESC";
    $result1 = mysqli_query($con, $query1);
    if (mysqli_num_rows($result1) == 0) {
        echo 'There are no tweets to display';
    } else {
        while ($row = mysqli_fetch_assoc($result1)) {
            $userid = $row["userid"];
            $userquery = "SELECT * FROM user WHERE id=$userid";
            $userqueryresult = mysqli_query($con, $userquery);
            $user = mysqli_fetch_assoc($userqueryresult);
            echo '<div class="tweet"><p>' . $user['email'] . '<span class="time">' . '  ' . time_since(time() - strtotime($row["datetime"])) . ' ago:</span></p>';
            echo '<p>' . $row['tweet'] . '</p>';
            echo '<p><a class="togglefollow" data-userid="' . $row["userid"] . '">';

            $query = "SELECT * FROM isfollowing WHERE follower=$sid AND isfollowing=$userid";
            $result = mysqli_query($con, $query);
            if (mysqli_num_rows($result) > 0) {
                echo 'Unfollow';
            } else {
                echo 'Follow';
            }
            echo '</a></p></div>';
        }
    }
}

function displaySearch() {

    echo ' <form class="form-inline">
            <div class="form-group">
                <input type="hidden" name="tweetsearch" value="search">
               <input type="text" name="q" class="form-control" id="search" placeholder="Search" required>
            </div>
               <button type="submit" class="btn btn-primary">Search Tweets</button>
           </form>';
}

function displayTweetBox() {
    if (isset($_SESSION['id'])) {
        echo '<div class="alert alert-success" id="tweetsuccess" >Tweet posted Successfully</div>
            <div class="alert alert-warning" id="tweetfail" ></div>
            <div class="form">
            <div class="form-group">
               <textarea class="form-control" id="tweetcontent"></textarea>
            </div>
               <button class="btn btn-primary" id="addposts">Add Tweet</button>
           </div>';
    }
}

function displayUsers() {

    global $con;
    $query = "SELECT * FROM user";
    $result = mysqli_query($con, $query);
    while($row=mysqli_fetch_assoc($result)){
        echo "<p><a href='?profile=publicprofile&userid=".$row['id']."'>".$row['email']."</a></p>";
    }
}

?>