<!DOCTYPE html>

<html>
  <head>
    <title>My Canvas — Home Page</title>
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
      if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit(1);
      }

      // # get the query that was posted
      $stID = $_POST["studentID"];
      $logID = $_POST["loginID"];
      // print("</code></pre>");
      // # execute the query
      $auth = "SELECT * FROM student WHERE studentID='$stID' AND loginID = '$logID'";

      if (!( $result = mysqli_query($conn, $auth))){
        printf("Error: %s\n", mysqli_error($conn));
        exit(1);
      }
      // print(gettype($result));
      // $row = mysqli_fetch_assoc($result);
      if (!(mysqli_num_rows($result) > 0)) {
        print("
        <h4>Person not found. Make sure your studentID and loginID are corect</h4>
        <form action=\"login.html\" method=\"POST\">
           <input type=\"submit\" value=\"Go back to login page\">
        </form>
        ");
      } else {

        $query = "SELECT cNo, cName, semester, year, letter
                  FROM course
                  JOIN takes USING(cNo, cName, semester, year)
                  JOIN student USING(studentID)
                  WHERE studentID = '$stID'
                  ORDER BY semester DESC, year DESC";

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
          if (!$header) {
            print("<!-- print header once -->");
            $header = true;
            print("<h3>Classes Taken</h3>");
            // # specify the header to be dark class
            print("<thead class=\"table-dark\"><tr>\n");
            foreach ($row as $key => $value) {
              if ($key == 'cNo'){
                print "<th>" . 'Course Number' . "</th>";
              }elseif ($key == 'cName'){
                print "<th>" . 'Course Name' . "</th>";
              }elseif ($key == 'letter'){
                print "<th>" . 'Final Grade' . "</th>";
              }elseif ($key == 'semester'){
                print "<th>" . 'Semester' . "</th>";
              }elseif ($key == 'year'){
                print "<th>" . 'Year' . "</th>";
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

          $stID = $_POST['studentID'];
          $smstr = $A[$counter]['semester'];
          $year = $A[$counter]['year'];
          $cNo = $A[$counter]['cNo'];
          $cName = str_replace(" ", "_",$A[$counter]['cName']);

          $btn ="<td>
          <form method=\"post\" action=\"get_class.php\">

          <input type=\"hidden\" name=\"cName\" value=".$cName.">

          <input type=\"hidden\" name=\"cNo\" value=".$cNo.">

          <input type=\"hidden\" name=\"semester\" value=".$smstr.">

          <input type=\"hidden\" name=\"year\" value=".$year.">

          <input type=\"hidden\" name=\"studentID\" value=".$stID.">

          <input type=\"hidden\" name=\"loginID\" value=".$logID.">

          <input type=\"submit\" value=\"Check Class\">
          </form>
          </td>";

          print($btn);
          print ("</tr>\n");   //# End row of HTML table



          $counter = $counter + 1;
        }
        print("</table></div>\n");



        $query1 = "SELECT cNo, cName, semester, year
                  FROM course
                  WHERE instructorID = '$stID'
                  ORDER BY semester DESC, year DESC";

        if (!( $result1 = mysqli_query($conn, $query1))){
          printf("Error: %s\n", mysqli_error($conn));
          exit(1);
        }
        print("<div><table class=\"table table-striped\"\n");
        $header = false;
        $A;
        $counter = 0;
        while ($row = mysqli_fetch_assoc($result1)) {
          // # print the attribute names once!
          if (!$header) {
            print("<!-- print header once -->");
            $header = true;
            print("<br><h3>Classes Taught</h3>");
            // # specify the header to be dark class
            print("<thead class=\"table-dark\"><tr>\n");
            foreach ($row as $key => $value) {
              if ($key == 'cNo'){
                print "<th>" . 'Course Number' . "</th>";
              }elseif ($key == 'cName'){
                print "<th>" . 'Course Name' . "</th>";
              }elseif ($key == 'letter'){
                print "<th>" . 'Final Grade' . "</th>";
              }elseif ($key == 'semester'){
                print "<th>" . 'Semester' . "</th>";
              }elseif ($key == 'year'){
                print "<th>" . 'Year' . "</th>";
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

          $bstID = $_POST['studentID'];
          $bsmstr = $A[$counter]['semester'];
          $byear = $A[$counter]['year'];
          $bcNo = $A[$counter]['cNo'];
          $bcName = str_replace(" ", "_",$A[$counter]['cName']);


          $btn ="<td>
          <form method=\"post\" action=\"class_page.php\">

          <input type=\"hidden\" name=\"cName\" value=".$bcName.">

          <input type=\"hidden\" name=\"cNo\" value=".$bcNo.">

          <input type=\"hidden\" name=\"semester\" value=".$bsmstr.">

          <input type=\"hidden\" name=\"year\" value=".$byear.">

          <input type=\"hidden\" name=\"studentID\" value=".$bstID.">

          <input type=\"hidden\" name=\"loginID\" value=".$logID.">

          <input type=\"submit\" value=\"Check Class\">
          </form>
          </td>";

          print($btn);
          print ("</tr>\n");   //# End row of HTML table
          $counter = $counter + 1;
        }


        $query2 = "SELECT cNo, cName, semester, year
                  FROM tas
                  WHERE TAID = '$stID'
                  ORDER BY semester DESC, year DESC";

        if (!( $result2 = mysqli_query($conn, $query2))){
          printf("Error: %s\n", mysqli_error($conn));
          exit(1);
        }

        $A;
        $counter = 0;
        while ($row = mysqli_fetch_assoc($result2)) {
          // # print the attribute names once!
          if (!$header) {
            print("<!-- print header once -->");
            $header = true;
            // # specify the header to be dark class
            print("<thead class=\"table-dark\"><tr>\n");
            foreach ($row as $key => $value) {
              if ($key == 'cNo'){
                print "<th>" . 'Course Number' . "</th>";
              }elseif ($key == 'cName'){
                print "<th>" . 'Course Name' . "</th>";
              }elseif ($key == 'letter'){
                print "<th>" . 'Final Grade' . "</th>";
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

          $bstID = $_POST['studentID'];
          $bsmstr = $A[$counter]['semester'];
          $byear = $A[$counter]['year'];
          $bcNo = $A[$counter]['cNo'];
          $bcName = str_replace(" ", "_",$A[$counter]['cName']);


          $btn ="<td>
          <form method=\"post\" action=\"class_page.php\">

          <input type=\"hidden\" name=\"cName\" value=".$bcName.">

          <input type=\"hidden\" name=\"cNo\" value=".$bcNo.">

          <input type=\"hidden\" name=\"semester\" value=".$bsmstr.">

          <input type=\"hidden\" name=\"year\" value=".$byear.">

          <input type=\"hidden\" name=\"studentID\" value=".$bstID.">

          <input type=\"hidden\" name=\"loginID\" value=".$logID.">

          <input type=\"submit\" value=\"Check Class\">
          </form>
          </td>";

          print($btn);
          print ("</tr>\n");   //# End row of HTML table
          $counter = $counter + 1;
        }



        print("</table></div>\n");

        $btn ="<form style=\"margin:25px;\" method=\"post\" action=\"login.html\">
              <input type=\"submit\" value=\"Log Out\">
              </form>";
        print("<div align=\"right\";>".$btn."</div>");
      }



      mysqli_free_result($result);
      mysqli_close($conn);
      ?>

    </div>
  </body>
</html>
