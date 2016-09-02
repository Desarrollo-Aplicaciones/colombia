<?php 

require(dirname(__FILE__).'/config/config.inc.php');

try {
    
  


$id_shop=1;
$id_shop_group=1;

if(isset($_POST['mail']))
$email=$_POST['mail'];


$newsletter_date_add=date('Y-m-d H:i:s');
$ip_registration_newsletter=Tools::getRemoteAddr();
$active=1;

if(isset($_POST['nombre'])) {
$newsletter_name=$_POST['nombre'];
} else {
    $newsletter_name='';
}




if(isset($_POST['mx']))
{
    

$mx=$_POST['mx'];
}
else
{
$mx=0;

}

$expr="/[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/";

if(!preg_match($expr, $email)){
    echo 'El correo Electrónico ingresado no es válido. Por Favor Intente Nuevamente';
    ?>
 <a href="javascript:history.back(1)">Volver Atrás</a>
<?php
    exit();

}else{
    

    
    $sql="SELECT email FROM "._DB_PREFIX_."newsletter WHERE email='$email'";
    Db::getInstance()->execute($sql);
    $existe = Db::getInstance()->Affected_Rows();
    
    if ($existe<1){
        if(isset($_POST['hombre'])){
            $id_gender=1;

        }

        if(isset($_POST['mujer'])){
            $id_gender=2;
 
        }
        try {
            
            Db::getInstance()->insert('newsletter', array(
            'id_shop'=>(int)$id_shop,
            'id_shop_group'=>(int)$id_shop_group,
            'email'=>pSQL($email),
            'newsletter_date_add'=>$newsletter_date_add,
            'ip_registration_newsletter'=>pSQL($ip_registration_newsletter),
            'active'=>(int)$active,
            'newsletter_name'=>pSQL($newsletter_name ? $newsletter_name : ''),
            'id_gender'=>(int)$id_gender,
            'mx'=>(int)$mx,
        ));
            
  
        } catch (Exception $exc) {
       
          //echo $exc->getTraceAsString();
          //exit();
        }


        
        
        setcookie("newsletter", 'newsletter', time()+3600*24*30*365);

        if($mx!="1"){
            Mail::Send(1, 'newsletter_welcome', 'Bienvenido al boletin de Farmalisto', null, $email, null, null, null, null, null, _PS_ROOT_DIR_.'/mails/', false, 1);
           
        }
        header('Location: ./');
    }
}


} catch (Exception $exc) {

    
              
             //echo $exc->getTraceAsString();
            //exit();
}
header('Location: ./');
