<?php

define('BASEDIR', dirname(__FILE__));
require_once('database.php');
require_once('get_table.php');
require_once('simple_html_dom.php');

if (!isset($_GET['action']))
    die('No action defined.');
else
    $action = $_GET['action'];
switch ($action) {
    case 'parse':
        echo "Updating league table...<br>\n";
        $tabelle = get_table::GetTable('https://www.fupa.net/club/djk-heuweiler/team/m1');
        foreach ($tabelle as $row) {
            $insert = $mysqli->query("INSERT INTO liga_tabelle(url, name, logo, punkte, spiele, differenz, performance) values ('" . $row['team_url'] . "', '" . $row['team_name'] . "', '" . $row['team_logo'] . "', " . $row['points'] . ", " . $row['games'] . ", " . $row['diff'] . ", " . $row['performance'] . ")");
            if (!$insert) { //No row was affected (probably duplicate unique) -> Update
                $update = $mysqli->query("UPDATE liga_tabelle SET name='" . $row['team_name'] . "', logo='" . $row['team_logo'] . "', punkte=" . $row['points'] . ", spiele=" . $row['games'] . ", differenz=" . $row['diff'] . ", performance=" . $row['performance'] . " where url = '" . $row['team_url'] . "'");
                if (!$update) {
                    echo "Es ist ein Fehler aufgetreten (Team: " . $row['team_name'] . ")<br>\n";
                } elseif (!mysqli_affected_rows($mysqli)) {
                    echo " Untouched " . $row['team_name'] . "<br>\n";
                } else {
                    echo " Updated " . $row['team_name'] . "<br>\n";
                }
            } else {
                echo " Inserted " . $row['team_name'] . "<br>\n";
            }
        }
        echo "Done.<br>\n";
        // NEU
        echo "Updating Results...<br>\n";
        $results = get_table::GetResults('https://www.fupa.net/liga/freiburg-kreisliga-b2/spielplan');
        foreach ($results as $v => $row) {

            $insert = $mysqli->query("INSERT INTO ergebnisse(datum, ergebnis_heim, ergebnis_gast,liga,saison,spieltag, team_gast,team_heim) "
                    . "values ('" . $row['datum'] . "', '" . $row['ergebnis_heim'] . "', '" . $row['ergebnis_gast'] . "', '" . $row['liga'] . "', '" . $row['saison'] . "', '" . $row['spieltag'] . "', '" . $row['team_gast'] . "', '" . $row['team_heim'] . "')");
            if (!$insert) { //No row was affected (probably duplicate unique) -> Update
                $update = $mysqli->query("UPDATE ergebnisse SET ergebnis_heim='" . $row['ergebnis_heim'] . "', ergebnis_gast='" . $row['ergebnis_gast'] . "' " . " where spieltag = '" . $row['spieltag'] . "' and team_heim='" . $row['team_heim'] . "' and  saison= '" . $row['saison'] . "' ");

                if (!$update) {
                    echo "Es ist ein Fehler aufgetreten (SP: " . $row['spieltag'] . ")<br>\n";
                } elseif (!mysqli_affected_rows($mysqli)) {
                    echo " Untouched " . $row['spieltag'] . "<br>\n";
                } else {
                    echo " Updated " . $row['spieltag'] . "<br>\n";
                }
            } else {
                echo " Inserted " . $row['spieltag'] . "<br>\n";
            }
        }

        echo "Done.<br>\n";
        break;

    case 'get_table':
        $json = (object) ['timestamp' => time()];
        $result = $mysqli->query("SELECT * FROM liga_tabelle ORDER BY Punkte DESC");
        if ($result && $result->num_rows > 0) {
            $json->success = true;
            $json->table = array();
        } else {
            $json->success = false;
        }

        while ($row = $result->fetch_assoc()) {
            array_push($json->table, $row);
        }

        header('Content-Type: application/json');
        echo json_encode($json);
        break;

    case 'get_result':
        $spieltag = $_GET['spieltag'];
        $json = (object) ['timestamp' => time()];

        $result = $mysqli->query("SELECT * FROM ergebnisse where spieltag = '" . $spieltag . "' ORDER BY spieltag DESC");
        if ($result && $result->num_rows > 0) {
            $json->success = true;
            $json->table = array();
        } else {
            $json->success = false;
        }
        while ($row = $result->fetch_assoc()) {
            array_push($json->table, $row);
        }
        header('Content-Type: application/json');
        echo json_encode($json);
        break;
}


