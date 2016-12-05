<?php
require_once 'classes/members.php';
$members = new Members();

$members->confirm_Member();
$messageContainer = '';
//Redirects
if(!empty($_POST['voornaam']) && !empty($_POST['achternaam'])){
    header("location: lidworden.php");
}
//SHOW TABLE
try {
  $db = new PDO('mysql:host=localhost;dbname=yannixs121_brabo', 'yannixs121_del', 'f3autvqm');

  $queryString = "SELECT * FROM boeken_uc";

  $statement = $db->prepare($queryString);

  $statement->execute();

  $boekenArray = array();

  while($row = $statement->fetch(PDO::FETCH_ASSOC)){
    $boekenArray[] = $row;
  }

} catch (PDOException $e) {
  $messageContainer = "Er ging iets mis: " . $e->getMessage();
}

//ADD row
if(isset($_POST['add'])){
  try {
    $naam = $_POST['naam'];
    $titel = $_POST['titel'];
    $auteur = $_POST['auteur'];
    $vraagprijs = $_POST['vraagprijs'];
    $opmerking = $_POST['opmerking'];

    $queryString = "INSERT INTO boeken_uc (naam, titel, auteur, vraagprijs, opmerking) VALUES (:naam, :titel, :auteur, :vraagprijs, :opmerking) ";

    $statement = $db->prepare($queryString);

    $statement->bindValue(':naam', $naam);
    $statement->bindValue(':titel', $titel);
    $statement->bindValue(':auteur', $auteur);
    $statement->bindValue(':vraagprijs', $vraagprijs);
    $statement->bindValue(':opmerking', $opmerking);

    $statement->execute();

    $messageContainer = "Boek toegevoegd!";
  } catch (PDOException $e) {
    $messageContainer = "Er ging iets mis: " . $e->getMessage();
  }
}
//DELETE ROW
if(isset($_GET['delete'])){
  try {
    $queryString = "DELETE FROM boeken_uc WHERE id = :boekid";

    $statement = $db->prepare($queryString);

    $statement->bindValue(':boekid', $_GET['delete']);

    $statement->execute();

    $messageContainer = "Boek nr " . $_GET['delete'] . " deleted";

  } catch (PDOException $e) {
    $messageContainer = "Er ging iets mis: " . $e->getMessage();
  }
}
 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <link rel="stylesheet" href="css/foundation.min.css">
     <link rel="stylesheet" href="css/master.css">
     <title>Boeken UC Brabo</title>
   </head>
   <body>
     <a href="login.php?status=loggedout">Log Out</a>
     <h1>Boeken UC Brabo</h1>
     <p><?= $messageContainer ?></p>
     <table>
       <thead>
         <td>#</td>
         <td>Naam</td>
         <td>Titel</td>
         <td>Auteur</td>
         <td>Vraagprijs</td>
         <td>Opmerking</td>
         <td></td>
       </thead>
       <tbody>
         <?php foreach($boekenArray as $row): ?>
           <tr>
             <td><?= $row['ID'] ?></td>
             <td><?= $row['naam'] ?></td>
             <td><?= $row['titel'] ?></td>
             <td><?= $row['auteur'] ?></td>
             <td>&euro;<?= $row['vraagprijs'] ?></td>
             <td><?= $row['opmerking'] ?></td>
             <td><form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
               <button type="submit" name="delete" value="<?= $row['ID'] ?>"><img class="delete" src="img/remove-icon.png" alt="delete"></button>
             </form></td>
           </tr>
         <?php endforeach; ?>
         <tr>
           <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
             <td>Add a new book</td>
             <td><input type="text" name="naam" placeholder="naam"></td>
             <td><input type="text" name="titel" placeholder="titel"></td>
             <td><input type="text" name="auteur" placeholder="auteur"></td>
             <td><input type="text" name="vraagprijs" placeholder="vraagprijs"></td>
             <td><input type="text" name="opmerking" placeholder="opmerking"></td>
             <td><input class="button" type="submit" name="add" value="Add"></td>
           </form>
         </tr>
       </tbody>
     </table>
   </body>
 </html>
