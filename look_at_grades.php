<!DOCTYPE html>

<html>
  <head>
    <title>My Canvas — Assignment Grades</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>
    <div>

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
      if (isset($_POST['newScore'])) {print("IS SET");}


      // # get the query that was posted
      $graderID = $_POST["graderID"];
      $smstr = $_POST["semester"];
      $year = $_POST["year"];
      $cNo = $_POST["cNo"];
      $cName = str_replace("_", " ",$_POST["cName"]);
      $dueDate = date("Y-m-d", strtotime($_POST['dueDate']));
      // print($_POST['dueDate']);


      print ("<h3>Assignments for ".$cNo.": "."$cName"."</h3>");
      // # execute the query
      // $query = "SELECT studentID, fName, lName, dueDate, score, totalPoints
      //           FROM assignment
      //           JOIN grades USING(cNo, cName, semester, year, dueDate)
      //           RIGHT JOIN student USING(studentID)
      //           WHERE cName = '$cName'
      //           AND cNo = '$cNo'
      //           AND semester = '$smstr'
      //           AND year = '$year'
      //           AND dueDate = '$dueDate'
      //           ORDER BY dueDate DESC";

      $query = "WITH classstudents AS (SELECT studentID, fName, lName FROM takes
                    JOIN student USING(studentID)
                    WHERE cName = '$cName'
                    AND cNo = '$cNo'
                    AND semester = '$smstr'
                    AND year = '$year'),
                    classassignment AS (SELECT * FROM assignment
                    WHERE cName = '$cName'
                    AND cNo = '$cNo'
                    AND semester = '$smstr'
                    AND year = '$year'
                    AND dueDate = '$dueDate'),
                    studentlist AS (SELECT * FROM classstudents, classassignment)
                SELECT studentID, fName, lName, dueDate, score, totalPoints
                FROM studentlist
                LEFT JOIN grades USING(cNo, cName, semester, year, dueDate, studentID)
                ";

      if (!( $result = mysqli_query($conn, $query))){
        printf("Error: %s\n", mysqli_error($conn));
        exit(1);
      }

      print("<div><table class=\"table table-striped\"\n");
      $header = false;
      $A;
      $counter = 0;
      while ($row = mysqli_fetch_assoc($result)) {
        // # print the attribute names once!
        if (!($header)) {
          print("<!-- print header once -->");
          $header = true;
          // # specify the header to be dark class
          print("<thead class=\"table-dark\"><tr>\n");
          foreach ($row as $key => $value) {
            if ($key == 'fName'){
              print "<th>" . 'First Name' . "</th>";
            }elseif ($key == 'lName'){
              print "<th>" . 'Last Name' . "</th>";
            }elseif ($key == 'totalPoints'){
              print "<th>" . 'Out of' . "</th>";
            }elseif ($key == 'dueDate'){
              print "<th>" . 'Due Date' . "</th>";
            }elseif ($key == 'score'){
              print "<th>" . 'Score' . "</th>";
            }else {
              print "<th>" . $key . "</th>";
            }

          }
          print "<th> Update Score </th>";
          print("</tr></thead>\n");
        }
        print("<tr>\n");    // # Start row of HTML table

        foreach ($row as $key => $value) {
          print ("<td>" . $value . "</td>"); //# One item in row


          $A[$counter][$key] = $value;
        }
        $studentID = $A[$counter]["studentID"];

        $btn ="<td>
        <form method=\"post\" action=\"update_grade.php\">

        <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $cName).">

        <input type=\"hidden\" name=\"cNo\" value=".$cNo.">

        <input type=\"hidden\" name=\"semester\" value=".$smstr.">

        <input type=\"hidden\" name=\"year\" value=".$year.">

        <input type=\"hidden\" name=\"graderID\" value=".$graderID.">

        <input type=\"hidden\" name=\"dueDate\" value=".$dueDate.">

        <input type=\"hidden\" name=\"studentID\" value=".$studentID.">

        <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

        <input type=\"text\" name=\"newScore\">

        <input type=\"submit\" value=\"Update\">
        </form>
        </td>";

        print($btn);

        print ("</tr>\n");   //# End row of HTML table
        $counter = $counter + 1;
      }
      print("</table></div>\n");

      print("<form method=\"post\" action=\"class_page.php\">

      <input type=\"hidden\" name=\"cName\" value=".$_POST["cName"].">

      <input type=\"hidden\" name=\"cNo\" value=".$cNo.">

      <input type=\"hidden\" name=\"semester\" value=".$smstr.">

      <input type=\"hidden\" name=\"year\" value=".$year.">

      <input type=\"hidden\" name=\"studentID\" value=".$_POST["graderID"].">

      <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

      <input type=\"submit\" value=\"Go back to class page\">
      </form>");

      mysqli_free_result($result);
      mysqli_close($conn);



      ?>

    </div>
  </body>
</html>
