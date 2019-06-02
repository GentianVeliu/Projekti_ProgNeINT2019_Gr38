<!-- Implementimi i nj SESSION -->
<?php
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>*****MOUNTAIN HOTEL*****</title>
  <link rel="stylesheet" type="text/css" href="rezervo.css" />
  <script src="room_fields.js"></script>
</head>
<body>
<?php
  require_once("hotel2header.php");
  require_once("dbconn.php");

  $email=$telefoni=$emailId=$dateFrom=$dateTo="";
  $errEmail=$errTelefoni=$errDateFrom=$errDateTo=$errBooking="";
  $valEmail=$valTelefoni=$valBooking="";
  $bookingId=$llojiDhomes=$nrDhomave=$nrTeRriturve=$nrFemijeve="";
  $showData=false;
  $showSearch=true;

  class EmailException extends Exception {
    public function errMsg() {
      $msg = "Shkruaj emailin sipas formatit te duhur";
      return $msg;
    }
  }

  //Nese behet kerkesa per te pare rezervimin
  if(isset($_GET['kerko'])) {
    $bookingId = $_GET['booking_id'];
    $bookingId= trim($bookingId);
    if(preg_match("/[0-9]+/",$bookingId)) {
      $_SESSION['bookingId'] = $bookingId;
      $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Error connecting with database");
      $bookingQuery = "SELECT * FROM Booking WHERE bookingId=$bookingId";
      $resultOfBooking = mysqli_query($dbc,$bookingQuery);
      $bookingData = mysqli_fetch_array($resultOfBooking);

      if($bookingData) {
        $emailId = $bookingData['emailId'];
        $dateFrom = $bookingData['bookingFrom'];
        $dateTo = $bookingData['bookingTo'];
        $nrDhomave = $bookingData['nrOfRooms'];
        $nrTeRriturve = $bookingData['nrOfAdults'];
        $nrFemijeve = $bookingData['nrOfChildren'];

        $contactQuery = "SELECT * FROM Contacts WHERE bookingId=$bookingId";
        $resultOfContact = mysqli_query($dbc,$contactQuery);
        while($contactData = mysqli_fetch_array($resultOfContact)) {
          $telefoni = $contactData['contactNumber'];
        }
        $emailQuery = "SELECT * FROM EmailList WHERE emailId=$emailId";
        $resultOfEmail = mysqli_query($dbc,$emailQuery);
        while($emailData = mysqli_fetch_array($resultOfEmail)) {
          //ne kete rast kemi perdorur indexed arrays ne vend te associative arrays
          $email = $emailData[1];
        }
        $roomQuery = "SELECT * FROM Room WHERE roomId=".$bookingData['roomId'];
        $resultOfRoom = mysqli_query($dbc,$roomQuery);
        while($roomData = mysqli_fetch_array($resultOfRoom)) {
          $llojiDhomes = $roomData['roomName'];
          $llojiDhomes = strtolower($llojiDhomes);
          $llojiDhomes = preg_replace("/[ ]+/","_",$llojiDhomes);
        }
        $showSearch = false;
        $showData = true;
      }
      else $errBooking="Rezervimi i tille nuk ekziston, ndrysho numrin e rezervimit";
    } else $valBooking="Numri i rezervimit duhet te perbehet vetem nga numrat";
  }
  //Nese behet kerkesa per ndryshime
  else if(isset($_POST['change'])) {
      $bookingId = $_SESSION['bookingId'];

      if(empty($_POST['email']))
        $errEmail="Shkruaj emailin";
      else {
        $email=$_POST['email'];
        $email = trim($email);
        try {
          if(!preg_match('/^[a-zA-Z0-9]+[-_.]{0,2}[a-zA-Z0-9]*@[a-zA-Z0-9]+\.{1}[a-zA-Z]+\.?[a-zA-Z]+$/',$email)) {
            throw new EmailException();
          }
        }
        catch (EmailException $e) {
            $valEmail = $e->errMsg();
          }
      }

      if(empty($_POST['telefoni']))
        $errTelefoni="Shkruaj kontaktin";
      else {
        $telefoni=$_POST['telefoni'];
        $telefoni = trim($telefoni);
        if(!preg_match('/^\+?[0-9]+$/',$telefoni))
          $valTelefoni = "Kontakti duhet te parmbaje vetem numra ose prefix";
      }

      if(empty($_POST['from']))
        $errDateFrom="Zgjedhe nje date";
      else
        $dateFrom=$_POST['from'];

      if(empty($_POST['to']))
        $errDateTo="Zgjedhe nje date";
      else
        $dateTo=$_POST['to'];

      $llojiDhomes=$_POST['lloji_i_dhomes'];
      $nrDhomave=$_POST['nr_i_dhomave'];
      $nrTeRriturve=$_POST['nr_te_rriturve'];
      $nrFemijeve=$_POST['nr_i_femijeve'];

      if(empty($errEmail) && empty($errTelefoni) && empty($errDateFrom) && empty($errDateTo)
          && empty($valEmail) && empty($valTelefoni) ){
            $llojiDhomes=preg_split("/[_]+/",$llojiDhomes);
            $llojiDhomes=join(" ",$llojiDhomes);
            $llojiDhomes=ucwords($llojiDhomes);

           // lidhja me database
           $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Error connecting with database");

           $queryOfBooking = "SELECT * FROM Booking where bookingId=$bookingId";
           $result = mysqli_query($dbc,$queryOfBooking) or die("Error quering select data with database");
           $emailId=mysqli_fetch_array($result)['emailId'];
           $queryOfEmails = "SELECT COUNT(emailId) FROM Booking WHERE emailId=$emailId";
           $result = mysqli_query($dbc,$queryOfEmails) or die("Error quering emails data with database");
           $count = mysqli_fetch_array($result)[0];

           if($count < 2) {
             $oldEmailId = $emailId;
             $queryOfEmails = "SELECT * FROM EmailList";
             $emails = mysqli_query($dbc,$queryOfEmails) or die("Error quering EmailList data");
             //testo nese emaili vec ekziston dhe mos e shto edhe njehere
             while($row=mysqli_fetch_array($emails)) {
               if(strcmp($row['email'],$email) == 0) {
                 $emailId = $row['emailId'];
                 break;
               }
             }
             //nese emaili nuk ekziston atehere update tek EmailList relacioni
             if(!$row) {
               $queryOfEmail = "UPDATE EmailList SET email='$email' WHERE emailId=$emailId";
               $result = mysqli_query($dbc,$queryOfEmail) or die("Error quering email data with database");
             }
           }
           else if($count >= 2) {
             //testo nese emaili vec ekziston dhe mos e shto edhe njhere
             $queryOfEmails = "SELECT * FROM EmailList";
             $emails = mysqli_query($dbc,$queryOfEmails) or die("Error quering email list data");
             while($row=mysqli_fetch_array($emails)) {
               if(strcmp($row['email'],$email) == 0) {
                 $emailId = $row['emailId'];
                 break;
               }
             }

             //nese emaili nuk ekziston atehere shto tek EmailList relacioni
             if(!$row) {
               $queryOfEmail = "INSERT INTO EmailList VALUES(default,'$email')";
               $result = mysqli_query($dbc,$queryOfEmail) or die("Error quering email data with database");
               $emailId = mysqli_insert_id($dbc);
             }
           }


           $queryOfBooking = "UPDATE Booking
                      SET emailId = $emailId,
                          roomId = (SELECT roomId FROM Room WHERE roomName = '$llojiDhomes'),
                          bookingFrom = '$dateFrom',bookingTo = '$dateTo',  nrOfRooms = $nrDhomave,
                          nrOfAdults = $nrTeRriturve,  nrOfChildren = $nrFemijeve
                      WHERE bookingId = $bookingId";
           $result = mysqli_query($dbc,$queryOfBooking) or die("Error quering booking data with database");

           //nqs emaili qe po e ndryshojme ka qene i referuar vetem njehere dhe nuk eshte bere update
           if($count < 2 && $row) {
             $queryOfEmail = "DELETE FROM EmailList WHERE emailid=$oldEmailId";
             $result = mysqli_query($dbc,$queryOfEmail) or die("Error quering email data with database");
           }

           $queryOfContact = "UPDATE Contacts SET contactNumber='$telefoni' WHERE contactId=$emailId";
           $result = mysqli_query($dbc,$queryOfContact) or die("Error quering contacts data");


           echo "<div class='form1'>";
           echo "<p class='areservation' style='font-size:130%;font-weight:bold;color:#104E8B;'>Ndryshimi u realizua me sukses</p>";
           echo "<p class='areservation'>MOUNTAIN Hotel ju falenderon qe na keni perzgjedhur neve si hotelin tuaj</p>";
           echo "<p class='areservation'>Numri i rezervimit tuaj eshte <span style='color:blue;text-decoration:underline;'>$bookingId</span>,
               ju duhet ta ruani kete kete numer me kujdes, pasi cdo ndryshim ne rezervim apo
               anulimin e tere te tij e beni ne baze te ketij numri</p>";
           echo "<p class='areservation' style='color:#104E8B;''>Ne qofte se deshironi te ndryshoni rezervimin apo ta anuloni ate ju mund te ktheheni ketu
               <a href='hotel2anulo.php'>ketu</a>";
           echo "</div>";


           //mbyllja e lidhjes me database
           mysqli_close($dbc);

           //mos e shfaq permbajtjen e meposhtme pervec footerit
           $showSearch=false;
        }
        else {
          $showSearch=false;
          $showData = true;
      }
  }
  else if(isset($_POST['delete'])) {
    $bookingId = $_SESSION['bookingId'];
    $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Error connecting with database");

    $query = "DELETE FROM Contacts WHERE bookingId=$bookingId";
    $result = mysqli_query($dbc,$query) or die("Error quering contacts");

    $query = "SELECT emailId FROM Booking WHERE bookingId = $bookingId";
    $result=mysqli_query($dbc,$query);
    $emailId = mysqli_fetch_array($result)['emailId'];

    $query = "SELECT COUNT(emailId) from Booking where emailId = $emailId";
    $result=mysqli_query($dbc,$query);
    $count = mysqli_fetch_array($result)[0];

    $query="DELETE FROM Booking WHERE bookingId=$bookingId";
    $result=mysqli_query($dbc,$query) or die("Error quering booking");;

    if($count < 2) {
      $query="DELETE FROM EmailList WHERE emailId=$emailId";
      $result=mysqli_query($dbc,$query) or die("Error quering emaillist");
    }

    echo "<div class='form1'>";
    echo "<p class='areservation' style='font-size:130%;font-weight:bold;color:#104E8B;'>Rezervimi u anulua me sukses</p>";
    echo "<p class='areservation'>MOUNTAIN Hotel ju falenderon qe keni shikuar mbi mundesite per qendrim ne hotelin tone</p>";
    echo "<p class='areservation'>Ne shpresojme qe ju do t'i shqyrtoni prape mundesite per rezervim ne hotelin tone</p>";
    echo "<p class='areservation' style='color:#104E8B;''>Ne qofte se deshironi te beni rezervim te ri atehere vazhdoni
        <a href='hotel2rezervo.php'>ketu</a>";
    echo "</div>";

    //mbyllja e lidhjes me database
    mysqli_close($dbc);
    //mos e shfaq permbajtjen e meposhtme pervec footerit
    $showSearch=false;
    $showData=false;
  }

  if($showSearch) {
?>
<div class="form1" style="height:350px;">
  <p class="panulo">Ne kete pjese ju mund te ndryshoni ose te anuloni rezervimin tuaj</p>
  <p class="panulo">Per ta bere kete ju duhet te shfrytezoni numrin e rezervimit i cili u eshte ofruar juve pasi eshte bere rezervimi</p>
  <p class="panulo">Ne zonen e meposhtme shenoni ate numer dhe shtypni kerko rezervimin</p>
  <form method="GET" action="hotel2anulo.php"  style="margin-top:60px;">
    <input type="text" name="booking_id" id="booking_id" style="margin-left:30px;" autofocus>
    <input type="submit" name="kerko"  value="Kerko rezervimin"><br/>
    <?php
     if(!empty($errBooking)) echo "<br/><span class='required' style='margin-left:30px;'>$errBooking</span>";
     if(!empty($valBooking)) echo "<br/><span class='required' style='margin-left:30px;'>$valBooking</span>";
    ?>
  </form>
</div>

<?php } if($showData) {?>

<div class="form1">
  <form autocomplete="off"  method="post" action="hotel2anulo.php" style="width: 600px; margin: 40px auto;">
    <fieldset>
      <legend style="color:#2F4F4F ;"> Informatat personale </legend>
      <div class="reserv">
        Email<br/>
        <input type="text" name="email"  autofocus value="<?php echo $email; ?>" >
        <?php if(!empty($errEmail)) echo "<br/><span class='required'>$errEmail</span>";
          else if(!empty($valEmail)) echo "<br/><span class='required'>$valEmail</span>";
        ?>
        <br/><br/>
      </div>
      <div class="reserv">
        Telefoni<br/>
        <input type="tel" name="telefoni" value="<?php echo $telefoni; ?>">
        <?php if(!empty($errTelefoni)) echo "<br/><span class='required'>$errTelefoni</span>";
              else if(!empty($valTelefoni)) echo "<br/><span class='required'>$valTelefoni</span>";
        ?>
        <br/><br/>
      </div>
    </fieldset>
    <fieldset>
      <legend style="color:#2F4F4F ;"> Rezervimi </legend>
      <div class="reserv">
        Zgjedhe llojin e dhomes:<br/>
        <select id="dhoma" name="lloji_i_dhomes" onchange="pickedUpRoom()">
        <?php
          //gjenerimi i options te select elementit ne menyre dinamike
          echo "<option value='deluxe_room'";
          if(strcmp($llojiDhomes,'deluxe_room') == 0 )
            echo " selected='selected'";
          echo ">Deluxe Room</option>";
          echo "<option value='junior_suite'";
          if(strcmp($llojiDhomes,'junior_suite') == 0 )
            echo " selected='selected'";
          echo ">Junior Suite</option>";
          echo "<option value='hospitality_room'";
          if(strcmp($llojiDhomes,'hospitality_room') == 0 )
            echo " selected='selected'";
          echo ">Hospitality Room</option>";
          echo "<option value='dhome_3-vendeshe'";
          if(strcmp($llojiDhomes,'dhome_3-vendeshe') == 0 )
              echo " selected='selected'";
          echo ">Dhome 3-vendeshe</option>";
        ?>
        </select>
        <br/><br/>
        Nr.i dhomave:<br/>
        <input id="nr_i_dhomave" name="nr_i_dhomave" type="number"  min="1" max="6"
          value="<?php echo $nrDhomave; ?>"
        ><br/><br/>
      </div>
      <div class="reserv">
        Nr.i të rriturve:<br/>
        <input id="nr_te_rriturve" name="nr_te_rriturve" type="number"
          <?php
            switch($llojiDhomes) {
              case "deluxe_room": echo "min='1' max='2' value='$nrTeRriturve'";break;
              case "junior_suite": echo "min='1' max='4' value='$nrTeRriturve'";break;
              case "hospitality_room": echo "min='1' max='2' value='$nrTeRriturve'";break;
              case "dhome_3-vendeshe": echo "min='1' max='3' value='$nrTeRriturve'";break;
            }
          ?>
        >
        <?php if(!empty($errLlojiDhomes)) echo "<br/><span class='required'></span>";
        ?>
        <br/><br/>
        Nr.i fëmijëve:<br/>
        <input id="nr_femijeve" type="number" name="nr_i_femijeve"
          <?php
            switch($llojiDhomes) {
              case "deluxe_room": echo "min='0' max='2' value='$nrFemijeve'";break;
              case "junior_suite": echo "min='0' max='3' value='$nrFemijeve'";break;
              case "hospitality_room": echo "min='0' max='2' value='$nrFemijeve'";break;
              case "dhome_3-vendeshe": echo "min='0' max='3' value='$nrFemijeve'";break;
            }
          ?>
        ><br/><br/>
      </div>
      <div class="reserv" id="d5">
        Rezervimi bëhet nga data:<br/>
        <input type="date" name="from" id="from" onchange="pickedUpFromDate()" value="<?php echo $dateFrom;?>"
          min="<?php $date = date('Y-m-d'); echo $date;?>" value="<?php echo $dateFrom;?>"
        >
        <?php if(!empty($errDateFrom)) echo "<br/><span class='required'>$errDateFrom</span>"; ?>
        <br/><br/>
        </div>
        <div class="reserv">
          Deri më datën:<br/>
            <!-- Bllokimi i min date me PHP -->
          <input type="date" name="to" id="to" value="<?php echo $dateTo;?>" min="<?php
            $date = explode('-',$date);
            $date[2] = $date[2] + 1;
            if($date[2] < 10)
              $date[2] = '0'.$date[2];
            $date = implode('-',$date);
            echo $date;
          ?>">
          <?php if(!empty($errDateTo)) echo "<br/><span class='required'>$errDateTo</span>"; ?>
          <br/><br/>
        </div>
    </fieldset><br/>
      <input type="submit" name="change" value="Ruaj ndryshimet" >
      <input type="submit" name="delete" value="Fshije rezervimin">
  </form>
</div>
<?php
  }
  require_once("hotel2footer.php");
?>
