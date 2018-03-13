<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

?>


  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <div class="wrapper">
    <form class="form-signin" action="http://nadia.dev.primebeta.com/AuthorizeNet/index.php" method="post">
      <h2 class="form-signin-heading">Check Authorize.Net Credentials</h2>

      <?php
      if(isset($_POST['submit'])){



        if(!empty($_POST['txtLoginID']) && !empty($_POST['txtTransactionKey']) ){

      $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
      $merchantAuthentication->setName($_POST['txtLoginID']);
      $merchantAuthentication->setTransactionKey($_POST['txtTransactionKey']);

      $request = new AnetAPI\AuthenticateTestRequest();
      $request->setMerchantAuthentication($merchantAuthentication);

      $controller = new AnetController\AuthenticateTestController($request);

      try{
        switch ($_POST['environment']) {
          case 'SANDBOX':
              $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            break;

          case 'PRODUCTION':
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
            break;

          default:
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            break;
        }


           $alert = "alert-danger";
            $alert_message = "You provide a invalid credentials";
           if($response->getMessages()->getResultCode() == 'Ok'){
              $alert = "alert-success";
              $alert_message = "You provide a valid credentials";
           }

           ?>

           <div class="alert <?php   echo $alert ?>">
               <strong><?php echo $alert_message ?></strong>
               <pre>
                 <?php print_r($response->getMessages()->getMessage()); ?>
               </pre>
           </div>



      <?php

      }catch(\Exception $e){ ?>
        <div class="alert alert-danger">
            <strong><?php   echo $e->getCode(); echo $e->getMessage(); ?></strong>
        </div>

    <?php  }

    }else{ ?>
      <div class="alert alert-danger">
          <strong>Credentials missing</strong>
     </div>
  <?php }

      }

      ?>


      <div class="form-group">
        <input type="text" class="form-control" name="txtLoginID" id="txtLoginID-main" placeholder="API Login ID" value="<?php echo (isset($_POST['txtLoginID'])) ?  $_POST['txtLoginID'] : ''; ?>">
      </div>
      <div class="form-group">
        <input type="text" class="form-control" name="txtTransactionKey" id="txtTransactionKey-main" placeholder="Transaction Key" autocomplete="off" value="<?php echo (isset($_POST['txtTransactionKey'])) ?  $_POST['txtTransactionKey'] : ''; ?>">
      </div>
      <div class="form-group">
     <label for="environment">Environment</label>
     <select class="form-control" name="environment" id="environment">
       <option value="SANDBOX" <?php echo (isset($_POST['environment']) && $_POST['environment']=='SANDBOX' ) ? 'selected' : ''; ?>>SANDBOX</option>
       <option value="PRODUCTION" <?php echo (isset($_POST['environment']) && $_POST['environment']=='PRODUCTION' ) ? 'selected' : ''; ?>>PRODUCTION</option>
    </select>

      </div>
      <button class="btn btn-lg btn-primary btn-block" name="submit" value="submit" type="submit">Validate</button>
    </form>
  </div>
  <style>
    @import "bourbon";

    body {
      background: #eee !important;
    }

    .wrapper {
      margin-top: 80px;
      margin-bottom: 80px;
    }

    .form-signin {
      max-width: 800px;
      padding: 15px 35px 45px;
      margin: 0 auto;
      background-color: #fff;
      border: 1px solid rgba(0, 0, 0, 0.1);

      .form-signin-heading,
      .checkbox {
        margin-bottom: 30px;
      }

      .checkbox {
        font-weight: normal;
      }

      .form-control {
        position: relative;
        font-size: 16px;
        height: auto;
        padding: 10px;
        @include box-sizing(border-box);

        &:focus {
          z-index: 2;
        }
      }

      input[type="text"] {
        margin-bottom: -1px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
      }

      input[type="password"] {
        margin-bottom: 20px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
    }
  </style>
