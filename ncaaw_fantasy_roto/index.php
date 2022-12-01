<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ncaaw-hoopz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" type = "text/css" href = "default.css">
    <style>
    </style>
  </head>
  <body>
    <?php include 'header.php' ?>

    <!-- today's games -->
    <?php
      // login details
      require_once 'connect.php';

      // load today's games
      $today_sql = "SELECT * FROM ncaaw_fantasy_roto.upcoming_today";
      $sql_result = $conn->query($today_sql);
      if (!$sql_result) die("failed to get today's games");

        // start building table of teams
        echo '<h1></h1><table>';
        echo '<tr>
          <th>School</th>
          <th>Date</th>
          <th></th>
          <th>Opponent</th>
          <th>Notes</th>
          </tr>';

        $rows = $sql_result->num_rows; // save the number of events
        
        for ($j = 0; $j < $rows; ++$j)
        {
            $row = $sql_result->fetch_array(MYSQLI_ASSOC); // convert query to array
            
            // get each column as an instance variable
            $school_name = $row['school_name'];
            $game_date = $row['date'];
            $opponent = $row['opponent'];
            $home_game = ($row['home'] == 1) ? "vs" : "@";
            $notes = $row['notes'];
            $bold = $row['opponent_school_id'];
            
            echo '<tr align="center"' . (($bold > 0) ? ' style="font-weight:bold;" ' : '') . '>' . 
              '<td>' . $school_name . '</td>' . 
              '<td>' . $game_date . '</td>' . 
              '<td>' . $home_game . '</td>' . 
              '<td>' . $opponent . '</td>' . 
              '<td>' . $notes . '</td>' . 
              '</tr>';
        }
                
        mysqli_close($conn);

        echo '</table>';
    
    ?>

    <pre style='font-family:monospace; font-size:12pt'>
      <ul>Tables
        <li><a href="upcoming_games.php">Upcoming games</a></li>
        <li><a href="game_stats.php?team=0&week=0">All Game Stats</a></li>
        <li><a href="week_stats.php?team=0&week=2">Current Week</a></li>
      </ul>
    </pre>
    
  </body>
</html>
