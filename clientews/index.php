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
<a href="CrearCarritoFarmalisto.html">Crear Carrito</a>
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
                            
            $client->call("getProductos", array("Buscar" => utf8_encode($_POST['buscar']), 'Pagina' => '1', 'Filas' => '20', 'Ordenar' => 'A', 'Campo' => 'price'), "onSucceededCallback", "onErrorCallback");
		}else{
	            $client->call("getProductos", array("Buscar" => "LIVERBYL", 'Pagina' => '1', 'Filas' => '20', 'Ordenar' => 'Z', 'Campo' => 'id_product'), "onSucceededCallback", "onErrorCallback");

			
			}
		
            break;

        case 'getCategoria':
            $client->call("getCategoria", array("categoria" => "cuidado_personal", 'Pagina' => '3', 'Filas' => '20', 'Ordenar' => 'A', 'Campo' => 'name'), "onSucceededCallback", "onErrorCallback");
            break;
        case 'getCategorias':
            $client->call("getCategorias", "farmalisto_colombia", "onSucceededCallback", "onErrorCallback");
            break;
        case 'getBanners':
            $client->call("getBanners", "banners_colombia", "onSucceededCallback", "onErrorCallback");
            break;

        default:
            echo '<b>Opci�n no contemplada<b>';
    }
}

function onSucceededCallback($result) {
    ?>
<textarea  rows="12" cols="132" ><pre><?php echo json_encode($result); ?> </pre></textarea>
    <?php
    ?>
    <hr>
    <textarea  rows="12" cols="132" ><?php print_r( $result); ?> </textarea>
    <?php
 echo '<h2>Resultados: '.count($result['productos']).'</h2>';
 ?>

    <table border="1">
            <thead>
                <tr>
                    <th>id_product</th>
                    <th>price</th>
                    <th>name</th>
                </tr>
            </thead>
            <tbody>
                <?php   foreach ($result['productos'] as $value) {     ?>
                <tr>
                    <td><?php echo $value[0]['id_product'];?></td>
                    <td><?php echo $value[0]['price'];?></td>
                    <td><?php echo $value[0]['name'];?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php
}

function onErrorCallback($error) {
    ?>
    <textarea  rows="7" cols="132" ><?php echo json_encode($error); ?> </textarea>


    <?php
      echo '<hr>';
    echo $error;
}
