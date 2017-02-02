<?php
session_start();
require_once 'classes/members.php';
require_once 'classes/config.php';
include_once 'partials/message.php';

$members = new Members();

$members->confirm_Member();

//Redirects
if(!empty($_POST['voornaam']) && !empty($_POST['achternaam'])){
    header("location: lidworden.php");
}

//SHOW TABLE
try {
  $db = new PDO('mysql:host=localhost;dbname=' . DB_NAME, 'yannixs121_del', DB_PASSWORD);

  $queryString = "SELECT * FROM boeken_uc";

  $statement = $db->prepare($queryString);

  $statement->execute();

  $boekenArray = $statement->fetchAll(PDO::FETCH_ASSOC);


  //SHOW EDIT ROW
  if(isset($_GET['edit']))
  {
    //get row to show in VALUES
    $id = $_GET['edit'];

    $queryShowRow = "SELECT * FROM boeken_uc WHERE ID = :id";

    $statementShowRow = $db->prepare($queryShowRow);

    $statementShowRow->bindValue(':id', $id);

    $statementShowRow->execute();

    $boekenEditShow = $statementShowRow->fetch(PDO::FETCH_ASSOC);
  }
  //UPDATE ROW
  if(isset($_POST['wijzigen']))
  {
    $queryWijzigen = "UPDATE boeken_uc SET naam = :naam, titel = :titel, auteur = :auteur, vraagprijs = :vraagprijs, opmerking = :opmerking, telnr = :telnr, email = :email WHERE id = :id LIMIT 1";

    $statementWijzigen = $db->prepare($queryWijzigen);

    $statementWijzigen->bindValue(":naam", $_POST['naam']);
    $statementWijzigen->bindValue(":titel", $_POST['titel']);
    $statementWijzigen->bindValue(":auteur", $_POST['auteur']);
    $statementWijzigen->bindValue(":vraagprijs", $_POST['vraagprijs']);
    $statementWijzigen->bindValue(":opmerking", $_POST['opmerking']);
    $statementWijzigen->bindValue(":telnr", $_POST['telnr']);
    $statementWijzigen->bindValue(":email", $_POST['email']);
    $statementWijzigen->bindValue(":id", $_POST['id']);

    $success = $statementWijzigen->execute();

    if($success)
    {
      $_SESSION['notification']['type'] = "success";
      $_SESSION['notification']['text'] =  "Gelukt, boek nr " . $_POST['id'] . " met succes gewijzigd";
      header("Refresh:0;  url=index.php");
    }else{
      $_SESSION['notification']['type'] = "error";
      $_SESSION['notification']['text'] =  "Aanpassing is niet gelukt. Probeer opnieuw of neem contact op met de <a >systeembeheerder</a> wanneer deze fout blijft aanhouden. " ;
    }

  }
} catch (PDOException $e) {
  $_SESSION['notification']['type'] = "error";
  $_SESSION['notification']['text'] =  "Boek kon niet geupdate worden. " ;
}

//ADD row
if(isset($_POST['add'])){
  try {
    $naam = $_POST['naam'];
    $titel = $_POST['titel'];
    $auteur = $_POST['auteur'];
    $vraagprijs = $_POST['vraagprijs'];
    $opmerking = $_POST['opmerking'];
    $telnr = $_POST['telnr'];
    $email = $_POST['email'];

    $queryString = "INSERT INTO boeken_uc (naam, titel, auteur, vraagprijs, opmerking, telnr, email) VALUES (:naam, :titel, :auteur, :vraagprijs, :opmerking, :telnr, :email) ";

    $statement = $db->prepare($queryString);

    $statement->bindValue(':naam', $naam);
    $statement->bindValue(':titel', $titel);
    $statement->bindValue(':auteur', $auteur);
    $statement->bindValue(':vraagprijs', $vraagprijs);
    $statement->bindValue(':opmerking', $opmerking);
    $statement->bindValue(':telnr', $telnr);
    $statement->bindValue(':email', $email);

    $success = $statement->execute();

    if($success){
      $_SESSION['notification']['type'] = "success";
      $_SESSION['notification']['text'] =  "Boek toegevoegd! " ;
      header("location: index.php");
    }
  } catch (PDOException $e) {
    $_SESSION['notification']['type'] = "error";
    $_SESSION['notification']['text'] =  "Kon rij niet toevoegen. " . $e->getMessage();
  }
}
//DELETE ROW
if(isset($_GET['delete'])){
  try {
    $queryString = "DELETE FROM boeken_uc WHERE id = :boekid";

    $statement = $db->prepare($queryString);

    $statement->bindValue(':boekid', $_GET['delete']);

    $success = $statement->execute();

    if($success){
      $_SESSION['notification']['type'] = "success";
      $_SESSION['notification']['text'] =  "Boek nr " . $_GET['delete'] . " is verwijderd";
      header("location: index.php");
    }

  } catch (PDOException $e) {
    $_SESSION['notification']['type'] = "error";
    $_SESSION['notification']['text'] =  "Kon rij niet verwijderen. " . $e->getMessage();
  }
}
 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="css/foundation.min.css">
     <link rel="stylesheet" href="css/master.css">
     <title>Boeken UC Brabo</title>
   </head>
   <body>
     <a href="login.php?status=loggedout">Log Out</a>
     <h1>Dashboard Boeken UC Brabo</h1>

     <ul>
       <li><a href="boeken.php">Boeken</a></li>
       <li><a href="verkopers.php">Verkopers</a></li>
       <li><a href="boeken-gezocht.php">Boeken Gezocht</a></li>
     </ul>
   </body>
 </html>
