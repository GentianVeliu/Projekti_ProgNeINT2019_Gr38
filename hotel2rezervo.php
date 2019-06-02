<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>*****MOUNTAIN HOTEL*****</title>
  <link rel="stylesheet" type="text/css" href="rezervo.css" />
  <script type="text/javascript" src="room_fields.js"></script>
</head>
<body>
<?php
  require_once("hotel2header.php");
  require_once("dbconn.php");

  //deklarimi i variablave te cilat do te na duhet per input fileds
  $email=$telefoni=$dateFrom=$dateTo="";
  $errEmail=$errTelefoni=$errDateFrom=$errDateTo=$errLlojiDhomes="";
  $valEmail=$valTelefoni="";
  $llojiDhomes=$nrDhomave=$nrTeRriturve=$nrFemijeve="";
  $show=true;

  class EmailException extends Exception {
    public function errMsg() {
      $msg = "Shkruaj emailin sipas formatit te duhur";
      return $msg;
    }
  }
  //Nqs te dhenat jane paraqitur
  if(isset($_POST['submit'])) {
    //marrja e nje pjese te te dhenave dhe validimi i tyre para se ti ruajme ne database
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

    if(empty($_POST['lloji_i_dhomes']))
      $errLlojiDhomes = "Zgjedhe dhomen";
    else {
      $llojiDhomes=$_POST['lloji_i_dhomes'];
    }
    //merr te dhenat shtese te cilat kemi per ti ruajtur ne database
    $nrDhomave=$_POST['nr_i_dhomave'];
    $nrTeRriturve=$_POST['nr_te_rriturve'];
    $nrFemijeve=$_POST['nr_i_femijeve'];
    //nqs te gjitha te dhenat jane plotesuar dhe validimi eshte ne rregull atehere i shtojme te dhenat ne database
    if(empty($errEmail)  && empty($errTelefoni) && empty($errDateFrom) && empty($errLlojiDhomes)
      && empty($errDateTo)&& empty($valEmail) && empty($valTelefoni)){
        //shnderro nga formati psh junior_suite ne Junior_Suite
         $llojiDhomes=preg_split("/[_]+/",$llojiDhomes);
         $llojiDhomes=join(" ",$llojiDhomes);
         $llojiDhomes=ucwords($llojiDhomes);
         // lidhja me database
         $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Error connecting with database");
         $queryOfEmails = "SELECT * FROM EmailList";
         $emails = mysqli_query($dbc,$queryOfEmails);
         //testo nese emaili vec ekziston dhe mos e shto edhe njehere
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
        //shtimi i rezervimit ne Booking relation
         $queryOfBooking = "INSERT INTO Booking VALUES(default,$emailId,
           (SELECT roomId FROM Room WHERE roomName = '$llojiDhomes'),'$dateFrom','$dateTo',$nrDhomave,$nrTeRriturve,$nrFemijeve)";
         $result = mysqli_query($dbc,$queryOfBooking) or die("Error quering booking data with database");

         $bookingId = mysqli_insert_id($dbc);
         //shtimi i contact number ne Contacts relation
         $queryOfContact = "INSERT INTO Contacts VALUES(default,$bookingId,'$telefoni')";

         $result = mysqli_query($dbc,$queryOfContact) or die("Error quering contacts data");

         echo "<div class='form1'>";
         echo "<p class='areservation' style='font-size:130%;font-weight:bold;color:#104E8B;'>Rezervimi u realizua me sukses</p>";
         echo "<p class='areservation'>MOUNTAIN Hotel ju falenderon qe na keni perzgjedhur neve si hotelin tuaj</p>";
         echo "<p class='areservation'>Numri i rezervimit tuaj eshte <span style='color:blue;text-decoration:underline;'>$bookingId</span>,
             ju duhet ta ruani kete kete numer me kujdes, pasi cdo ndryshim ne rezervim apo
             anulimin e tere te tij e beni ne baze te ketij numri</p>";
         echo "<p class='areservation' style='color:#104E8B;''>Ne qofte se deshironi te ndryshoni rezervimin apo ta anuloni ate vazhdoni
             <a href='hotel2anulo.php'>ketu</a>";

         if(!is_file("email_subject.txt")) {
           echo "<p class='areservation'>Email nuk u dergua tek rezervuesi ka probleme me hapjen e email_subject file</p>";
           echo "</div>";
           mysqli_close($dbc);
           require_once("hotel2footer.php");
           die("");
         }
         else{
           $myfile = fopen("email_subject.txt","r");
           $subject = fread($myfile,filesize("email_subject.txt"));
           fclose($myfile);
         }

         if(!is_file("email_message.txt")) {
           echo "<p class='areservation'>Email nuk u dergua tek rezervuesi ka probleme me hapjen e email_message file</p>";
           echo "</div>";
           mysqli_close($dbc);
           require_once("hotel2footer.php");
           die("");
         }
         else{
           $myfile = fopen("email_message.txt","r");
           $message = fread($myfile,filesize("email_message.txt"));
           fclose($myfile);
         }

         if(mail($email,$subject,$message)) {
            echo "<p class='areservation'>Email u dergua me sukses</p>";
            echo "</div>";
          }
         else {
            echo "<p class='areservation'>Email nuk u dergua</p>";
            echo error_get_last()['message'];
            echo "</div>";
          }

         //mbyllja e lidhjes me database
         mysqli_close($dbc);

         //mos e shfaq permbajtjen e meposhtme pervec footerit
         $show=false;
      }
  }

  if($show) {
?>

<div class="form1">
  <form autocomplete="off"  method="post" action="hotel2rezervo.php" style="width:600px; margin:40px auto;">
    <fieldset>
      <legend style="color:#2F4F4F;">Informatat personale </legend>
      <div class="reserv">
        <label>Email</label><br/>
        <input type="text" name="email"  value="<?php echo $email; ?>" autofocus>
        <?php if(!empty($errEmail)) echo "<br/><span class='required'>$errEmail</span>";
          else if(!empty($valEmail)) echo "<br/><span class='required'>$valEmail</span>";
        ?>
        <br/><br/>
      </div>
      <div class="reserv">
        <label>Telefoni</label><br/>
        <input type="tel" name="telefoni" value="<?php echo $telefoni; ?>">
        <?php if(!empty($errTelefoni)) echo "<br/><span class='required'>$errTelefoni</span>";
              else if(!empty($valTelefoni)) echo "<br/><span class='required'>$valTelefoni</span>";
        ?>
        <br/><br/>
      </div>
    </fieldset>
    <fieldset>
      <legend style="color:#2F4F4F;">Rezervimi</legend>
      <div class="reserv">
        <label>Zgjedhe llojin e dhomes:</label><br/>
        <select id="dhoma" name="lloji_i_dhomes" onchange="pickedUpRoom()" >
          <?php
            //gjenerimi i options te select elementit ne menyre dinamike
            echo "<option value=''";
            if(strcmp($llojiDhomes,'') == 0 )
              echo " selected='selected'>";
            echo "</option>";
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
        <?php if(!empty($errLlojiDhomes)) echo "<br/><span class='required'>$errLlojiDhomes</span>";
        ?>
        <br/><br/>
        <label>Nr.i dhomave:</label><br/>
        <input id="nr_i_dhomave" name="nr_i_dhomave" type="number"
          <?php
            if(strcmp($llojiDhomes,"") == 0) {
              echo "min='1' max='0' value=''";
            } else {
              echo "min='1' max='6' value='$nrDhomave'";
            }
          ?>>
        <br/><br/>
      </div>
      <div class="reserv">
        <label>Nr.i të rriturve:</label><br/>
        <input id="nr_te_rriturve" name="nr_te_rriturve" type="number"
          <?php
            switch($llojiDhomes) {
              case "": echo "min='1' max='0' value=''";break;
              case "deluxe_room": echo "min='1' max='2' value='$nrTeRriturve'";break;
              case "junior_suite": echo "min='1' max='4' value='$nrTeRriturve'";break;
              case "hospitality_room": echo "min='1' max='2' value='$nrTeRriturve'";break;
              case "dhome_3-vendeshe": echo "min='1' max='3' value='$nrTeRriturve'";break;
            }
          ?>>
         <br/><br/>
         <?php if(!empty($errLlojiDhomes)) echo "<br/><span class='required'></span>";
         ?>
        <label>Nr.i fëmijëve:</label><br/>
        <input id="nr_femijeve" type="number" name="nr_i_femijeve"
        <?php
          switch($llojiDhomes) {
            case "": echo "min='0' max='-1' value=''";break;
            case "deluxe_room": echo "min='0' max='2' value='$nrFemijeve'";break;
            case "junior_suite": echo "min='0' max='3' value='$nrFemijeve'";break;
            case "hospitality_room": echo "min='0' max='2' value='$nrFemijeve'";break;
            case "dhome_3-vendeshe": echo "min='0' max='3' value='$nrFemijeve'";break;
          }
        ?>>
        <br/><br/>
      </div>
      <div class="reserv" id="d5">
      <!--Bllokimi i min date me PHP  -->
        <label>Rezervimi bëhet nga data:</label><br/>
        <input type="date" name="from" id="from" min="<?php $date = date('Y-m-d'); echo $date;?>" value="<?php echo $dateFrom;?>"
          onchange="pickedUpFromDate()">
        <?php if(!empty($errDateFrom)) echo "<br/><span class='required'>$errDateFrom</span>";?>
          <br/><br/>
        </div>
        <div class="reserv">
          <label>Deri më datën:</label><br/>
            <!-- Bllokimi i min date me PHP -->
          <input type="date" name="to" id="to"  min="<?php $date = explode('-',$date);
            $date[2] = $date[2] + 1;
            if($date[2] < 10)
              $date[2] = '0'.$date[2];
            $date = implode('-',$date);
            echo $date;
            ?>"
            value="<?php echo $dateTo;?>">
          <?php if(!empty($errDateTo)) echo "<br/><span class='required'>$errDateTo</span>"; ?>
          <br/><br/>
        </div>
    </fieldset><br/>
      <input type="submit" name="submit" value="Rezervo" >
      <input  type="reset" value="Reseto">
  </form>
</div>
<?php
  }
  require_once("hotel2footer.php");
?>
