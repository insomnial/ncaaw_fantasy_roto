<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Marbles 2020!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" type = "text/css" href = "table_rank.css">
    <link rel = "stylesheet" type = "text/css" href = "default.css">
    <style>
        #season_points {
            width: 50%;
            float: left;
        }
        #graph {
            width: 50%;
            float: left;
        }
    </style>
  </head>
  <body>
    <?php include 'countdown.php' ?>
    <?php include 'header.php' ?>
    <script type="text/javascript" src="countdown.js"></script>
    <h1>Fantasy Team Season Standings</h1>
    <div id="container">
    <div id="season_standings">
        <?php
            function mysql_fatal_error()
            {
                echo "No connection. Rip.<br>";
            };

            // login details
            require_once 'connect.php';
            
            $fantasy_standings_sql = "SELECT users.id, users.team_name, (SELECT SUM(event_results.points) as season_points FROM event_results WHERE event_results.team_id IN (SELECT fantasy_teams.team_id FROM fantasy_teams WHERE fantasy_teams.owner_id = users.id)) AS season_points FROM users WHERE 1 ORDER BY season_points DESC";
            $fantasy_standings_result = $conn->query($fantasy_standings_sql);
            if (!$fantasy_standings_result) die("failed to get user list");
            
            $rows = $fantasy_standings_result->num_rows; // save the number of rows to loop
            
            // start to build the table
            echo '<table>';
            echo '<tr><th>Team Name</th><th>Season Points</th></tr>';
            
            for ($j = 0; $j < $rows; ++$j)
            {
                $row = $fantasy_standings_result->fetch_array(MYSQLI_ASSOC);
                
                echo '<tr id="rank' . ($j + 1) . '"><td>' . $row['team_name'] . '</td>';
                echo '<td style="text-align: center">' . $row['season_points'] . '</td></tr>';
                
            }
            
            echo '</table>';
                    
            mysqli_close($conn);
    ?>
    </div> <!-- season_standings -->
    <div id="graph">
    </div> <!-- graph -->
    </div> <!-- container -->
  </body>
</html>
