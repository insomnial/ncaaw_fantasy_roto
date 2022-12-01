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
    <h1>Weekly Stats</h1>
    </br>
    <?php
        $weekSelected = $_GET["week"];
        function formatCell($input) 
        {
          if ($input == 0) 
          {
            return '';
          }
          else
          {
            return $input;
          }
        }

        echo '<h3>Week ' . $weekSelected . '</h3>'; 

        // set weeks
        $weeks = array(
          'week_zero',
          'week_one',
          'week_two',
          'week_three',
          'week_four',
          'week_five',
          'week_six',
          'week_seven',
          'week_eight',
          'week_nine',
          'week_ten',
          'week_eleven',
          'week_twelve',
          'week_thirteen',
          'week_fourteen',
          'week_fifteen',
          'week_sixteen',
          'week_seventeen',
          'week_eighteen',
          'week_nineteen',
          'week_twenty',
          'week_twentyone',
          'week_twentytwo',
          'week_twentythree',
          'week_twentyfour',
          'week_twentyfive',
          'week_twentysix',
          'week_twentyseven',
          'week_twentyeight'
        );

        // login details
        require_once 'connect.php';

        // load season view
        $week_sql = "SELECT * FROM ncaaw_fantasy_roto." . $weeks[$weekSelected] . " ORDER BY ncaaw_fantasy_roto." . $weeks[$_GET["week"]] . ".total DESC";
        $sql_result = $conn->query($week_sql);
        if (!$sql_result) die("failed to get week records");

        // start building table of teams
        echo '<table>';
        echo '<tr>
          <th>Manager</th>
          <th>School</th>
          <th>Wins</th>
          <th>Losses</th>
          <th>FGP</th>
          <th>Plus/Minus</th>
          <th>Rebounds</th>
          <th>Assists</th>
          <th>Blocks</th>
          <th>Steals</th>
          <th>DD</th>
          <th>TD</th>
          <th>Conf PotW</th>
          <th>Conf FotW</th>
          <th>ESPN Top 10</th>
          <th>Total</th>
          </tr>';
        
        $rows = $sql_result->num_rows; // save the number of events
        
        for ($j = 0; $j < $rows; ++$j)
        {
            $row = $sql_result->fetch_array(MYSQLI_ASSOC); // convert query to array
            
            // get each column as an instance variable
            $manaager = $row['manager']; // offset by one
            $school_name = $row['school_name'];
            $win = $row['wins'];
            $losses = $row['losses'];
            $fgp = number_format($row['field_goal_percent'], 1);
            $plus_minus = formatCell($row['plus_minus']);
            $rebounds = $row['rebounds'];
            $assists = $row['assists'];
            $blocks = $row['blocks'];
            $steals = $row['steals'];
            $double_double = formatCell($row['dd']);
            $triple_double = formatCell($row['td']);
            $conf_potw = formatCell($row['conf_potw']);
            $conf_fotw = formatCell($row['conf_fotw']);
            $espn_top_ten = formatCell($row['espn_top_ten']);
            $total = number_format($row['total'], 2);
  
            echo '<tr align="center">' . 
              '<td>' . $manaager . '</td>' . 
              '<td>' . $school_name . '</td>' . 
              '<td>' . $win . '</td>' . 
              '<td>' . $losses . '</td>' . 
              '<td>' . $fgp . '</td>' . 
              '<td>' . $plus_minus . '</td>' . 
              '<td>' . $rebounds . '</td>' . 
              '<td>' . $assists . '</td>' . 
              '<td>' . $blocks . '</td>' . 
              '<td>' . $steals . '</td>' . 
              '<td>' . $double_double . '</td>' . 
              '<td>' . $triple_double . '</td>' . 
              '<td>' . $conf_potw . '</td>' .
              '<td>' . $conf_fotw . '</td>' . 
              '<td>' . $espn_top_ten . '</td>' . 
              '<td>' . $total . '</td>' . 
              '</tr>';
        }
                
        mysqli_close($conn);

        echo '</table><br><br>';
    
    ?>

  </body>
</html>
