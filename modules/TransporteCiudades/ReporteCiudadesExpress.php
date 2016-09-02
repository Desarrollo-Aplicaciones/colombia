<?php

    class ReporteCiudadesExpressCore {

        //CONSULTA LAS CIUDADES QUE APLICAN SERVICIO EXPRESS
        public function consultDataReport() {
            require(dirname(__FILE__) . '/../../config/config.inc.php');

            $query='SELECT s.name, cico.city_name, caci.express_arriba, caci.express_abajo
                    FROM '._DB_PREFIX_.'carrier_city caci
                    INNER JOIN '._DB_PREFIX_.'cities_col cico
                    on caci.id_city_des = cico.id_city
                    INNER JOIN '._DB_PREFIX_.'state s
                    on cico.id_state = s.id_state
                    WHERE caci.express_arriba <> "" 
                    AND caci.express_abajo <> ""
                    AND cico.id_country = '.Configuration::get('PS_COUNTRY_DEFAULT'). '
                    ORDER BY cico.city_name';

            $results = Db::getInstance()->ExecuteS($query);
            $this->downloadReportCityExpress($results);
            
        }

        //GENERA XLS CON LA INFORMACION OBTENIDA
        public function downloadReportCityExpress($dataReport){

            header('Content-type: application/vnd.ms-excel; charset=utf-8');
            header("Content-Disposition: attachment; filename=reporte_ciudades_servicio_express.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo "<table border=1> ";
            echo "<tr> ";
            echo "<center><b><h3>";
            echo "LISTA DE CIUDADES QUE APLICAN SERVICIO EXPRESS ";
            echo "</h3></b></center>";
            echo "</tr> ";
            if ($dataReport[0]['name'] != "") {
                echo "<tr> ";
                echo "<th>DEPARTAMENTO</th> ";
                echo "<th>CIUDAD</th> ";
                echo "<th>COSTO DEl SERVICIO ARRIBA DEL UMBRAL</th> ";
                echo "<th>COSTO DEl SERVICIO ABAJO DEL UMBRAL</th> ";
                echo "</tr> ";
                foreach ($dataReport as $valores) {
                    echo "<tr> ";
                    echo "<td>".utf8_decode($valores['name'])."</td> ";
                    echo "<td>".utf8_decode($valores['city_name'])."</td> ";
                    echo "<td>".utf8_decode($valores['express_arriba'])."</td> ";
                    echo "<td>".utf8_decode($valores['express_abajo'])."</td> ";
                    echo "</tr> ";
                }
            } else {
                echo "<tr> ";
                echo "<th width='500' >EL SERVICIO NO SE ENCUENTRA APLICADO PARA NINGUNA CIUDAD</th> ";
                echo "</tr> ";
            }            
            echo "</table> ";
            exit();
        }
    }
    
?>