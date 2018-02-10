<?php
/**
 * Created by PhpStorm.
 * User: ele
 * Date: 2/9/18
 * Time: 11:24 PM
 */

class APIClima
{


    //private $conn;

    /**
     * APIClima constructor.
     * @param
     */
    /*    public function __construct()
        {
            $this->conn = new mysqli(HOST, USERNAME, PASSWD,DBNAME);
            if ($this->conn->connect_errno) {
                print "Error en la conexion";
            }

        }*/

    public function getClima($idciudad)
    {

        $url = "http://api.openweathermap.org/data/2.5/weather?id=" . $idciudad . "&appid=742d1e4f2990bf2ce2fff58706d5e48e";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);


        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;

    }


}