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
    <h1>Fantasy Teams</h1>
    
    <?php
        function mysql_fatal_error()
        {
            echo "No connection. Rip.<br>";
        };

        // login details
        require_once 'connect.php';
        
        $fantasy_teams = "SELECT * FROM users ORDER BY user_name";
        $result = $conn->query($fantasy_teams);
        if (!$result) die("failed to get user list");
        
        $rows = $result->num_rows; // save the number of rows to loop
        
        for ($j = 0; $j < $rows; ++$j)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            $owner_id = htmlspecialchars($row['id']);
            $fantasy_team_name = htmlspecialchars($row['team_name']);
            // print the team name
            echo '<h3>' . $fantasy_team_name . '</h3>';
            
            // get the draft picks
            $draft_picks_sql = "SELECT id, team_name FROM teams WHERE id IN (SELECT team_id FROM `fantasy_teams` WHERE owner_id = " . $owner_id . ")";
            $result_draft_picks = $conn->query($draft_picks_sql);
            if (!$result) die("failed to get draft picks");
            
            $draft_rows = $result_draft_picks->num_rows;
            
            echo '<table><tr><th>Pick #</th><th>Team Name</th><th>Qualifier Rank</th></tr>';
            
            for ($k = 0; $k < $draft_rows; ++$k)
            {
                $row_inner = $result_draft_picks->fetch_array(MYSQLI_ASSOC);
                echo '<tr><td style="text-align: center">' . ($k + 1) . '</td>' . 
                    '<td>' . $row_inner['team_name'] . '</td>' . 
                    '<td style="text-align: center">' . $row_inner['id'] . '</td></tr>';
            }
            
            echo '</table><br><br>';
        }
                
        mysqli_close($conn);
    
    ?>

  </body>
</html>
