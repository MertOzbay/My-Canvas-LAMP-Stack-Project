<!DOCTYPE html>

<html>
  <head>
    <title>Course Page — Student</title>
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
      $conn = mysqli_connect("localhost",
                             "cs377", "ma9BcF@Y", "my_canvas");
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
      print("<h3>Grades for ".$cNo.": ".$cName."<h3>");

      $query = "SELECT assignment_text, dueDate, score, totalPoints
                FROM assignment
                JOIN grades USING(cNo, cName, semester, year, dueDate)
                WHERE studentID = '$stID'
                AND cNo = '$cNo'
                AND cName = '$cName'
                AND semester = '$smstr'
                AND year = '$year'
                ORDER BY dueDate DESC";

      if (!( $result = mysqli_query($conn, $query))){
        printf("Error: %s\n", mysqli_error($conn));
        exit(1);
      }
      print("<div><table class=\"table table-striped\"\n");
      $header = false;


      while ($row = mysqli_fetch_assoc($result)) {
        // # print the attribute names once!
        if (!$header) {
          print("<!-- print header once -->");
          $header = true;
          // # specify the header to be dark class
          print("<thead class=\"table-dark\"><tr>\n");
          foreach ($row as $key => $value) {
            if ($key == 'assignment_text'){
              print "<th>" . 'Description' . "</th>";
            }elseif ($key == 'dueDate'){
              print "<th>" . 'Due Date' . "</th>";
            }elseif ($key == 'totalPoints'){
              print "<th>" . 'Out of' . "</th>";
            }elseif ($key == 'score'){
              print "<th>" . 'Score' . "</th>";
            } else {
              print "<th>" . $key . "</th>";
            }

          }
          print("</tr></thead>\n");
        }
        print("<tr>\n");    // # Start row of HTML table

        foreach ($row as $key => $value) {
          print ("<td>" . $value . "</td>"); //# One item in row

        }


        print ("</tr>\n");   //# End row of HTML table
      }
      print("</table></div>\n");

      $btn ="
      <form method=\"post\" action=\"home_page.php\">

      <input type=\"hidden\" name=\"studentID\" value=".$stID.">

      <input type=\"hidden\" name=\"loginID\" value=".$_POST["loginID"].">

      <input type=\"submit\" value=\"Back to Home Page\">
      </form>";

      print($btn);

      mysqli_free_result($result);
      mysqli_close($conn);


      ?>

    </div>
  </body>
</html>
