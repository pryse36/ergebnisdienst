<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Ergebnisparser DJK </title>
    </head>
    <body>
        <?php
        include('simple_html_dom.php');
        $html = new simple_html_dom();
        $html->load_file("https://www.fupa.net/club/djk-heuweiler/team/m1");

// Anpassen wenn Produktiv genommen:
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "djk_ergebnisdienst";
// Fehlermeldung ausgeben falls vergessen
        if ($servername == 'localhost') {
            echo "Achtung: DB Servername noch auf localhost" . "<br>";
        }
        $team_name = $html->find("td[class=tab_team_name]");
        $spiele = $html->find("td[class=tabelle_nummer tab_games]");
        $punkte = $html->find("td[class=tabelle_nummer tab_points]");
        $diff = $html->find("td[class=tabelle_nummer tab_diff]");

        if ($team_name == null || $spiele == null || $punkte == null) {
            echo "fehler";
            exit;
        }

// die DB Verbindung aufbauen
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset('utf8');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            exit;
        }
        echo "Connected successfully" . "<br><br>";
        $result = $conn->query("SELECT * FROM liga_tabelle");
        $num_rows = $result->num_rows;
        // Wenn keine Datensätze vorhanden sind, dann führe ein INSERT durch
        // SONST ein UPDATE
        if ($num_rows <= 1) {//INSERT
            for ($i = 0; $i < sizeof($team_name); $i++) {
                $counter = $i + 1;
                $text_team = $team_name[$i]->plaintext;
                $text_punkte = $punkte[$i]->plaintext;
                $text_spiele = $spiele[$i]->plaintext;
                $text_diff = $diff[$i]->plaintext;
                $sqlquery = ("INSERT INTO liga_tabelle(Platz,Name,Logo,Punkte,Spiele,Differenz)
                   values('$counter','$text_team','','$text_punkte','$text_spiele','$text_diff')");
                if ($conn->query($sqlquery) === TRUE) {
                    $counter++;
                } else {
                    ECHO "FEHLER DB Operation" . "<br>";
                    echo $conn->error;
                    exit;
                }
            }
        } else {// UPDATE          
            for ($i = 0; $i < sizeof($team_name); $i++) {
                $counter = $i + 1;
                $text_team = $team_name[$i]->plaintext;
                $text_punkte = $punkte[$i]->plaintext;
                $text_spiele = $spiele[$i]->plaintext;
                $text_diff = $diff[$i]->plaintext;
                $sqlquery = "UPDATE liga_tabelle SET Platz=$counter, Name='$text_team',  Punkte=$text_punkte,
                     Spiele=$text_spiele,  Differenz=$text_diff where Platz = $counter ";
                if ($conn->query($sqlquery) === TRUE) {
                    $counter++;
                } else {
                    ECHO "FEHLER DB Operation" . "<br>";
                    echo $conn->error;
                    exit;
                }
            }
            echo "Update durchgeführt";
        }
        ?>
    </body>
</html>
