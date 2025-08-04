<?php
include("../../database.php");
if ($_POST['h1'] == 1) {

  $alias_iimmgg = date("YmdHIS");
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $mesage = mysqli_real_escape_string($conn, $_POST['mesage']);
  $datee = date('Y-m-d');


  //img upload to dir
  $idir = "notification-img/";
  $idirr = "../../../notification-img/";
  $userfile_extn = explode(".", strtolower($_FILES['img']['name']));

  //image validation jpeg,png images
  if (($userfile_extn[1] != "jpg") && ($userfile_extn[1] != "jpeg") && ($userfile_extn[1] != "png") && ($userfile_extn[1] != "")) {
    $msg = "Image File Extention Invalid , Please Upload Valid Image";
  } else {

    //copy to images to folder
    if (!$_FILES['img']['tmp_name'] == "") {
      $copy = copy($_FILES['img']['tmp_name'], $idirr . "" . $alias_iimmgg . "-" . time() . "." . $userfile_extn[1]);
      $img = $idir . "" . $alias_iimmgg . "-" . time() . "." . $userfile_extn[1];
      if ($img == $idir . ".") {
        $img = "";
      }
    }

    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $status = 1;
    $website_link = mysqli_real_escape_string($conn, $_POST['website_link']);

    $notification_type = 3;

    $qury1 = "INSERT INTO mobile_notification(title, mesage,datee, img,details,status,website_link,notification_type) VALUES ('$title','$mesage','$datee','$img','$details','$status','$website_link','$notification_type')";

    $sq1 = $conn->query($qury1);

    $last_idd = mysqli_insert_id($conn);

    if (!empty($img)) {
      $imageUrl = $web_url . $img;
    } else {
      $imageUrl = '';
    }

    $set_title = 'E World Notification';

    $flag = 'custom';
    $serviceAccountPath = 'e-world-education-b737a-firebase-adminsdk-fbsvc-1bd2575c21.json';
    $projectId = 'e-world-education-b737a';
    function getAccessToken($serviceAccountPath)
    {

      $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
      $header = [
        'alg' => 'RS256',
        'typ' => 'JWT'
      ];
      $now = time();
      $claims = [
        'iss' => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => $now + 3600,
        'iat' => $now
      ];
      $base64UrlHeader = base64UrlEncode(json_encode($header));
      $base64UrlClaims = base64UrlEncode(json_encode($claims));

      $unsignedJwt = "$base64UrlHeader.$base64UrlClaims";

      $privateKey = $serviceAccount['private_key'];
      $signature = '';
      openssl_sign($unsignedJwt, $signature, $privateKey, OPENSSL_ALGO_SHA256);

      $base64UrlSignature = base64UrlEncode($signature);

      $jwt = "$unsignedJwt.$base64UrlSignature";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
      ]));

      $response = curl_exec($ch);
      if ($response === FALSE) {
        die('Error fetching access token: ' . curl_error($ch));
      }
      curl_close($ch);

      $data = json_decode($response, true);
      return $data['access_token'];
    }

    function base64UrlEncode($data)
    {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    $accessToken = getAccessToken($serviceAccountPath);

    if (!empty($accessToken)) {

      $qry = "SELECT id from registration where status=1 and device_type!=0  and device_token!=''";
      $result_reg = $conn->query($qry);
      $rowcount = mysqli_num_rows($result_reg);
      $page_size = 1000;
      $per_page   = $page * $page_size;
      $total_page = ceil($rowcount / $page_size);

      for ($i = 0; $i < $total_page; $i++) {
        $start_limit = $i . '000';
        $qry = "SELECT device_token,device_type from registration where  status=1 and device_type!=0 and device_token!='' limit $start_limit,$page_size";
        $result_reg = $conn->query($qry);
        while ($row_reg = $result_reg->fetch_array()) {
          if ($row_reg['device_type'] == 2) {
            $divice_token_iphone[] = $row_reg['device_token'];
          } else {
            $divice_token_android[] = $row_reg['device_token'];
          }
        }


        if (!empty($divice_token_android)) {


          $json_android_tokens = json_encode($divice_token_android, JSON_PRETTY_PRINT);
          $url = "https://iid.googleapis.com/iid/v1:batchAdd";
          $topic_name = 'custom' . $last_idd;
          $data = [
            "to" => "/topics/$topic_name",
            "registration_tokens" => $divice_token_android
          ];
          $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken",
            "access_token_auth: true"
          ];
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          if (curl_errno($ch)) {
            echo "cURL error: " . curl_error($ch);
          }
          curl_close($ch);

          $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";
          $notification = [
            'title' => $set_title,
            'body' => $title,
            'image' => $imageUrl
          ];
          $payload = [
            'message' => [
              "topic" => $topic_name,
              'notification' => $notification,
              'data' => [
                'id' => (string)$last_idd,
                'flag' => (string)$flag,
                'title' => $set_title,
                'message' => $title,
                'image' => $imageUrl
              ]
            ]
          ];
          $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
          ];
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

          if (curl_errno($ch)) {
            //echo "cURL Error: " . curl_error($ch);
          } else {
            //echo "Response Code: $httpCode\n";
            //echo "Response: $response\n";
          }
        }
        if (!empty($divice_token_iphone)) {

          $url = "https://iid.googleapis.com/iid/v1:batchAdd";
          $topic_name = 'iphonecustom' . $last_idd;
          $data = [
            "to" => "/topics/$topic_name",
            "registration_tokens" => $divice_token_iphone
          ];
          $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken",
            "access_token_auth: true"
          ];
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          if (curl_errno($ch)) {
            echo "cURL error: " . curl_error($ch);
          }
          curl_close($ch);

          $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

          $payload = [
            'message' => [
              "topic" => $topic_name,
              'notification' => [
                'title' => $set_title,
                'body'  => $title
              ],
              'data' => [
                'customKey1' => (string)$last_idd,
                'customKey2' =>  (string)$flag,
              ],
              'apns' => [
                'headers' => [
                  'apns-priority' => '10'
                ],
                'payload' => [
                  'aps' => [
                    'sound' => 'default',
                    'badge' => 1
                  ]
                ]
              ]

            ]
          ];
          // Set Headers
          $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
          ];

          // Send the Request using cURL
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

          $response = curl_exec($ch);
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

          if (curl_errno($ch)) {
            //echo "cURL Error: " . curl_error($ch);
          } else {
            //echo "Response Code: $httpCode\n";
            //echo "Response: $response\n";
          }
        }
      }
    }


    if (!$last_idd == "") {
      header("location:../register-user-notification.php");
    }
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <base href="<?= $base_path ?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $softtitle ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php include("../includes/css-scripts.php"); ?>
  <style>
    .error {
      color: red;
    }

    .control-label {
      text-align: left !important;
    }

    .form-control {
      display: block;
      width: 100%;
      height: 34px;
      padding: 6px 12px;
      font-size: 14px;
      line-height: 1.42857143;
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
    }

    .select2-container {
      width: 100% !important;
    }
  </style>
</head>

<body class="<?= $bodyclass ?>">
  <div class="wrapper">
    <?php include("../includes/header.php"); ?>
    <?php include("../includes/sidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>Create Register User Notification</h1>
      </section>

      <section class="content">

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Add Data Here</h3>
          </div>

          <div class="box-body">
            <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data" onsubmit="return validateCheckboxes()">
              <input name="h1" type="hidden" id="h1" value="1" />

              <div class="form-group">
                <label class="control-label col-sm-2">Notification Title :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="title" id="title" placeholder="Enter Notification Title" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Short mesage :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="mesage" id="mesage" placeholder="Enter Short mesage" required>
                </div>
              </div>



              <div class="form-group">
                <label for="email" class="col-sm-2">Notification Image :</label>
                <div class="col-sm-8">
                  <input name="img" id="img" type="file" class="file" multiple=true data-preview-file-type="any">
                </div>
              </div>

              <div class="form-group">
                <label for="passwrod" class="col-sm-2">Description :</label>
                <div class="col-sm-8">

                  <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details"></textarea>

                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Website Link :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="website_link" id="website_link" placeholder="Enter Website Link ">
                </div>
              </div>
              <div class="col-md-12" align="right">
                <button type="submit" class="btn btn-success ">Save changes</button>
              </div>

            </form>
          </div>
        </div>
      </section>
    </div>
    <?php include("../includes/footer.php"); ?>
  </div>
  <?php include("../includes/js-scripts.php"); ?>
  <script>
    $(document).ready(function() {
      //Select2
      $(".select2").select2();
      //bootstrap WYSIHTML5 - text editor
      $(".textarea").wysihtml5();
    });
  </script>
</body>

</html>