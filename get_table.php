<?php defined('BASEDIR') OR die('No direct access allowed.');

class get_table {
  public static function GetTable($url) {
    require_once(BASEDIR.'/simple_html_dom.php');
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
  
  private static function ImageToBase64($url) {
 //   $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($url);
 //   $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);    
     $base64 =  base64_encode($data);    
    return $base64;
  }
}