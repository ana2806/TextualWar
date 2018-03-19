<?php
  require_once ('textwar_logic.php');
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Textual war</title>
  </head>
  <body>
    <?php
      /*
        Ispisuje stanje na pocetku i na kraju borbe (broj vojnika u vojskama).
        Ispisuje pobjednicku vojsku, tj onu koja nije izgubila sve vojnike.
      */
      echo 'Početak borbe: <br>';
      echo 'Prva vojska: ' . $n1 . ' vojnika  <br>';
      echo 'Druga vojska: ' . $n2 . ' vojnika  <br><hr><br>';
      echo 'Kraj borbe: <br>';
      echo 'Prva vojska: ' . $vojska1->brojVojnika . ' vojnika  <br>';
      echo 'Druga vojska: ' . $vojska2->brojVojnika . ' vojnika  <br><br>';
      if( !$vojska2->brojVojnika )
        echo 'Pobijedila je prva vojska! <br><hr>';
      else
        echo 'Pobijedila je druga vojska! <br><hr>';
    ?>
  </body>
</html>
