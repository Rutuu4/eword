<?php 
include("../../database.php");

?>
<!DOCTYPE html>
<html>
<head>
<base href="<?=$relative_path;?>">
<?php include("../includes/css-scripts.php"); ?>
</head>
<body>
  	
     <div class="box">
    <div class="box-header well" data-original-title="data-original-title">
      <h2 style="font-size:17px;margin:0;"><i class="fa fa-pencil-square-o"></i> Delete Notification </h2>
    </div>
    <div class="box-content">
    <form id="form1" name="form1" method="post" action="" onSubmit="return selIt();" enctype="multipart/form-data" >
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
    <tr>
      <td><table width="280" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="280" height="41"><div align="center" class="text-blue">Are You sure , You want to Delete .? </div></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="7%"><input name="h101" type="hidden" id="h101" value="1" />
                  </td>
                  <td width="93%"><div class="form-actions">
            <button type="submit" class="btn btn-primary pull-left" style="margin-left:50px;">Delete</button>
            <button type="reset" class="btn" style="margin-left:10px;">Cancel</button>
      </div></td>
                 
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</div>
</div>
<?php 
$a=$_POST['h101'];
if($a==1)
{
	 
if($_GET['id']!="")
{
$id =$_GET['id'];


$qury1="Delete From mobile_notification where id='$id'";
$sq1 = $conn->query($qury1);



            $serviceAccountPath = 'e-world-education-b737a-firebase-adminsdk-fbsvc-1bd2575c21.json';
            $projectId='e-world-education-b737a';
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



  if(!empty($accessToken))
  {

      $qry="SELECT id from registration where status=1 and device_type!=0  and device_token!='' ";
      $result_reg=$conn->query($qry);
      $rowcount=mysqli_num_rows($result_reg);
      $page_size=1000;
      $per_page   = $page * $page_size;
      $total_page = ceil($rowcount / $page_size);  

      for($i=0;$i<$total_page;$i++)
      { 
        $start_limit=$i.'000';
        $qry="SELECT device_token,device_type from registration where  status=1 and device_type!=0 and device_token!=''   limit $start_limit,$page_size";
        $result_reg=$conn->query($qry);
        while($row_reg= $result_reg->fetch_array())
        { 
                  if ($row_reg['device_type'] == 2) 
                    {
                        $divice_token_iphone[] = $row_reg['device_token'];
                    }
                    else 
                    {
                        $divice_token_android[] = $row_reg['device_token'];
                    }

        }


            if(!empty($divice_token_android))
            {

                
                    $json_android_tokens = json_encode($divice_token_android, JSON_PRETTY_PRINT); 
                    $url = "https://iid.googleapis.com/iid/v1:batchRemove";
                    $topic_name='custom'.$id;
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
                    if (curl_errno($ch)) 
                    {
                        echo "cURL error: " . curl_error($ch);
                    } 
                    curl_close($ch);

               
            }
            if(!empty($divice_token_iphone))
            { 

                    $url = "https://iid.googleapis.com/iid/v1:batchRemove";
                    $topic_name='iphonecustom'.$id;
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
                    if (curl_errno($ch)) 
                    {
                        echo "cURL error: " . curl_error($ch);
                    } 
                    curl_close($ch);

                   

            }
            
           
      }
  }


if ($sq1==true)
{
echo("<script language=\"javascript\">");
echo("window.close()");
echo("</script>");
}
else
{
error_log(mysqli_error($conn));
echo("<script language=\"javascript\">");
echo("window.close()");
echo("</script>");
}
}
}
 ?>  
</body>
<?php include("../includes/js-scripts.php"); ?>
<script>
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>
</html>
