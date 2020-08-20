jQuery(document).ready(function($){
    function getFBShares(page){
        var shares;
        $.getJSON("http://graph.facebook.com/?ids=" + page, function(data){
            if (data[page].shares > 1){
                shares = data[page].shares;
                $("#fb-share").append(" (" + shares + ")");
            }
        });
    }
    function getTweets(page){
        var tweets;
        $.getJSON("http://urls.api.twitter.com/1/urls/count.json?url=" + page + "&callback=?", function(data){
            if (data.count > 1) {
                tweets = data.count;
                $("#tweet").append(" (" + tweets + ")");
            }
        });
    }
    function getLinkedIn(page){
        var linkedinCount;
        $.getJSON("http://www.linkedin.com/countserv/count/share?url=" + page + "&callback=?", function(data){
            if (data.count > 1) {
                linkedinCount = data.count;
                $("#linkedin").append(" (" + linkedinCount + ")");
            }
        });
    }

    var Url = window.location.href;
    var UrlEncoded = encodeURIComponent(Url);
    var title = encodeURIComponent(document.getElementById("title").innerText);
    getFBShares(Url);
    getTweets(Url);
    getLinkedIn(Url);
    document.getElementById("fb-share").href="http://www.facebook.com/share.php?u=" + UrlEncoded;
    document.getElementById("tweet").href="http://twitter.com/home?status=" + title + " " + UrlEncoded;
    document.getElementById("linkedin").href="http://www.linkedin.com/shareArticle?mini=true&url=" + UrlEncoded + "&title=" + title;
    document.getElementById("gplus-share").href="https://plus.google.com/share?url=" + UrlEncoded;
    document.getElementById("email-share").href="mailto:?body=Take a look at this page I found: " + title + ". You can read it here: " + Url;
});