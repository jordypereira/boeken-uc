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

  $queryString = "SELECT * FROM boeken_uc_verkopers";

  $statement = $db->prepare($queryString);

  $statement->execute();

  $showArray = $statement->fetchAll(PDO::FETCH_ASSOC);


  //SHOW EDIT ROW
  if(isset($_GET['edit']))
  {
    //get row to show in VALUES
    $id = $_GET['edit'];

    $queryShowRow = "SELECT * FROM boeken_uc_verkopers WHERE id = :id";

    $statementShowRow = $db->prepare($queryShowRow);

    $statementShowRow->bindValue(':id', $id);

    $statementShowRow->execute();

    $EditShow = $statementShowRow->fetch(PDO::FETCH_ASSOC);
  }
  //UPDATE ROW
  if(isset($_POST['wijzigen']))
  {
    $queryWijzigen = "UPDATE boeken_uc_verkopers SET naam = :naam, achternaam = :achternaam, telnr = :telnr, email = :email WHERE id = :id LIMIT 1";

    $statementWijzigen = $db->prepare($queryWijzigen);

    $statementWijzigen->bindValue(":naam", $_POST['naam']);
    $statementWijzigen->bindValue(":achternaam", $_POST['achternaam']);
    $statementWijzigen->bindValue(":telnr", $_POST['telnr']);
    $statementWijzigen->bindValue(":email", $_POST['email']);
    $statementWijzigen->bindValue(":id", $_POST['id']);

    $success = $statementWijzigen->execute();

    if($success)
    {
      $_SESSION['notification']['type'] = "success";
      $_SESSION['notification']['text'] =  "Gelukt, verkoper nr " . $_POST['id'] . " met succes gewijzigd";
      header("Refresh:0;  url=verkopers.php");
    }else{
      $_SESSION['notification']['type'] = "error";
      $_SESSION['notification']['text'] =  "Aanpassing is niet gelukt. Probeer opnieuw of neem contact op met de <a >systeembeheerder</a> wanneer deze fout blijft aanhouden. " ;
    }

  }
} catch (PDOException $e) {
  $_SESSION['notification']['type'] = "error";
  $_SESSION['notification']['text'] =  "Verkoper kon niet geupdate worden. " ;
}

//ADD row
if(isset($_POST['add'])){
  try {
    $queryString = "INSERT INTO boeken_uc_verkopers (naam, achternaam, telnr, email) VALUES (:naam, :achternaam, :telnr, :email) ";

    $statement = $db->prepare($queryString);

    $statement->bindValue(':naam', $_POST['naam']);
    $statement->bindValue(':achternaam', $_POST['achternaam']);
    $statement->bindValue(':telnr', $_POST['telnr']);
    $statement->bindValue(':email', $_POST['email']);

    $success = $statement->execute();

    if($success){
      $_SESSION['notification']['type'] = "success";
      $_SESSION['notification']['text'] =  "Verkoper toegevoegd! " ;
      header("location: verkopers.php");
    }
  } catch (PDOException $e) {
    $_SESSION['notification']['type'] = "error";
    $_SESSION['notification']['text'] =  "Kon rij niet toevoegen. " . $e->getMessage();
  }
}
//DELETE ROW
if(isset($_GET['delete'])){
  try {
    $queryString = "DELETE FROM boeken_uc_verkopers WHERE id = :id";

    $statement = $db->prepare($queryString);

    $statement->bindValue(':id', $_GET['delete']);

    $success = $statement->execute();

    if($success){
      $_SESSION['notification']['type'] = "success";
      $_SESSION['notification']['text'] =  "Verkoper nr " . $_GET['delete'] . " is verwijderd";
      header("location: verkopers.php");
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
     <title>Verkopers UC Brabo</title>
   </head>
   <body>
     <a href="login.php?status=loggedout">Log Out</a>
     <ul>
       <li><a href="index.php">Terug naar Dashboard</a></li>
     </ul>
     <h1>Verkopers</h1>

     <?php   include_once 'partials/message-show.php'; ?>

     <?php if(isset($_GET['edit'])): ?>

      <h3>Verkoper <?= $EditShow['naam'] ?> ( #<?= $EditShow['id'] ?> ) wijzigen</h3>

      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="text" name="naam" value="<?= $EditShow['naam'] ?>">
        <input type="text" name="achternaam" value="<?= $EditShow['achternaam'] ?>">
        <input type="text" name="telnr" value="<?= $EditShow['telnr'] ?>">
        <input type="text" name="email" value="<?= $EditShow['email'] ?>">
        <input type="hidden" name="id" value="<?= $EditShow['id'] ?>">

        <input type="submit" class="button" name="wijzigen" value="Wijzigen">
      </form>
    <?php endif; ?>
     <table>
       <thead>
         <td>#</td>
         <td>Naam</td>
         <td>Achternaam</td>
         <td>telnr</td>
         <td>email</td>
         <td></td>
         <td></td>
       </thead>
       <tbody>
         <?php foreach($showArray as $row): ?>
           <tr>
             <td><?= $row['id'] ?></td>
             <td><?= $row['naam'] ?></td>
             <td><?= $row['achternaam'] ?></td>
             <td><?= $row['telnr'] ?></td>
             <td><?= $row['email'] ?></td>
             <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
             <td>
                <button type="submit" name="edit" value="<?= $row['id'] ?>"><img class="icon" src="img/edit.png" alt="edit-icon"></button>
              </td>
             <td>
               <button type="submit" name="delete" value="<?= $row['id'] ?>"><img class="icon" src="img/remove-icon.png" alt="delete"></button>
             </td>
             </form>
           </tr>
         <?php endforeach; ?>
         <tr>
           <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
             <td>Add a new Seller</td>
             <td><input type="text" name="naam" placeholder="naam"></td>
             <td><input type="text" name="achternaam" placeholder="achternaam"></td>
             <td><input type="text" name="telnr" placeholder="telnr"></td>
             <td><input type="text" name="email" placeholder="email"></td>
             <td><input class="button" type="submit" name="add" value="Add"></td>
           </form>
         </tr>
       </tbody>
     </table>
   </body>
 </html>
