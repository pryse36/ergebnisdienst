<?php

defined('BASEDIR') OR die('No direct access allowed.');

class get_table {

    public static function GetTable($url) {
        require_once(BASEDIR . '/simple_html_dom.php');
        $html = new simple_html_dom();
        $html->load_file($url);

        $table = array();
        foreach ($html->find("table[class=liga_tabelle] tr[class^=tabelle_farbe]") as $row) {
            $table[] = array(
                'team_name' => $row->find("td[class=tab_team_name] a", 0)->plaintext,
                'team_url' => $row->find("td[class=tab_team_name] a", 0)->href,
                'team_logo' => self::ImageToBase64($row->find("td[class=tab_wappen] img", 0)->src),
                'performance' => $row->find("td[class=tab_aufab]", 0)->title,
                'games' => $row->find("td[class=tab_games]", 0)->plaintext,
                'points' => $row->find("td[class=tab_points]", 0)->plaintext,
                'diff' => $row->find("td[class=tab_diff]", 0)->plaintext,
            );
        }
        return $table;
    }

    // Ergebnisse holen
    public static function GetResults($url) {
        require_once(BASEDIR . '/simple_html_dom.php');
        $html_results = new simple_html_dom();
        $html_results->load_file($url);
        $datum = "";
        $spieltag = "";
        $heim = "";
        $gast = "";
        $erg_heim = "";
        $erg_gast = "";
        $results = array();
        foreach ($html_results->find("table[class=content_table_std]") as $table) {
//  Spieltag holen
            $tmp_sp = $table->find("th", 0)->plaintext;
        preg_match("/(\\d+)/",$tmp_sp,$matches);
        $spieltag = $matches[0];
            foreach ($table->find("td[class=liga_spielplan_container] ") as $row) {
                $heim = $row->find("div[class=liga_spieltag_vorschau_heim_content]", 0)->plaintext;
                $gast = $row->find("div[class=liga_spieltag_vorschau_gast_content]", 0)->plaintext;
                if ($row->find("div[class=liga_spieltag_vorschau_datum_content]", 0) != null) {
                    $datum = $row->find("div[class=liga_spieltag_vorschau_datum_content]", 0)->plaintext;
                    $erg_heim = "";
                    $erg_gast = "";
                } else {
                    $erg_heim = $row->find("span[class=liga_spieltag_vorschau_datum_content_ergebnis_heim]", 0)->plaintext;
                    $erg_gast = $row->find("span[class=liga_spieltag_vorschau_datum_content_ergebnis_gast]", 0)->plaintext;
                }

                $results[] = array(
                    'liga' => "Kreisliga B2",
                    'saison' => "2018/19",
                    'spieltag' => $spieltag,
                    'datum' => $datum,
                    'team_heim' => $heim,
                    'team_gast' => $gast,
                    'ergebnis_heim' => $erg_heim,
                    'ergebnis_gast' => $erg_gast,
                );
            }
        }


        return $results;
    }

    private static function ImageToBase64($url) {
        //   $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($url);
        //   $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);    
        $base64 = base64_encode($data);
        return $base64;
    }

}
