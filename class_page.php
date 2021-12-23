<!DOCTYPE html>

<html>
  <head>
    <title>Course Page — Teacher</title>
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

      // # get the query that was posted
      $stID = $_POST["studentID"];
      $smstr = $_POST["semester"];
      $year = $_POST["year"];
      $cNo = $_POST["cNo"];
      $cName =str_replace("_", " ",$_POST["cName"]);


      print ("<h3>Assignments for ".$cNo.": "."$cName"."
      </h3>");
      // # execute the query
      $query = "SELECT assignment_text, dueDate, totalPoints
                FROM assignment
                WHERE cName = '$cName'
                AND cNo = '$cNo'
                AND semester = '$smstr'
                AND year = '$year'
                ORDER BY dueDate DESC";

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
            if ($key == 'assignment_text'){
              print "<th>" . 'Description' . "</th>";
            }elseif ($key == 'totalPoints'){
              print "<th>" . 'Out of' . "</th>";
            }elseif ($key == 'dueDate'){
              print "<th>" . 'Due Date' . "</th>";
            } else {
              print "<th>" . $key . "</th>";
            }

          }
          print "<th> </th>";
          print("</tr></thead>\n");
        }
        print("<tr>\n");    // # Start row of HTML table

        foreach ($row as $key => $value) {
          print ("<td>" . $value . "</td>"); //# One item in row


          $A[$counter][$key] = $value;
        }
        $dDate = $A[$counter]["dueDate"];

        $btn ="<td>
        <form method=\"post\" action=\"look_at_grades.php\">

        <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $cName).">

        <input type=\"hidden\" name=\"cNo\" value=".$cNo.">

        <input type=\"hidden\" name=\"semester\" value=".$smstr.">

        <input type=\"hidden\" name=\"year\" value=".$year.">

        <input type=\"hidden\" name=\"graderID\" value=".$stID.">

        <input type=\"hidden\" name=\"dueDate\" value=".$dDate.">

        <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

        <input type=\"submit\" value=\"Update Grades\">
        </form>
        </td>";

        print($btn);

        print ("</tr>\n");   //# End row of HTML table
        $counter = $counter + 1;
      }
      print("</table></div>\n<br>");

      print("<h4 class=\"fs-3 mb-2 bg-dark text-white\">Add new assignment:</h4>
      <form method=\"post\" action=\"add_assignment.php\">

      <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $cName).">

      <input type=\"hidden\" name=\"cNo\" value=".$cNo.">

      <input type=\"hidden\" name=\"semester\" value=".$smstr.">

      <input type=\"hidden\" name=\"year\" value=".$year.">

      <input type=\"hidden\" name=\"instructorID\" value=".$stID.">

      <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

      <div class=\"row mb-4\">
        <div class=\"col\">
          <div class=\"form-outline\">
      Enter assignment description:
      <input type=\"text\" name=\"assignment_text\">
          </div>
        </div>

        <div class=\"col\">
          <div class=\"form-outline\">
      <label for=\"dueDate\">Enter due date:</label>
      <input type=\"date\" name=\"dueDate\" id=\"dueDate\">
          </div>
        </div>
      </div>

      <div class=\"row mb-4\">
        <div class=\"col\">
          <div class=\"form-outline\">
      <label for=\"totalPoints\">Enter total score:
      <input type=\"number\" name=\"totalPoints\" id=\"totalPoints\">
          </div>
        </div>

        <div class=\"col\">
      <input type=\"submit\" value=\"Submit\">
        </div>
      </div>
      </form><br>");

      mysqli_free_result($result);
      mysqli_close($conn);


      ?>

    </div>
    <div >
      <?php
      $stID = $_POST["studentID"];
      $smstr = $_POST["semester"];
      $year = $_POST["year"];
      $cNo = $_POST["cNo"];
       $cName =str_replace("_", " ",$_POST["cName"]);

      $conn = mysqli_connect("localhost",
                             "cs377", "ma9BcF@Y", "my_canvas");
      if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit(1);
      }

      print ("<h3>Students of ".$cNo.": "."$cName"."  </h3>");
      // <div class=\"d-grid gap-2 d-md-flex justify-content-md-end\">
      // </div>
      // <form method=\"post\" action=\"home_page.php\">
      // <input type=\"submit\" value=\"Back to Home Page\">
      // </form>

      // # execute the query
      $query2 = "SELECT studentID, fName, lName, letter
                FROM course
                JOIN takes USING(cName, cNo, semester, year)
                JOIN student USING(studentID)
                WHERE cName = '$cName'
                AND cNo = '$cNo'
                AND semester = '$smstr'
                AND year = '$year'
                AND instructorID = '$stID'
                ORDER BY studentID DESC";

      if (!( $result = mysqli_query($conn, $query2))){
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
            }elseif ($key == 'letter'){
              print "<th>" . 'Letter Grade' . "</th>";
            }else {
              print "<th>" . $key . "</th>";
            }

          }
          print "<th> </th>";
          print("</tr></thead>\n");
        }
        print("<tr>\n");    // # Start row of HTML table

        foreach ($row as $key => $value) {
          print ("<td>" . $value . "</td>"); //# One item in row


          $A[$counter][$key] = $value;
        }

        $btn ="<td>
        <form method=\"post\" action=\"update_letter_grade.php\">

        <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $cName).">

        <input type=\"hidden\" name=\"cNo\" value=".$cNo.">

        <input type=\"hidden\" name=\"semester\" value=".$smstr.">

        <input type=\"hidden\" name=\"year\" value=".$year.">

        <input type=\"hidden\" name=\"graderID\" value=".$stID.">

        <input type=\"hidden\" name=\"studentID\" value=".$A[$counter]["studentID"].">

        <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

        <input type=\"text\" name=\"letter\">

        <input type=\"submit\" value=\"Update Letter Grade\">
        </form>
        </td>";

        print($btn);

        print ("</tr>\n");   //# End row of HTML table
        $counter = $counter + 1;
      }
      $A;
      $counter = 0;
      $query3 = "SELECT studentID, fName, lName, letter
                FROM course
                JOIN tas USING(cName, cNo, semester, year)
                JOIN takes USING(cName, cNo, semester, year)
                JOIN student USING(studentID)
                WHERE cName = '$cName'
                AND cNo = '$cNo'
                AND semester = '$smstr'
                AND year = '$year'
                AND TAID = '$stID'
                ORDER BY studentID DESC";

      if (!( $result3 = mysqli_query($conn, $query3))){
        printf("Error: %s\n", mysqli_error($conn));
        exit(1);
      }

      while ($row = mysqli_fetch_assoc($result3)) {
        // # print the attribute names once!
        if (!$header) {
          print("<!-- print header once -->");
          $header = true;
          // # specify the header to be dark class
          print("<thead class=\"table-dark\"><tr>\n");
          foreach ($row as $key => $value) {
            if ($key == 'cNo'){
              print "<th>" . 'course number' . "</th>";
            }elseif ($key == 'cName'){
              print "<th>" . 'course name' . "</th>";
            }elseif ($key == 'letter'){
              print "<th>" . 'final grade' . "</th>";
            } else {
              print "<th>" . $key . "</th>";
            }

          }
          print "<th> </th>";
          print("</tr></thead>\n");
        }
        print("<tr>\n");    // # Start row of HTML table

        foreach ($row as $key => $value) {
          print ("<td>" . $value . "</td>"); //# One item in row


          $A[$counter][$key] = $value;
        }

        $btn ="<td>
        <form method=\"post\" action=\"update_letter_grade.php\">

        <input type=\"hidden\" name=\"cName\" value=".str_replace(" ", "_", $cName).">

        <input type=\"hidden\" name=\"cNo\" value=".$cNo.">

        <input type=\"hidden\" name=\"semester\" value=".$smstr.">

        <input type=\"hidden\" name=\"year\" value=".$year.">

        <input type=\"hidden\" name=\"graderID\" value=".$stID.">

        <input type=\"hidden\" name=\"studentID\" value=".$A[$counter]["studentID"].">

        <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

        <input type=\"text\" name=\"letter\">

        <input type=\"submit\" value=\"Update Letter Grade\">
        </form>
        </td>";

        print($btn);

        print ("</tr>\n");   //# End row of HTML table
        $counter = $counter + 1;
      }

      print("</table></div>\n<br>");


      $btn ="<h3>
      <form method=\"post\" action=\"home_page.php\">

      <input type=\"hidden\" name=\"studentID\" value=".$stID.">

      <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

      <input type=\"submit\" value=\"Back to Home Page\">
      </form></h3>";

      print($btn);


      mysqli_free_result($result);
      mysqli_close($conn);


       ?>
    </div>
  </body>
</html>
