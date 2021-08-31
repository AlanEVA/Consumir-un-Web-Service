<?php
require __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;
//Crear el cliente para llamadas al servicio
//Debes cambiar el valor de base_uri a la dirección en donde esta tu servicio
//El valor de timeout, en este caso es para decir que despues de 5 segundos
//si el servicio no responde, se cancela el proceso.
$client = new Client([
    'base_uri' => 'https://localhost/webservice/index.php',
    'timeout'  => 5.0,
    'verify'  => false,

]);

//Hacer la llamada al metodo get, sin ningún parametro
$res = $client->request('GET');
if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
{
  echo $res->getBody();
}
?>

///////**** MOSTRAR UN REGISTRO **** ///////
//Hago llamado a REST para recuperar un solo articulo
//Ahora le pasamos un parametro al llamado del servicio
$res = $client->request('GET',null,[
    'query' => ['id' => '1']
]);
if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
{
  //Convertir el resultado que viene en formato JSON a un array
  $json2Array = json_decode($res->getBody(), true);
  //Ahora que esta la informacion en Array, podemos acceder a ella de forma sencilla
  echo $json2Array['content'];
}

///////**** INSERTAR UN REGISTRO **** ///////
//Hago la llamada al servicio rest, para insertar un articulo
$articulo = ['title'=>'Insertar usando Rest',
             'status'=>'draft',
             'content'=>'Este es un ejemplo del metodo POST',
             'user_id'=>'1'
            ];
$res = $client->request('POST', '', ['form_params' => $articulo]);
if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
{
  echo "Se inserto un post (articulo)";
}
<?php
///////**** ACTUALIZAR UN REGISTRO **** ///////
// =============================================
//Actualizar un articulo usando PUT
$actualizar = ['title'=>'Articulo actualizado',
             'status'=>'draft',
             'content'=>'Esta es una guia sencilla',
             'user_id'=>'1',
             'id'=>'1' //ID del articulo a modificar
            ];
$res = $client->request('PUT',null,[
    'query' => $actualizar
]);
if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
{
  echo $res->getBody();
}
<?php
///////**** ELIMINAR UN REGISTRO **** ///////
// =============================================
//Hago llamado a REST para borrar un  articulo
$res = $client->request('DELETE',null,[
    'query' => ['id' => '1'] //Id del post a eliminar
]);
if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
{
  echo $res->getBody();
}
?>
