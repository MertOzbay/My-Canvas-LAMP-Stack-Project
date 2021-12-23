<html>
  <head>
    <title>My Canvas: Add Assignment</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>
    <div>
      <h3>Adding Assignment...</h3>
      <?php
      // # establish connection to cs377 database
      // echo "before connection";
      // if (isset($_POST['assignment_text'])) {print("IS SET!!");}
      $conn = mysqli_connect("localhost",
                             "cs377", "ma9BcF@Y", "my_canvas");
      // print "\n";
      // print "after connection";
      // # make sure no error in connection
      if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit(1);
      }

      if (isset($_POST['assignment_text'])) {
          $result = add_assignment($_POST['cName'], $_POST['cNo'], $_POST['semester'],
                                  $_POST['year'], $_POST['dueDate'],$_POST['assignment_text'],
                                  $_POST['totalPoints'], $_POST['instructorID'],$conn);

          reset($_POST['assignment_text']);
      }

      mysqli_free_result($result);
      mysqli_close($conn);



      ?>

    </div>
  </body>
</html>
<?php
function add_assignment($fcName, $fcNo, $fsmstr, $fyear, $fdueDate, $ftext, $fpoints, $finstructorID, $fconn) {

  $fcName = str_replace("_", " ",$fcName);
  $insert = "INSERT INTO assignment(cName, cNo, semester, year, dueDate, assignment_text, totalPoints)
            VALUES('".$fcName."', '".$fcNo."', '".$fsmstr."', '".$fyear."', '".$fdueDate."', '".$ftext."', ".$fpoints.")";
            if (!( $rfesult = mysqli_query($fconn, $insert))){
              printf("Error: %s\n", mysqli_error($fconn));
              print("<form method=\"post\" action=\"class_page.php\">
              <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $fcName).">

              <input type=\"hidden\" name=\"cNo\" value=".$fcNo.">

              <input type=\"hidden\" name=\"semester\" value=".$fsmstr.">

              <input type=\"hidden\" name=\"year\" value=".$fyear.">

              <input type=\"hidden\" name=\"studentID\" value=".$finstructorID.">

              <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

              <input type=\"submit\" value=\"Go back to class page\">
              </form>
              ");
              exit(1);
            } else {
              print(
                "<h4>Assignment added.</h4>
                <form method=\"post\" action=\"class_page.php\">

                <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $fcName).">

                <input type=\"hidden\" name=\"cNo\" value=".$fcNo.">

                <input type=\"hidden\" name=\"semester\" value=".$fsmstr.">

                <input type=\"hidden\" name=\"year\" value=".$fyear.">

                <input type=\"hidden\" name=\"studentID\" value=".$finstructorID.">

                <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

                <input type=\"submit\" value=\"Go back to class page\">
                </form>"
            );
            }
}
 ?>
