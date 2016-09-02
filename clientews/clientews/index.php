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

        </tr></table>
    <tr><td>  </td> <td> <input type="submit" value="Enviar"></td> </tr>
</form>
<?php
include("clientews.php");

$client = new JSON_WebClient("http://www.farmalisto.com.co/mobilews/webservicemobile.php");

if (isset($_POST) && $_POST) {

    switch ($_POST['metodo']) {
        case 'getToken':
            $client->call("getTokenStr", "Token", "onSucceededCallback", "onErrorCallback");
            break;

        case 'getProductos':
            $client->call("getProductos", array("Buscar" => "LIVERBYL", 'Pagina' => '2', 'Filas' => '20', 'Ordenar' => 'A', 'Campo' => 'name'), "onSucceededCallback", "onErrorCallback");
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
    <textarea  rows="32" cols="132" ><?php echo json_encode($result); ?> </textarea>
    <?php
}

function onErrorCallback($error) {
    ?>
    <textarea  rows="7" cols="132" ><?php echo json_encode($error); ?> </textarea>


    <?php
}
