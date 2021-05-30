<?php
//Redirects user.

require_once("inc/functions.php"); //Including functions.

$link = GetLink($_GET["link_id"]); //Gets link.

if(empty($link) || $link == false) {
  NotFound();
  exit;
}

CountClick($_GET["link_id"]); //Counts click.

header("Location: $link"); //Redirect.

exit;
