<div class="container maincontainer">
    <div class="row">
        <div class="col-md-8">
            <h2>Recent Tweets</h2>
            <?php displayTweets('public'); ?>
        </div>
          <div class="col-md-4">
           <?php displaySearch(); ?>
           <hr>
           <?php displayTweetBox(); ?>
        </div>
    </div>
</div>