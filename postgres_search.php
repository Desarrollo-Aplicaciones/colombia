<?php
//echo extension_loaded('pgsql') ? 'yes':'no';

$conn_string = "host=10.0.1.240 port=5432 dbname=farmalisto_colombia user=search password=search123";
$dbconn4 = pg_pconnect($conn_string) or die("Could not connect");
if (isset($_GET['word'])) {
$palabras = explode('|', $_GET['word']);

$query_search = "SELECT sw.word, SUM(si.weight) AS peso FROM
		ps_search_index si 
		INNER JOIN ps_search_word sw ON ( sw.id_word = si.id_word )
		WHERE  ( ";
			$query_search .= " ( 100 - ( ( levenshtein(sw.word, '".$palabras[0]."') ) * 100)/ length(sw.word) ) >= 70 ";

			if (count($palabras) > 1){
				
				foreach ($palabras as $key => $value ) {
					if ($key > 0 && trim($value) != '' ) {
						$query_search .= "  OR ( 100 - ( ( levenshtein(sw.word, '".$value."') ) * 100)/ length(sw.word) )>= 70 ";
					}
				}				
			} 
			
		$query_search .= ")
		GROUP BY si.id_product,sw.word 
		ORDER BY peso DESC
		LIMIT 10";
// echo "<br><hr>query: ".$query_search;

    $result=pg_exec($query_search); // Sample of SQL QUERY 
   
        while ( $fetch = pg_fetch_row($result)) {

        	echo "<hr>Peso: ".$fetch[1]." - Producto: ".$fetch[0];
        	//print_r($fetch);
        }// Sample of SQL QUERY 

    pg_close($dbconn4); // Close this connection 
} else {
	echo " <br> NO HAY PALABRAS PARA BUSCAR";
}

?>