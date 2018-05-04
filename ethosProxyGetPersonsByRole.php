<?php
//header('Content-Type: application/json');

$apikey="ey-your-api-key-here";

// Don't change these URLs
$ethosurl='https://integrateapi.elluciancloud.com';
$authurlpath='/auth';
$apiurlpath='/api/';
$ethosDataModel="persons";
$ethosRoleName="instructor";
//TODO add reference for role names available
$ethosMaxReturn="3";
//$publishurl='https://integrateapi.elluciancloud.com/publish';
//$checkurl='https://integrateapi.elluciancloud.com/consume';
//$consumeurl='https://integrateapi.elluciancloud.com/consume?lastProcessedID=0&max=1';
$resultJsonPrettyPrint='';
$results='';

// Header options for authentication request
$authOpts = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>"Authorization: Bearer ".$apikey."\r\n" .
              "Content-Type: application/json\r\n" .
              "charset: UTF-8"
  )
);

$authContext = stream_context_create($authOpts);

try {
  $authtoken = file_get_contents($ethosurl.$authurlpath, false, $authContext);
  if ($authtoken === false){
    echo "\r\nWe must have done something wrong because our attempt to authenticate and get an authorization token returned false\r\n";
  } else {
    error_log("The authorization token is: ".$authtoken);
  }
} catch (Exception $e) {
  echo "We caught an exeption: " . $e . "\r\n\r\n";
}

// Header options for proxy GET request
$proxyGetOpts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer ".$authtoken."\r\n" .
              "Content-Type: application/vnd.hedtech.s.v2.json\r\n" .
              "Accept: application/vnd.hedtech.integration.v8+json",
    'timeout' => 60
  )
);

$proxyGetContext = stream_context_create($proxyGetOpts);

// Lets use the authtoken to get some data
try {
  $personSearchCriteria = array("role" => "$ethosRoleName");
  $getData = file_get_contents($ethosurl.$apiurlpath.$ethosDataModel."?criteria=".json_encode($personSearchCriteria)."&max=".$ethosMaxReturn, false, $proxyGetContext);
  if ($getData === false){
    echo "\r\nWe must have done something wrong because our attempt to proxy our API call through Ethos to Banner returned false\r\n";
  } else {
    //echo json_encode(json_decode($getData), JSON_PRETTY_PRINT);
    $results = json_decode($getData);
  }
} catch (Exception $e) {
  echo "We caught an exeption: " . $e . "\r\n";
}

$resultJsonPrettyPrint = json_encode($result, JSON_PRETTY_PRINT);
$numberOfResults = count($results);
//$numberOfNames = count($result[0]->names);
$firstResult = $result[0];

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Showing Persons Information from Ethos using Roles</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="page-header">
        <h1>Ellucian Ethos Examples</h1>
        <p class="lead">Displaying Ethos Data Model JSON</p>
      </div>


      <div class="row">
        <!-- <div class="col-sm-12">
          <p>Found <?php echo $numberOfResults; ?> results. Found <?php echo $numberOfNames; ?> name records. </p>
        </div> -->

      </div>

      <?php
      foreach ($results as $result) {
      ?>
      <div class="row">
        <div class="col-sm-12">
          <h3>Preferred Name: <?php echo $result->names[0]->fullName; ?></h3>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <ul class="list-group">
            <li class="list-group-item text-muted">Portfolio</li>
            <li class="list-group-item">Legal Name: <?php echo $result->names[1]->fullName; ?></li>
            <li class="list-group-item">Privacy Status: <?php echo $result->privacyStatus->privacyCategory; ?></li>
            <li class="list-group-item">Number of Roles: <?php echo count($result->roles); ?></li>
            <li class="list-group-item">Roles: <?php
            unset($roles);
            foreach ($result->roles as $value) {
                $roles[]=$value->role;
            }
            echo implode(", ", $roles);
            ?></li>
            <li class="list-group-item">Marital Status: <?php echo $result->maritalStatus->maritalCategory; ?></li>
            <li class="list-group-item">Gender: <?php echo $result->gender; ?> </li>
            <li class="list-group-item">Ethnicity Code: <?php echo $result->ethnicity->reporting[0]->country->code; ?></li>
            <li class="list-group-item">Ethnicity Category: <?php echo $result->ethnicity->reporting[0]->country->ethnicCategory; ?></li>
            <li class="list-group-item">Date of Birth: <?php echo $result->dateOfBirth; ?></li>
            <li class="list-group-item">Number of Addresses: <?php echo count($result->addresses); ?></li>
            <?php if (count($result->addresses)): ?>
            <li class="list-group-item">Address Types: <?php
            unset($addressesList);
            foreach ($result->addresses as $value) {
                $addressesList[]=$value->type->addressType;
            }
            echo implode(", ", $addressesList);
            // echo $result->addresses[0]->type->addressType;
             ?></li>
           <?php endif ?>
          </ul>
        </div>
        <div class="col-sm-8">
          <p>GUID: <?php echo $result->id ?></p>
          <h5>Credentials</h5>
          <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Credential Type</th>
                      <th>Credential Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($result->credentials as $credentialSet) {
                      echo "<tr><td>".$credentialSet->type."</td>";
                      echo "<td>".$credentialSet->value."</td></tr>\n";
                    }
                    ?>
                  </tbody>
                </table>

          </div>
        </div>
      </div>

      <?php
      }
      ?>
      <div class="row">
        <div class="col-sm-12">
          <h3>JSON Received</h3>
          <pre class="pre-scrollable"><code>
<?php echo json_encode($results,JSON_PRETTY_PRINT); ?>
          </code></pre>
        </div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

  </body>
</html>
