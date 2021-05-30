<?php
//Homepage script for Eos.

//Redirect if not installed.
if (!file_exists('inc/config.php')) {
header("Location: admin/install.php");
exit;
}

require_once("inc/functions.php");

if(!$homepage_allowed) {
  CountHomePage(); //Add clicks to analytics.
  NotFound(); //404 Page.
  exit;
}
if(isset($_GET["shrink"])) {  //Shrink URL.
  $shortname = Shrink($_POST["link"]); //FUNCTION
  $link = preg_replace('#^https?://#', '', $url);
  $link = "$link/$shortname";
  echo $link;
  exit;
}
else {
  CountHomePage(); //Add clicks to analytics.
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <title><?php echo $website_name; ?></title>
  </head>
  <style>
  body, html {
    background: #000;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    font-family: 'Roboto', sans-serif;
  }
  .bg-img {
    width: 100%;
    height: 100%;
    background: url("inc/header.jpg") center center no-repeat;
    background-size: cover;
    z-index: -99;
    display: none;
  }
  .bg-img:before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-image: linear-gradient(to bottom right, #002f4b, #dc4225);
    opacity: .4;
  }
  .card {
    z-index: 99;
    margin: auto;
    position: absolute;
    top: 0; left: 0; bottom: 0; right: 0;
    height: 450px;
    min-width: 360px;
    max-width: 30%;
    text-align: center;
    padding: 15px;
    border-radius: 15px;
    color: #fff;
    word-wrap: break-word;
    display: none;
  }
  .heading {
    font-size: 34px;
    margin-top: 20px;
    font-weight: 500;
    letter-spacing: 1px;
  }
  .sub-heading {
    font-size: 16px;
    margin-top: 10px;
    font-weight: 300;
    letter-spacing: 0.5px;
  }
  .input {
    float: left;
    width: 70%;
    margin-right: 20.5px;
  }
  .input input {
    margin-top: 70px;
    width: 100%;
    height: 30px;
    background: rgba(255,255,255,0.3);
    border: none;
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
    outline: none;
    padding: 10px;
    color: #dcdee2;
    font-size: 18px;
    padding: 10px;
    float: left;
  }
  .input input::placeholder {
    color: #dcdee2;
    font-size: 18px;

  }
  input[type="submit"] {
    font-size: 14px;
    margin-top: 70px;
    text-transform: uppercase;
    border: 0;
    border-top-right-radius: 16px;
    border-bottom-right-radius: 16px;
    display: block;
    width: 24%;
    height: 50px;
    color: #fff;
    background: #006ac1;
    cursor: pointer;
    float: left;
    outline: none;
    transition: all 0.3s ease;
  }
  input[type="submit"]:hover {
    color: #e0e0e0;
    background: #005399;
  }
  input[type="submit"]:active {
    box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
  input[type="submit"]:disabled {
   background: #005399;
   box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
   cursor: progress;
  }
  .invalid input[type="text"] {
    border: 1px solid red !important;
  }
  .invalid input[type="submit"] {
    height: 52px;
  }
.helptext {
  transition: opacity 1s;
  opacity: 0;
}
.help .helptext {
    position: absolute;
    margin-top: 126px;
    margin-left: -270px;
    text-align: center;
    padding: 5px 0;
    border: none;
    border-radius: 6px;
    background-color: red;
    color: #fff;
    z-index: 9999;
    opacity: 1;
    width: 200px;
    height: 20px;
}
.help .helptext::before {
  content: "";
  position: absolute;
  margin-top: -15px;
  margin-left: 87px;
  border-width: 5px;
  border-style: solid;
  border-color: transparent transparent red transparent;
}
a{
  text-decoration: none;
  color: inherit;
}
.link {
  font-size: 44px;
  color: #000;
  word-wrap: break-word;
}
.footer {
  font-size: 13px;
  color: #dcdee2;
  z-index: 999;
  margin: auto;
  position: absolute;
  bottom: 5px; right: 5px;
  text-align: center;
  display: none;
}
  </style>
  <body>
    <div class="bg-img"></div>
    <div class="card">
      <div class="heading"><?php echo $homepage_title; ?></div>
      <div class="sub-heading"><?php echo $homepage_subheading; ?></div>
      <form>
      <div class="input">
        <input id="link" type="text" placeholder="Link to shorten" required>
        <span class="helptext">Please enter a valid URL.</span>
      </div>
        <input type="submit" value="Shorten" />
      </form>
    </div>
    <div class="footer">
       © 2017 <?php echo $website_name; ?>. All rights reserved.
    </div>
  </body>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js'></script>
  <script>
  $(document).ready(function() {
    setTimeout(function() {
     $(".bg-img").fadeIn(999);
   }, 200);
   setTimeout(function() {
    $(".card, .footer").fadeIn(500);
  }, 700);
  });
  $("form").submit(function(e) {
    form = $("form");
    e.preventDefault();
    if(form.hasClass("invalid")) {
      form.addClass("help");
    }
    else {
      $("input").prop("disabled", true);
      $("body").css("cursor", "progress");
      $.ajax({
      type: "POST",
      url: "?shrink",
      data: {link: $("#link").val()},
      success: function(link_non_http) {
        link = "http://" + link_non_http;
        $("input").prop("disabled", false);
        $("body").css("cursor", "auto");
        if(isUrlValid(link_non_http)) {
          $("input[type='text']").val("");
          swal({
            title: "Link Shortened!",
            text: "<a class='link' target='blank' href='" + link + "'>" + link_non_http + "</a>",
            type: "success",
            confirmButtonText: "Shorten Another",
            html: true
          });
        }
        else {
          swal({
            title: "There was an error.",
            text: link_non_http,
            type: "error",
            confirmButtonText: "Okay",
          });
        }
      },
      error: function() {
        $("input").prop("disabled", false);
        $("body").css("cursor", "auto");
        swal({
          title: "There was an error.",
          text: "We encountered an error while trying to shorten your link.",
          type: "error",
          confirmButtonText: "Okay",
        });
      },
      });
    }
  })
  $('.input input').on('change', function() {
    var input = $(this);
    if (isUrlValid(input.val())) {
      $("form").removeClass('invalid');
      $("form").removeClass("help");
    } else {
      $("form").addClass('invalid');
    }
  });
  function isUrlValid(url) {
   if(/(^|\s)((https?:\/\/)?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/.test(url)) {
     return true;
   } else {
     return false;
   }
  };
  </script>
</html>
