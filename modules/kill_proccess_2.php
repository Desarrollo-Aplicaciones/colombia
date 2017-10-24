<?php
error_reporting(0);
echo "<br> Ini:".date("Y-m-d h:i:s A");

$echoar = "<br>". date("Y-m-d h:i:s A");
include_once("../config/config.inc.php");

$mysqli_1 = mysqli_init();
        mysqli_options($mysqli_1, MYSQLI_OPT_LOCAL_INFILE, true);

        $url_post = explode(':', _DB_SERVER_);


        if ( count($url_post) > 1 ) {

          mysqli_real_connect($mysqli_1, $url_post[0], _DB_USER_, _DB_PASSWD_, _DB_NAME_, $url_post[1]);

        } else {

          mysqli_real_connect($mysqli_1, _DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);

        }


        if (mysqli_connect_errno()) {
           $echoar .= " Conexión fallida: %s\n mensaje error: " . mysqli_connect_error();
        }

     /*       echo "<br>\r\n<br>".    $procesos_matar = "SELECT CONCAT('KILL ',ID,';') AS killcommand
                                FROM INFORMATION_SCHEMA.PROCESSLIST 
                                WHERE User = 'farmalisto'
                                AND ( ( COMMAND = 'Sleep'  AND TIME > 3 ) OR ( COMMAND = 'Query'  AND TIME > 60 ) )
                                AND HOST LIKE '10.0.0.85%'
                                AND DB = 'farmalisto_mexico';";
*/
$result = Db::getInstance()->ExecuteS( "CALL kill_user_processes('farmalisto')");

/*                if ($result = Db::getInstance()->ExecuteS( $procesos_matar)) {
                        $kill = 0;
                        $nokill = 0;

                        foreach( $result AS $key => $value ){
                                if (!mysqli_query($mysqli_1, $value['killcommand'])) {
                                        $echoar .= "<br> Error kill proc: ".$value['killcommand']." - Mensaje error: " .  mysqli_error($mysqli_1);
                                        $nokill++;
                                } else {
                                        $kill++;
                                }
                        }
                      if ( $kill > 0 ) {
                                $echoar .= "<br> Procesos eliminados: ".$kill." - - - Procesos no eliminados: ".$nokill;
                                echo "\n".$echoar." - <br>".date("Y-m-d h:i:s A");
                        }

                } else {
                        echo "<br> Sin procesos para matar ";
                }*/

echo "<br> Fin:".date("Y-m-d h:i:s A");
?>
