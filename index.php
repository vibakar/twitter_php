<?php

include 'functions.php';
include 'views/header.php';

if (isset($_GET['page']) && $_GET['page'] == "yourtimeline") {
    include 'views/timeline.php';
} else if (isset($_GET['mytweet'])) {
    if ($_GET['mytweet'] == "yourtweet") {
        include 'views/yourtweets.php';
    }
}else if (isset($_GET['tweetsearch'])) {
    if ($_GET['tweetsearch'] == "search") {
        include 'views/search.php';
    }
}else if (isset($_GET['profile'])) {
    if ($_GET['profile'] == "publicprofile") {
        include 'views/publicprofile.php';
    }
} else {
    include 'views/home.php';
}
include 'views/footer.php';
?>
