<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Marbles 2020!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" type = "text/css" href = "table_rank.css">
    <link rel = "stylesheet" type = "text/css" href = "default.css">
    <style>
    </style>
  </head>
  <body>
    <?php include 'countdown.php' ?>
    <?php include 'header.php' ?>
    <script type="text/javascript" src="countdown.js"></script>
    <h1>Team Season Standings</h1>
    
    <?php
        function mysql_fatal_error()
        {
            echo "No connection. Rip.<br>";
        };

        // login details
        require_once 'connect.php';
        
        $standings_sql = "SELECT team_name, (SELECT SUM(event_results.points) FROM event_results WHERE event_results.team_id = teams.id) as season_points FROM teams ORDER BY season_points DESC";
        $standings_result = $conn->query($standings_sql);
        if (!$standings_result) die("failed to get user list");
        
        $rows = $standings_result->num_rows; // save the number of rows to loop
        
        // start to build the table
        echo '<table>';
        echo '<tr><th>Team Name</th><th>Season Points</th></tr>';
        
        for ($j = 0; $j < $rows; ++$j)
        {
            $row = $standings_result->fetch_array(MYSQLI_ASSOC);
            
            echo '<tr id="rank' . ($j + 1) . '"><td>' . $row['team_name'] . '</td>';
            echo '<td style="text-align: center">' . $row['season_points'] . '</td></tr>';
            
        }
        
        echo '</table>';
                
        mysqli_close($conn);
    
    ?>

  </body>
</html>
