<!DOCTYPE html>
<html>
<meta charset="utf-8" >
<head>
<title>*****MOUNTAIN HOTEL*****</title>
<link rel="stylesheet" type="text/css" href="identifikohu.css" />
</head>
<body>
<?php require_once("hotel2header.php"); ?>
<div class="form1">
  <form method="post" action="hotel2rezervo.php" style=" color:#2F4F4F ;width: 350px; margin: 40px auto;">
    <fieldset>
      <legend style="font-size:100%;font-weight:lighter;">Ju lutemi ,identifikohuni për të vazhduar</legend>
        <div style="padding:10px 100px;">
          <span style="display:inline;">Emaili:<input type="email" autofocus></span></br></br>
          <span>Fjalëkalimi:<input type="password" ></span></br></br>
        </div>
        <span class="d1" style="padding:0 5px 20px 100px ;">
        <input  type="submit" name="submit" value="Paraqit" ></span><span class="d1" ><input type="reset" value="Reseto"></span>
    </fieldset>
  </form>
</div>
<?php require_once("hotel2footer.php"); ?>
