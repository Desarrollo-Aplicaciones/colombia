<form method="POST">
    <br>  
    <table><tr><td>Llamar Metodo ws</td>
            <td><select name="metodo">
                    <option value="getToken">getToken</option>
                    <option value="getProductos">getProductos</option>
                    <option value="getCategoria">getCategoria</option>
                    <option value="getCategorias">getCategorias</option>
                    <option value="getBanners">getBanners</option>
                  
                </select></td>
        
        </tr>
        <tr><td>Buscar Productos </td> <td><input type="text" name="buscar"/> </td></tr>
    </table>
    <tr><td>  </td> <td> <input type="submit" value="Enviar"></td> </tr>
</form>
<?php
include("clientews.php");
//192.168.10.82/
$client = new JSON_WebClient("http://www.farmalisto.com.co/mobilews/webservicemobile.php");

if (isset($_POST) && $_POST) {

    switch ($_POST['metodo']) {
        case 'getToken':
            $client->call("getTokenStr", "Token", "onSucceededCallback", "onErrorCallback");
            break;

        case 'getProductos':
                        if(isset($_POST['buscar'])&& $_POST['buscar']&& $_POST['buscar']!='')
                {
                            echo '<b>'. $_POST['buscar'].'</b><br>';
                            
                            $var=  json_encode(utf8_encode($_POST['buscar']));
                            $var= utf8_decode(json_decode($var));
                             echo '<b>json: '. $var.'</b><br>';
                            
            $client->call("getProductos", array("Buscar" => utf8_encode($_POST['buscar']), 'Pagina' => '2', 'Filas' => '20', 'Ordenar' => 'A', 'Campo' => 'id_product'), "onSucceededCallback", "onErrorCallback");
		}else{
	            $client->call("getProductos", array("Buscar" => "LIVERBYL", 'Pagina' => '2', 'Filas' => '20', 'Ordenar' => 'Z', 'Campo' => 'price'), "onSucceededCallback", "onErrorCallback");

			
			}
		
            break;

        case 'getCategoria':
            $client->call("getCategoria", array("categoria" => "sin_formula_medica", 'Pagina' => '2', 'Filas' => '20', 'Ordenar' => 'Z', 'Campo' => 'name'), "onSucceededCallback", "onErrorCallback");
            break;
        case 'getCategorias':
            $client->call("getCategorias", "farmalisto_colombia", "onSucceededCallback", "onErrorCallback");
            break;
        case 'getBanners':
            $client->call("getBanners", "banners_colombia", "onSucceededCallback", "onErrorCallback");
            break;

        default:
            echo '<b>Opción no contemplada<b>';
    }
}

function onSucceededCallback($result) {
    ?>
    <textarea  rows="12" cols="132" ><?php echo json_encode($result); ?> </textarea>
    <?php
    ?>
    <hr>
    <textarea  rows="12" cols="132" ><?php print_r( $result); ?> </textarea>
    <?php
    echo '<hr>';
    print_r( $result);
}

function onErrorCallback($error) {
    ?>
    <textarea  rows="7" cols="132" ><?php echo json_encode($error); ?> </textarea>


    <?php
      echo '<hr>';
    echo $error;
}
