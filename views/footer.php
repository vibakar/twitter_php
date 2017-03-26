<footer class="footer">
    <div class="container">
        &copy; mytwitter.com 2017
    </div>
</footer>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="alert alert-warning" id="alerterror" role="alert">

                    </div>
                    <div class="form-group">
                        <input type="hidden" id="loginactive" value="1" />
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a id="loginsignup" >Sign up</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="loginsignupbutton" class="btn btn-primary">Login</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" ></script>

<script>
    $("#loginsignup").click(function () {
        if ($("#loginactive").val() == "1") {
            $("#myModalLabel").html("Signup");
            $("#loginsignupbutton").html("Signup");
            $("#loginsignup").html("Login");
            $("#loginactive").val("0");
        } else if ($("#loginactive").val() == "0") {
            $("#myModalLabel").html("Login");
            $("#loginsignupbutton").html("Login");
            $("#loginsignup").html("Signup");
            $("#loginactive").val("1");
        }

    });

    $("#loginsignupbutton").click(function () {

        $.ajax({
            type: "POST",
            url: "action.php?action=loginsignup",
            data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&loginactive=" + $("#loginactive").val(),
            success: function (data) {
                if (data == "1") {
                    window.location.assign("/twitter_prj");
                } else {
                    $("#alerterror").html(data).show();
                }
            }
        });

    });

    $(".togglefollow").click(function () {
        var id = $(this).attr("data-userid");
        $.ajax({
            type: "POST",
            url: "action.php?action=togglefollow",
            data: "userid=" + id,
            success: function (data) {
                if (data == "1") {
                    $("a[data-userid='" + id + "']").html("Follow");
                } else if (data == "2") {
                    $("a[data-userid='" + id + "']").html("UnFollow");
                }

            }
        });
    });

    $("#addposts").click(function () {

        $.ajax({
            type: "POST",
            url: "action.php?action=tweet",
            data: "tweetcontent=" + $("#tweetcontent").val(),
            success: function (data) {
              
                if (data == "1") {
                    $("#tweetsuccess").show();
                    $("#tweetfail").hide();
                }
                else if(data!="") {
                    $("#tweetfail").html(data).show();
                    $("#tweetsuccess").hide();
                }

            }
        });

    });
</script>
</body>
</html>