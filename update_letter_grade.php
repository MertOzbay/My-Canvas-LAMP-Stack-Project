<html>
  <head>
    <title>My Canvas — Update Letter Grade</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>
    <div>
      <h3>Updating Grades...</h3>
      <?php
      // # establish connection to cs377 database
      // echo "before connection";
      $conn = mysqli_connect("localhost",
                             "cs377", "ma9BcF@Y", "my_canvas");
      // print "\n";
      // print "after connection";
      // # make sure no error in connection
      if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit(1);
      }

      if (isset($_POST['letter'])) {
          $result = update_grade($_POST['cName'], $_POST['cNo'], $_POST['semester'],
                                  $_POST['year'], $_POST['studentID'], $_POST['letter'], $_POST['graderID'],$conn);

          reset($_POST['letter']);
      }

      // ADD ASSIGNMENT BUTTON HERE

      mysqli_free_result($result);
      mysqli_close($conn);



      ?>

    </div>
  </body>
</html>

<?php
function update_grade($fcName, $fcNo, $fsmstr, $fyear, $fstudentID, $fletter, $fgraderID, $fconn) {

  $fcName = str_replace("_", " ",$fcName);
  $update = "UPDATE takes
            SET letter = '$fletter'
            WHERE cName = '$fcName'
            AND cNo = '$fcNo'
            AND semester = '$fsmstr'
            AND year = '$fyear'
            AND studentID = '$fstudentID'";

            if (!( $rfesult = mysqli_query($fconn, $update))){
              printf("Error: %s\n", mysqli_error($fconn));
              print(
                "<form method=\"post\" action=\"class_page.php\">

                <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $fcName).">

                <input type=\"hidden\" name=\"cNo\" value=".$fcNo.">

                <input type=\"hidden\" name=\"semester\" value=".$fsmstr.">

                <input type=\"hidden\" name=\"year\" value=".$fyear.">

                <input type=\"hidden\" name=\"studentID\" value=".$fgraderID.">

                <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

                <input type=\"submit\" value=\"Go back to grade list\">
                </form>"
            );
              exit(1);
            } else {
              print(
                "<h4>Grade succesfully updated.</h4>
                <form method=\"post\" action=\"class_page.php\">

                <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $fcName).">

                <input type=\"hidden\" name=\"cNo\" value=".$fcNo.">

                <input type=\"hidden\" name=\"semester\" value=".$fsmstr.">

                <input type=\"hidden\" name=\"year\" value=".$fyear.">

                <input type=\"hidden\" name=\"studentID\" value=".$fgraderID.">

                <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

                <input type=\"submit\" value=\"Go back to grade list\">
                </form>"
            );
            }
}
 ?>
