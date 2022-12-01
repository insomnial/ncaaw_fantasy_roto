<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ncaaw-hoopz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" type = "text/css" href = "table_rank.css">
    <link rel = "stylesheet" type = "text/css" href = "default.css">
    <style>
    </style>
  </head>
  <body>
    <?php include 'header.php' ?>
    <h1>Upcoming Games</h1>
    </br>
    <?php
        // login details
        require_once 'connect.php';
        
        // get schedule view
        $schedule_sql = "SELECT * FROM ncaaw_fantasy_roto.upcoming_three_days;";
        $events_result = $conn->query($schedule_sql);
        if (!$events_result) die("failed to get events");

        // start building table of teams
        echo '<table>';
        echo '<tr>
          <th>SCHOOL</th>
          <th>DATE</th>
          <th></th>
          <th>OPPONENT</th>
          <th>NOTES</th>
          </tr>';

        $rows = $events_result->num_rows; // save the number of events
        
        for ($j = 0; $j < $rows; ++$j)
        {
            $row = $events_result->fetch_array(MYSQLI_ASSOC); // convert query to array
            
            // get each column as an instance variable
            $school_name = $row['school_name'];
            $game_date = $row['date'];
            $opponent = $row['opponent'];
            $home_game = ($row['home'] == 1) ? "vs" : "at";
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

        echo '</table><br><br>';
    
    ?>

  </body>
</html>
