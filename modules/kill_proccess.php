<?php
error_reporting(0);
$echoar =  date("Y-m-d h:i:s A");
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

                $procesos_matar = "SELECT CONCAT('KILL ',ID,';') AS killcommand
                                FROM INFORMATION_SCHEMA.PROCESSLIST 
                                WHERE User = 'farmalisto'
                                AND ( ( COMMAND = 'Sleep'  AND TIME > 5 ) OR ( COMMAND = 'Query'  AND TIME > 60 ) )
                                AND HOST LIKE '10.0.0.83%'
                                AND DB = 'farmalisto_colombia';";

                if ($result = Db::getInstance()->ExecuteS( $procesos_matar)) {
                        $kill = 0;
                        $nokill = 0;

                        foreach( $result AS $key => $value ){
                                if (!mysqli_query($mysqli_1, $value['killcommand'])) {
                                        $echoar .= " Error kill proc: ".$value['killcommand']." - Mensaje error: " .  mysqli_error($mysqli_1);
                                        $nokill++;
                                } else {
                                        $kill++;
                                }
                        }
                      if ( $kill > 0 ) {
                                $echoar .= " Procesos eliminados: ".$kill." - - - Procesos no eliminados: ".$nokill;
                                echo "\n".$echoar;
                        }

                } else {
                        echo " Sin procesos para matar ";
                }


/*******************************   POSTGRESQL  *******************

                        $conn_string = "host=search.cuznbgafgkfl.us-east-1.rds.amazonaws.com port=5432 dbname=farmalisto_colombia user=bus_col password='nyT.19*xS'";
                        $dbconn4 = pg_pconnect($conn_string);
                        $conn_open = 1;


                        $query_psql = "SELECT  CONCAT ( ' SELECT pg_terminate_backend('  , pid , ');' ) as matar, extract('epoch' from now() - query_start)
                                         FROM pg_stat_activity
                                        WHERE state = 'idle'
                                        AND ( ( client_addr::text LIKE '10.0.0%' )  )
                                        AND extract('epoch' from now() - query_start) > 30";
                                                /*OR ( client_addr::text LIKE '192.168.10.1%' )

                        if ( $resultado = pg_exec($query_psql) ) {

                                $kill_pg = 0;
                                $nokill_pg = 0;

                                while ( $fetch = pg_fetch_row($resultado)) {
                                        $kill_process = $fetch[0];

                                        if ( $resultado2 = pg_exec($kill_process) ) {
                                                $kill_pg++;
                                        } else {
                                                $nokill_pg++;
                                        }

                                }
                        }

                                if ($conn_open == 1) {
                                        pg_close($dbconn4);
                                        $conn_open = 0;
                                        }

        if ( $kill > 0 || $kill_pg > 0 ) {
                $echoar .= " Proc kill mysql: ".$kill." - - - Proc kill mysql: ".$nokill;
                $echoar .= " Proc kill PGsql: ".$kill_pg." - - - Proc kill PGsql: ".$nokill_pg;
                echo "\n".$echoar;
        }
*/

?>
     
