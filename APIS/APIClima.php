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

    private function getClima5Dias($idciudad)
    {

        $url = "http://api.openweathermap.org/data/2.5/forecast?id=" . $idciudad . "&appid=742d1e4f2990bf2ce2fff58706d5e48e";
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;

    }

    private function getClima($idciudad)
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

    public function getRecomendacion($idciudad)
    {

        $clima5Dias = $this->getClima5Dias($idciudad);
        $clima = json_decode($clima5Dias, true);
        $recomendacion = array("ciudad" => $clima['city']['name']);
        $i = 0;


        foreach ($clima['list'] as $dia) {

            $fecha = new DateTime($dia['dt_txt']);
            $fecha = $fecha->format('Y-m-d');
            $hora = new DateTime($dia['dt_txt']);
            $hora = $hora->format('G:ia');

            $temp = number_format(floatval($dia['main']['temp']) - 275.15
                , 2);
            $temp_min = number_format(floatval($dia['main']['temp_min']) - 275.15
                , 2);
            $temp_max = number_format(floatval($dia['main']['temp_max']) - 275.15
                , 2);


                array_push($recomendacion,
                    array(
                        "temp" => $temp,
                        "temp_min" => $temp_min,
                        "temp_max" => $temp_max,
                        "humedad" => $dia['main']['humidity'],
                        "pronostico" => $dia['weather'][0]['main'],
                        "descripcion" => $dia['weather'][0]['description'],
                        "icono" => $dia['weather'][0]['icon'],
                        "nubes" => $dia['clouds']['all'],
                        "viento" => $dia['wind']['speed'],
                        "hora" => $hora,
                        "fecha" => $fecha,
                        "recomendacion" => $this->recomendacionXHora($temp)
                    ));

        }
        return $recomendacion;
    }

    private function recomendacionXHora($temp)
    {

        if ($temp < 0) return "El clima esta muy frio, te recomendamos salir bien abrigado";
        elseif ($temp > 10) return "El clima esta fresco, te recomendamos no olvidar un abrigo";
        elseif ($temp < 10) return "El clima esta frio, te recomendamos no salir sin abrigo";
        else return "";

    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }


}