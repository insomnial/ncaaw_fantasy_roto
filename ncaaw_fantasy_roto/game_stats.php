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
    <h1>Game Stats</h1>
    </br>
    <?php
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

        // get GET variables
        $team_selected = $_GET['team'];
        $week_selected = $_GET['week'];
        
        // login details
        require_once 'connect.php';

        // preload teams and IDs
        $teams_sql = "SELECT * FROM ncaaw_fantasy_roto.school ORDER BY ncaaw_fantasy_roto.school.name ASC";
        $sql_result = $conn->query($teams_sql);
        if (!$sql_result) die("failed to get teams");

        // parse team rows into an array
        $schools = array('== All =='); // empty 0 index so we use row IDs
        $rows = $sql_result->num_rows; // save the number of events
        for ($i = 0; $i < $rows; ++$i)
        {
          $row = $sql_result->fetch_array(MYSQLI_ASSOC); // convert query to array
          $schools[$row['id']] = $row['name'];
        }

        // add drop down to select teams or all
        echo '<label for="team-list">Team </label>';
        echo '<select name="team-list" id="team-list">';
        foreach ($schools as $key => $value)
        {
          echo '<option value="' . $key . '"' . (($team_selected == $key) ? 'selected' : '' ) . '>' . $value . '</option>';
        }
        echo '</select>';

        echo '&nbsp;&nbsp;&nbsp;&nbsp;';

        // add drop down for weeks
        $weeks = array('== All ==');
        echo '<label for="week-list">Week </label>
          <select name="week-list" id="week-list">
          <option value="0">== All ==</option>';
        for ($i = 1; $i < 29; ++$i)
        {
          echo '<option value="' . $i . '"' . (($week_selected == $i) ? 'selected' : '' ) . '>' . $i . '</option>';
        }
        echo '</select></br></br>';

        // add date picker
        // TO-DO

        // start building table of games
        echo '<table>';
        echo '<tr>
          <th>School</th>
          <th>Win</th>
          <th>Scored</th>
          <th>Allowed</th>
          <th>FGM</th>
          <th>FGA</th>
          <th>Assists</th>
          <th>Rebounds</th>
          <th>Turnovers</th>
          <th>Steals</th>
          <th>Blocks</th>
          <th>Double-Double</th>
          <th>Triple-Double</th>
          <th>Conf PotW</th>
          <th>Conf FotW</th>
          <th>ESPN Top 10</th>
          <th>Week</th>
          <th>Date</th>
          <th>Link</th>
          </tr>';
        
        // get game events
        $select_boundary = '';
        if ($team_selected > 0 or $week_selected > 0)
        {
          $select_boundary = "WHERE ";
          $select_boundary .= ($team_selected > 0) ? 'ncaaw_fantasy_roto.game_stats.school_id = ' . $team_selected : '';  // add team boundary
          $select_boundary .= ($team_selected > 0 and $week_selected > 0) ? ' and ' : '';
          $select_boundary .= ($week_selected > 0) ? 'ncaaw_fantasy_roto.game_stats.week = ' . $week_selected : '';  // add week boundary
        }
        $events_sql = "SELECT * FROM ncaaw_fantasy_roto.game_stats $select_boundary ORDER BY date ASC";
        $events_result = $conn->query($events_sql);
        if (!$events_result) die("failed to get events");

        $rows = $events_result->num_rows; // save the number of events
        
        for ($j = 0; $j < $rows; ++$j)
        {
            $row = $events_result->fetch_array(MYSQLI_ASSOC); // convert query to array
            
            // get each column as an instance variable
            $school_name = $schools[ $row['school_id']]; // offset by one
            $win = ($row['win'] == 1) ? "Yes" : "";
            $points = $row['points'];
            $points_against = $row['points_allowed'];
            $fgm = $row['field_goals_made'];
            $fga = $row['field_goals_attempted'];
            $assists = $row['assists'];
            $rebounds = formatCell($row['rebounds']);
            $turnovers = $row['turnovers'];
            $steals = $row['steals'];
            $blocks = $row['blocks'];
            $double_double = formatCell($row['double_double']);
            $triple_double = formatCell($row['triple_double']);
            $conf_potw = formatCell($row['conference_potw']);
            $conf_fotw = formatCell($row['conference_fotw']);
            $espn_top_ten = formatCell($row['espn_top_ten']);
            $week = $row['week'];
            $date = $row['date'];
            $game_id_url = 'https://stats.ncaa.org/contests/' . $row['game_id'] . '/box_score';
            
            echo '<tr align="center">' . 
              '<td>' . $school_name . '</td>' . 
              '<td>' . $win . '</td>' . 
              '<td>' . $points . '</td>' . 
              '<td>' . $points_against . '</td>' . 
              '<td>' . $fgm . '</td>' . 
              '<td>' . $fga . '</td>' . 
              '<td>' . $assists . '</td>' . 
              '<td>' . $rebounds . '</td>' . 
              '<td>' . $turnovers . '</td>' . 
              '<td>' . $steals . '</td>' . 
              '<td>' . $blocks . '</td>' . 
              '<td>' . $double_double . '</td>' . 
              '<td>' . $triple_double . '</td>' . 
              '<td>' . $conf_potw . '</td>' . 
              '<td>' . $conf_fotw . '</td>' . 
              '<td>' . $espn_top_ten . '</td>' . 
              '<td>' . $week . '</td>' . 
              '<td>' . $date . '</td>' . 
              '<td><a href="' . $game_id_url . '" target="_blank" rel="noopener">URL</a></td>' . 
              '</tr>';
        }
        echo '</table><br><br>';
        mysqli_close($conn);
    ?>

    <!-- Update page based on drop-down boxes -->
    <script>
    document.getElementById("team-list").addEventListener("change", reload_page);
    document.getElementById("week-list").addEventListener("change", reload_page);
    function reload_page() {
      team = document.getElementById("team-list").value;
      week = document.getElementById("week-list").value;
      window.location.replace("https://bjorna-3.net/ncaaw_fantasy_roto/game_stats.php?team=" + team + "&week=" + week);
    }
    </script>

  </body>
</html>
