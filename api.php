<?php
//Manages API requests.

require_once("inc/functions.php"); //Including functions.


$key = filter_var($_GET["key"], FILTER_SANITIZE_STRING); //Verifying API request key.
if($key !== $api_key) {
  echo json_encode(array("Status" => false, "Error" => "Invalid authentication key."));
  exit;
}

//Collecting Variables.
$link = filter_var($_GET["link"], FILTER_SANITIZE_URL);
$new_link = filter_var($_GET["new_link"], FILTER_SANITIZE_URL);
$id = filter_var($_GET["id"], FILTER_SANITIZE_STRING);



//Create link.
if(isset($_GET["create"])) {
  if(empty($link)) {
    echo json_encode(array("Status" => false, "Error" => "link GET variable not sent.", "Link" => null, "ID" => null));
    exit;
  }
  if (filter_var($link, FILTER_VALIDATE_URL) === false) {
    echo json_encode(array("Status" => false, "Error" => "Invalid URL format.", "Link" => null, "ID" => null));
    exit;
  }
  $ID = Shrink($link);
  echo json_encode(array("Status" => true, "Link" => "$url/$ID", "ID" => $ID, "Error" => null));
  exit;
}


//Change redirect link.
if(isset($_GET["edit"])) {
  if(empty($new_link)) {
    echo json_encode(array("Status" => false, "Error" => "new_link GET variable not sent."));
    exit;
  }
  if(empty($id)) {
    echo json_encode(array("Status" => false, "Error" => "id GET variable not sent."));
    exit;
  }
	if (!preg_match("~^(?:f|ht)tps?://~i", $new_link)) {
				$new_link = "http://" . $new_link;
		}
  $edit = $global_database->update("Links", ["Redirect" => $new_link], ["Shortname" => $id]);
  if($edit !== 0) {
    echo json_encode(array("Status" => true, "Error" => null));
    exit;
  }
  else {
    echo json_encode(array("Status" => false, "Error" => "This shortname does not exist."));
    exit;
  }

}


//Get link clicks.
if(isset($_GET["stats"])) {
  if(empty($id)) {
    echo json_encode(array("Status" => false, "Error" => "id GET variable not sent.", "Clicks" => null));
    exit;
  }
  $clicks = $global_database->select("Links", "Clicks", ["Shortname" => $id]);
  foreach ($clicks as $clicks) {
    $clicks = $clicks;
  }
  if(empty($clicks)) {
    echo json_encode(array("Status" => false, "Error" => "This shortname does not exist.", "Clicks" => null));
    exit;
  }
  echo json_encode(array("Status" => true, "Error" => null, "Clicks" => $clicks));
  exit;
}


//Delete link.
if(isset($_GET["delete"])) {
  if(empty($id)) {
    echo json_encode(array("Status" => false, "Error" => "id GET variable not sent."));
    exit;
  }
  $delete = $global_database->delete("Links",["Shortname" => $id]);
  if($delete !== 0) {
    echo json_encode(array("Status" => true, "Error" => null));
    exit;
  }
  else {
    echo json_encode(array("Status" => false, "Error" => "This shortname does not exist."));
    exit;
  }
}
