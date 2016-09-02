<form method="POST">
    <br>  
    <table><tr><td>Llamar Metodo ws</td>
            <td><select name="metodo">
                    <option value="setOrderState">setOrderState</option>
                </select></td>
        
        </tr>
        <tr>
            <td>Usuario</td> 
            <td> <input type="text" value="alertasclaro" name="user" /> </td> 
        </tr>
        <tr>
            <td>Contraseña</td> 
            <td> <input type="text" value="password" name="password" /> </td> 
        </tr>
        <tr>
            <td>Guía</td> <td><input type="text" value="12345" name="guia" /> </td>    
        </tr>
        <tr>
            <td>Fecha</td> <td><input type="text" value="2015-06-18 21:46:12" name="datetime" /> </td>    
        </tr>
        <tr>
            <td>Latitud</td> <td><input type="text" value="4.71725329" name="latitud" /> </td>    
        </tr>
        <tr>
            <td>Longitud</td> <td><input type="text" value="-74.06611051" name="longitud" /> </td>    
        </tr>
        <tr>
            <td>Estado pedido</td> <td><input type="text" value="Entregado" name="state" /> </td>    
        </tr>
 
        <tr>
            <td>  </td> <td> <input type="submit" value="Enviar"></td> 
        </tr>
</table>
</form>


<?php
include("clientews.php");
include("Bcrypt.php");
 
 // se crea una instancia de la clase JSON_WebClient inicilaizando el nuevo objeto con la URL del servicio web     
$client = new JSON_WebClient("http://farmatest:h2o-123@test.farmalisto.com.co/mobilews/webservicemobile.php");

// validación de campos del formulario
if (isset($_POST) && $_POST && !empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['guia']) && !empty($_POST['datetime']) && !empty($_POST['latitud']) && !empty($_POST['longitud']) && !empty($_POST['state'])) {

    // encriptación de contraseña algoritmo bcrypt
    $passhash = NULL;
    if(phpversion() >= 5.5){ // si la versión de PHP es mayor o igual a 5.5 se utiliza la funcionan nativa  password_hash
        $passhash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }else{ // si la versión de PHP es inferior a 5.5 se utiliza la clase  Bcrypt
        $passhash = Bcrypt::hash($_POST['password'],10);
    }
    
        // validando el hash generado
     if(crypt($_POST['password'], $passhash) == $passhash) {    
        echo "¡Contraseña verificada!";
    }

    switch ($_POST['metodo']) {
            case 'setOrderState':
                // Se llama al método call pasando-le como parámetros el nombre del método del servicio web, un arreglo con todos parámetros requeridos por método, finalmente se retorna la respuesta por alguno de las funciones Callback
                $client->call("setOrderState", array('user' => md5($_POST['user']),'passhash'=> $passhash, 'guia' => (int) $_POST['guia'], 'state' => $_POST['state'],'datetime'=>$_POST['datetime'],'latitud'=>$_POST['latitud'],'longitud'=>$_POST['longitud']), "onSucceededCallback", "onErrorCallback");
            break;    

        default:
            echo '<b>Opción no contemplada<b>';
    }
}else{
  echo '<br> Diligencia todos los campos del formulario y pulsa en enviar.';
    
}

/**
    retorna la respuesta del servicio web en caso de ser satisfactoria.
**/
function onSucceededCallback($result) {
    ?>
    <h2> Respuesta servicio web </h2>
    <textarea  rows="12" cols="132" ><?php 
        print_r($result);
    echo "

-----------------------------------------------------------------------------------------------------------------------------------

";
        print_r(json_decode($result,TRUE));
     ?></textarea>
    <?php

}

/**
    retorna un mensaje de error en caso de error.
**/
function onErrorCallback($error) {
    ?>
    <textarea  rows="7" cols="132" ><?php echo json_encode($error); ?> </textarea>


    <?php
      echo '<hr>';
    echo print_r($error,true);
}