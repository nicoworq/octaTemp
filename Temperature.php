<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include_once 'Database.php';

class Temperature {

    private $db;
    private $mysqli;
    private $tableName = "temp";
    private $tableNameOutside = "temp_externa";

    public function __construct() {
        $this->db = Database::getInstance();
        $this->mysqli = $this->db->getConnection();
    }

    public function storeTemperature($temperature) {

        $sqlInsert = "INSERT INTO {$this->tableName} (temp, fecha) VALUES ({$temperature}, NOW())";

        $this->mysqli->query($sqlInsert);

        return $this->mysqli->affected_rows;
    }
    public function storeOutsideTemperature($temperature) {

        $sqlInsert = "INSERT INTO {$this->tableNameOutside} (temp, fecha) VALUES ({$temperature}, NOW())";
        
        $this->mysqli->query($sqlInsert);

        return $this->mysqli->affected_rows;
    }


    
    public function getTemperatures() {

        $sqlInsert = "SELECT * FROM {$this->tableName}";

        $tempQuery = $this->mysqli->query($sqlInsert);

        $temperaturas = array();
        while ($mostrar = mysqli_fetch_array($tempQuery)) {
            $temperaturas[] = array('id' => $mostrar['id'], 'temp' => $mostrar['temp'], 'fecha' => $mostrar['fecha']);
        }
        return $temperaturas;
    }

    public function getTemperaturesForChart24hs() {

        $sqlInsert = "SELECT * FROM {$this->tableName} WHERE fecha  BETWEEN CURDATE() - INTERVAL 1 DAY AND NOW();";

        $tempQuery = $this->mysqli->query($sqlInsert);



        $temperaturas = array();
        $fechas = array();

        while ($mostrar = mysqli_fetch_array($tempQuery)) {
            $temperaturas[] = $mostrar['temp'];
            $fecha = date_create_from_format("Y-m-d H:i:s", $mostrar['fecha'])->sub(new DateInterval("PT3H"));
            $fechas[] = $fecha->format("H:i");
        }
        return array("temperaturas" => $temperaturas, "fechas" => $fechas);
    }

    public function getTemperaturesOutsideForChart24hs() {

        $sqlInsert = "SELECT * FROM {$this->tableNameOutside} WHERE fecha  BETWEEN CURDATE() - INTERVAL 1 DAY AND NOW();";

        $tempQuery = $this->mysqli->query($sqlInsert);

        $temperaturas = array();
        $fechas = array();

        while ($mostrar = mysqli_fetch_array($tempQuery)) {
            $temperaturas[] = $mostrar['temp'];
            $fecha = date_create_from_format("Y-m-d H:i:s", $mostrar['fecha'])->sub(new DateInterval("PT3H"));
            $fechas[] = $fecha->format("H:i");
        }
        return array("temperaturas" => $temperaturas, "fechas" => $fechas);
    }

    public function getActualTemperature() {

        $sql = "SELECT * FROM {$this->tableName} ORDER BY fecha DESC LIMIT 1";

        $tempQuery = $this->mysqli->query($sql);

        $temperatura = array();

        while ($mostrar = mysqli_fetch_array($tempQuery)) {

            $temperatura = array("temperatura" => $mostrar['temp'], "fecha" => $this->time_elapsed_string($mostrar['fecha'] ) );
        }
        return $temperatura;
    }

    public function getActualOutsideTemperature() {

        $sql = "SELECT * FROM {$this->tableNameOutside} ORDER BY fecha DESC LIMIT 1";

        $tempQuery = $this->mysqli->query($sql);

        $temperatura = array();

        while ($mostrar = mysqli_fetch_array($tempQuery)) {

            $temperatura = array("temperatura" => $mostrar['temp'], "fecha" => $this->time_elapsed_string($mostrar['fecha'] ) );
        }
        return $temperatura;
    }

    public function getOutsideTemperature() {

        $html = file_get_contents("https://www.wunderground.com/personal-weather-station/dashboard?ID=ISANTAFE105");
        $dom = new DOMDocument();

        @$dom->loadHTML($html);

        $temp = $dom->getElementById("curTemp");

        $textTemp = trim($temp->textContent);

        $valTemp = doubleval($textTemp);

        if (strpos($textTemp, "F")) {
            $valTemp = round(($valTemp - 32) / 1.8, 2);
        }

        $fecha = $dom->getElementById("update_time");

        return array("temperatura" => $valTemp, "fecha" => $fecha->textContent);
    }
   
    
     
    

    public function showTemperaturesForChart() {
        return json_encode($this->getTemperaturesForChart());
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

}
