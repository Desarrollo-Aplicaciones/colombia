<?php
// Include the SDK using the Composer autoloader
require 'aws-autoloader.php';

use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

$s3Client = new Aws\S3\S3Client([
  'version' => 'latest',
  'region'  => 'us-east-1',
  'credentials' => [
        'key'    => 'AKIAIJMZFOD35IV6OCKQ',
        'secret' => '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',
    ],
]);
/*
$result = $s3Client->getObject([
    'Bucket' => 'imagenes-colombia',
    'Key'    => 'france2.jpg'
]);
echo "<pre>";
var_dump($result);
echo "</pre>";
*/

if ( isset($_FILES) && isset($_FILES['imagenes']['name']) && !empty($_FILES['imagenes']['name']) ) {

$dir_subida = '/tmp/';
$fichero_subido = $dir_subida . basename($_FILES['imagenes']['name']);

echo "<br> ruta: ".$_FILES['imagenes']['tmp_name'];
echo '<pre>';

if (move_uploaded_file($_FILES['imagenes']['tmp_name'], $fichero_subido)) {
    echo "El fichero es válido y se subió con éxito.\n";
echo'<br>pepe:<img src="'.$fichero_subido.'">'.$fichero_subido;
} else {
    echo "¡Posible ataque de subida de ficheros!\n";
}

  $uploader = new MultipartUploader($s3Client, $fichero_subido, [
    'bucket' => 'imagenes-colombia',
    'key'    => $_FILES['imagenes']['name'],
    'acl'    => 'public-read'  
  ]);

  try {
      $result = $uploader->upload();
      echo "Upload complete: {$result['ObjectURL']}\n";
  } catch (MultipartUploadException $e) {
      echo $e->getMessage() . "\n";
  }
}
echo '
<form action="" method="post" enctype="multipart/form-data">
<p>Imágenes:
<input type="file" name="imagenes" />
<input type="submit" value="Enviar" />
</p>
</form>
';
/*
$dir_subida = '/tmp/';
$fichero_subido = $dir_subida . basename($_FILES['imagenes']['name']);

echo "<br> ruta: ".$_FILES['imagenes']['tmp_name'];
echo '<pre>';

if (move_uploaded_file($_FILES['imagenes']['tmp_name'], $fichero_subido)) {
    echo "El fichero es válido y se subió con éxito.\n";
echo'<br>pepe:<img src="'.$fichero_subido.'">';
} else {
    echo "¡Posible ataque de subida de ficheros!\n";
}
*/
echo 'Más información de depuración:';
print_r($_FILES);

print "</pre>";
