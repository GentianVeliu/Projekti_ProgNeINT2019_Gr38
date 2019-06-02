<?php
  $cookie_name = "lojtari";
  $cookie_value = 0;
  if(!isset($_COOKIE['lojtari']))
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>*****MOUNTAIN HOTEL*****</title>
  <link rel="stylesheet" type="text/css" href="loja.css" />
</head>
<body>
<?php
  require_once("hotel2header.php");
  $lojtarifitues="";
  $kaperfunduarloja = false;

  function findWinner() {
    if(isset($_POST['btn1']) && isset($_POST['btn2']) && isset($_POST['btn3'])) {
      if($_POST['btn1'] == $_POST['btn2'] && $_POST['btn1'] ==  $_POST['btn3'])
        if($_POST['btn1'] == 'X'){
         $GLOBALS['lojtarifitues'] = "Ka fituar lojtari X";
         return true;
       }
        else {
          $GLOBALS['lojtarifitues'] = "Ka fituar lojtari O";
          return true;
        }
    }
    if(isset($_POST['btn1']) && isset($_POST['btn4']) && isset($_POST['btn7'])){
      if($_POST['btn1'] == $_POST['btn4'] && $_POST['btn1'] ==  $_POST['btn7'])
        if($_POST['btn1'] == 'X'){
         $GLOBALS['lojtarifitues'] = "Ka fituar lojtari X";
         return true;
       }
        else {
          $GLOBALS['lojtarifitues'] = "Ka fituar lojtari O";
          return true;
        }
    }
    if(isset($_POST['btn1']) && isset($_POST['btn5']) && isset($_POST['btn9'])){
      if($_POST['btn1'] == $_POST['btn5'] && $_POST['btn1'] ==  $_POST['btn9'])
        if($_POST['btn1'] == 'X'){
         $GLOBALS['lojtarifitues'] = "Ka fituar lojtari X";
         return true;
       }
        else {
          $GLOBALS['lojtarifitues'] = "Ka fituar lojtari O";
          return true;
        }
    }
    if(isset($_POST['btn4']) && isset($_POST['btn5']) && isset($_POST['btn6'])){
      if($_POST['btn4'] == $_POST['btn5'] && $_POST['btn4'] ==  $_POST['btn6'])
        if($_POST['btn4'] == 'X'){
         $GLOBALS['lojtarifitues'] = "Ka fituar lojtari X";
         return true;
       }
        else {
          $GLOBALS['lojtarifitues'] = "Ka fituar lojtari O";
          return true;
        }
    }
    if(isset($_POST['btn7']) && isset($_POST['btn8']) && isset($_POST['btn9'])){
      if($_POST['btn7'] == $_POST['btn8'] && $_POST['btn7'] ==  $_POST['btn9'])
        if($_POST['btn7'] == 'X'){
         $GLOBALS['lojtarifitues'] = "Ka fituar lojtari X";
         return true;
       }
        else {
          $GLOBALS['lojtarifitues'] = "Ka fituar lojtari O";
          return true;
        }
    }
    if(isset($_POST['btn2']) && isset($_POST['btn5']) && isset($_POST['btn8'])){
      if($_POST['btn2'] == $_POST['btn5'] && $_POST['btn2'] ==  $_POST['btn8'])
        if($_POST['btn2'] == 'X'){
         $GLOBALS['lojtarifitues'] = "Ka fituar lojtari X";
         return true;
       }
        else {
          $GLOBALS['lojtarifitues'] = "Ka fituar lojtari O";
          return true;
        }
    }
    if(isset($_POST['btn3']) && isset($_POST['btn6']) && isset($_POST['btn9'])){
      if($_POST['btn3'] == $_POST['btn6'] && $_POST['btn3'] ==  $_POST['btn9'])
        if($_POST['btn3'] == 'X'){
         $GLOBALS['lojtarifitues'] = "Ka fituar lojtari X";
         return true;
       }
        else {
          $GLOBALS['lojtarifitues'] = "Ka fituar lojtari O";
          return true;
        }
    }
    if(isset($_POST['btn3']) && isset($_POST['btn5']) && isset($_POST['btn7'])){
      if($_POST['btn3'] == $_POST['btn5'] && $_POST['btn3'] ==  $_POST['btn7'])
        if($_POST['btn3'] == 'X') {
         $GLOBALS['lojtarifitues'] = "Ka fituar lojtari X";
         return true;
       }
        else {
          $GLOBALS['lojtarifitues'] = "Ka fituar lojtari O";
          return true;
        }
    }
  }
  echo "<div class='form1'>";
  $kaperfunduarloja = findWinner();
  if($lojtarifitues)
    echo $lojtarifitues."<p>Loja ka perfunduar, filloni lojen e re <a href='hotel2loja.php'>ketu</a></p>";
?>
    <form  method="post" action="hotel2loja.php">
      <div class="line">
        <button type="submit" name="button1"
        <?php if(isset($_POST['button1']) || isset($_POST['btn1']) || $kaperfunduarloja) echo "disabled";?> readonly>
        <?php if(isset($_POST['button1']))
                if($_COOKIE['lojtari'] % 2 == 0){
                  echo "X</button><input type='hidden' name='btn1' value='X'>";
                  $_POST['btn1']='X';
                  setcookie($cookie_name, 1, time() + (86400 * 30), "/");
                  }
                else {
                  echo "O</button><input type='hidden' name='btn1' value='O'>";
                  setcookie($cookie_name, 0, time() + (86400 * 30), "/");
                  $_POST['btn1']='O';
                }
             elseif(isset($_POST['btn1']))
              echo $_POST['btn1']."</button><input type='hidden' name='btn1' value='".$_POST['btn1']."'>";
             else echo "X/O</button>";?>

         <button type="submit" name="button2"
         <?php if(isset($_POST['button2']) || isset($_POST['btn2'])  || $kaperfunduarloja) echo "disabled";?> readonly>
         <?php if(isset($_POST['button2']))
                 if($_COOKIE['lojtari'] % 2 == 0){
                   echo "X</button><input type='hidden' name='btn2' value='X'>";
                   $_POST['btn2']='X';
                   setcookie($cookie_name, 1, time() + (86400 * 30), "/");
                   }
                 else {
                   echo "O</button><input type='hidden' name='btn2' value='O'>";
                   $_POST['btn2']='O';
                   setcookie($cookie_name, 0, time() + (86400 * 30), "/");
                 }
              elseif(isset($_POST['btn2']))
               echo $_POST['btn2']."</button><input type='hidden' name='btn2' value='".$_POST['btn2']."'>";
              else echo "X/O</button>";?>

              <button type="submit" name="button3"
              <?php if(isset($_POST['button3']) || isset($_POST['btn3']) || $kaperfunduarloja) echo "disabled";?> readonly>
              <?php if(isset($_POST['button3']))
                    if($_COOKIE['lojtari'] % 2 == 0){
                      echo "X</button><input type='hidden' name='btn3' value='X'>";
                      $_POST['btn3']='X';
                      setcookie($cookie_name,1, time() + (86400 * 30), "/");
                      }
                    else {
                      echo "O</button><input type='hidden' name='btn3' value='O'>";
                      $_POST['btn3']='O';
                      setcookie($cookie_name, 0, time() + (86400 * 30), "/");
                    }
                   elseif(isset($_POST['btn3']))
                    echo $_POST['btn3']."</button><input type='hidden' name='btn3' value='".$_POST['btn3']."'>";
                   else echo "X/O</button>";?>


        <button type="submit" name="button4"
        <?php if(isset($_POST['button4']) || isset($_POST['btn4']) || $kaperfunduarloja) echo "disabled";?> readonly>
        <?php if(isset($_POST['button4']))
                if($_COOKIE['lojtari'] % 2 == 0){
                  echo "X</button><input type='hidden' name='btn4' value='X'>";
                  $_POST['btn4']='X';
                  setcookie($cookie_name, 1, time() + (86400 * 30), "/");
                  }
                else {
                  echo "O</button><input type='hidden' name='btn4' value='O'>";
                  $_POST['btn4']='O';
                  setcookie($cookie_name, 0, time() + (86400 * 30), "/");
                }
             elseif(isset($_POST['btn4']) )
              echo $_POST['btn4']."</button><input type='hidden' name='btn4' value='".$_POST['btn4']."'>";
             else echo "X/O</button>";?>

       <button type="submit" name="button5"
       <?php if(isset($_POST['button5']) || isset($_POST['btn5']) || $kaperfunduarloja) echo "disabled";?> readonly>
       <?php if(isset($_POST['button5']))
             if($_COOKIE['lojtari'] % 2 == 0){
               echo "X</button><input type='hidden' name='btn5' value='X'>";
               $_POST['btn5']='X';
               setcookie($cookie_name, 1, time() + (86400 * 30), "/");
               }
             else {
               echo "O</button><input type='hidden' name='btn5' value='O'>";
               $_POST['btn5']='O';
               setcookie($cookie_name, 0, time() + (86400 * 30), "/");
             }
            elseif(isset($_POST['btn5']))
             echo $_POST['btn5']."</button><input type='hidden' name='btn5' value='".$_POST['btn5']."'>";
            else echo "X/O</button>";?>

      <button type="submit" name="button6"
      <?php if(isset($_POST['button6']) || isset($_POST['btn6']) || $kaperfunduarloja) echo "disabled";?> readonly>
      <?php if(isset($_POST['button6']))
            if($_COOKIE['lojtari'] % 2 == 0){
              echo "X</button><input type='hidden' name='btn6' value='X'>";
              $_POST['btn6']='X';
              setcookie($cookie_name, 1, time() + (86400 * 30), "/");
              }
            else {
              echo "O</button><input type='hidden' name='btn6' value='O'>";
              $_POST['btn6']='O';
              setcookie($cookie_name, 0, time() + (86400 * 30), "/");
            }
           elseif(isset($_POST['btn6']))
            echo $_POST['btn6']."</button><input type='hidden' name='btn6' value='".$_POST['btn6']."'>";
           else echo "X/O</button>";?>

         <button type="submit" name="button7"
         <?php if(isset($_POST['button7']) || isset($_POST['btn7'])  || $kaperfunduarloja) echo "disabled";?> readonly>
         <?php if(isset($_POST['button7']))
                 if($_COOKIE['lojtari'] % 2 == 0){
                   echo "X</button><input type='hidden' name='btn7' value='X'>";
                   $_POST['btn7']='X';
                   setcookie($cookie_name, 1, time() + (86400 * 30), "/");
                   }
                 else {
                   echo "O</button><input type='hidden' name='btn7' value='O'>";
                   $_POST['btn7']='O';
                   setcookie($cookie_name, 0, time() + (86400 * 30), "/");
                 }
              elseif(isset($_POST['btn7']))
               echo $_POST['btn7']."</button><input type='hidden' name='btn7' value='".$_POST['btn7']."'>";
              else echo "X/O</button>";?>

        <button type="submit" name="button8"
        <?php if(isset($_POST['button8']) || isset($_POST['btn8']) || $kaperfunduarloja) echo "disabled";?> readonly>
        <?php if(isset($_POST['button8']))
                if($_COOKIE['lojtari'] % 2 == 0){
                  echo "X</button><input type='hidden' name='btn8' value='X'>";
                  $_POST['btn8']='X';
                  setcookie($cookie_name, 1, time() + (86400 * 30), "/");
                  }
                else {
                  echo "O</button><input type='hidden' name='btn8' value='O'>";
                  $_POST['btn8']='O';
                  setcookie($cookie_name, 0, time() + (86400 * 30), "/");
                }
             elseif(isset($_POST['btn8']))
              echo $_POST['btn8']."</button><input type='hidden' name='btn8' value='".$_POST['btn8']."'>";
             else echo "X/O</button>";?>

       <button type="submit" name="button9"
       <?php if(isset($_POST['button9']) || isset($_POST['btn9']) || $kaperfunduarloja) echo "disabled";?> readonly>
       <?php if(isset($_POST['button9']))
               if($_COOKIE['lojtari'] % 2 == 0){
                 echo "X</button><input type='hidden' name='btn9' value='X'>";
                 $_POST['btn9']='X';
                 setcookie($cookie_name, 1, time() + (86400 * 30), "/");
                 }
               else {
                 echo "O</button><input type='hidden' name='btn9' value='O'>";
                 $_POST['btn9']='O';
                 setcookie($cookie_name, 0, time() + (86400 * 30), "/");
               }
            elseif(isset($_POST['btn9']))
             echo $_POST['btn9']."</button><input type='hidden' name='btn9' value='".$_POST['btn9']."'>";
            else echo "X/O</button>";?>

      </div>
    </form>
  </div>
<?php
require_once("hotel2footer.php");
?>
