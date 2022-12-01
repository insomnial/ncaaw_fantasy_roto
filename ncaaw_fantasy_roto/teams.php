<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Marbles 2020!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" type = "text/css" href = "default.css">
    <style>
        th, td {
            padding: 15px;
        }
    </style>
  </head>
  <body>
    <?php include 'countdown.php' ?>
    <?php include 'header.php' ?>
    <script type="text/javascript" src="countdown.js"></script>
    
    <h1>Teams</h1>    
    <?php
        function mysql_fatal_error()
        {
            echo "No connection. Rip.<br>";
        };

        // login details
        require_once 'connect.php';
        
        $teams_sql = "SELECT * FROM teams ORDER BY team_name";
        $teams_result = $conn->query($teams_sql);
        if (!$teams_result) die("failed to get user list");
        
        $rows = $teams_result->num_rows; // save the number of rows to loop
        
        // start to build the table
        echo '<table>';
        echo '<tr><th>Team Name</th><th>Method of Qualification</th><th>Date of Qualification</th><th>Marble League Appearances</th></tr>';
        
        for ($j = 0; $j < $rows; ++$j)
        {
            $row = $teams_result->fetch_array(MYSQLI_ASSOC);
            
            echo '<tr><td>' . $row['team_name'] . '</td>';
            echo '<td>' . $row['method_of_qualification'] . '</td>';
            echo '<td style="text-align: center">' . $row['date_of_qualification'] . '</td>';
            echo '<td style="text-align: center">' . $row['marble_league_appearance'] . '</td></tr>';
            
        }
        
        echo '</table>';
                
        mysqli_close($conn);
    
    ?>

  </body>
</html>
