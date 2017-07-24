<!DOCTYPE html>
<?php
include './model/Tercero.php';
include './config/params.php';

$tercero = new Tercero();
?>

<script>
    var jsonTercero =<?php echo json_encode($tercero->campos()["campos"]); ?>;
    var dirTipoIdentificacion = "<?php echo "controller/TipoIdentificacion.php"; ?>";
    var dirTerceroGuardar = "<?php echo "controller/TerceroGuardar.php"; ?>";
    var dirTerceroEliminar = "<?php echo "controller/TerceroEliminar.php"; ?>";
    var dirTerceroListar = "<?php echo "controller/TerceroListar.php"; ?>";
</script>


<!--jquery-->
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>


<!--bootstrap-->
<link rel="stylesheet" href="assets/bootstrap/arch/bootstrap.css" >
<script src="assets/bootstrap/arch/bootstrap.js" ></script>
<link rel="stylesheet" href="assets/bootstrap/arch/bootstrap-datetimepicker.css" >
<script src="assets/bootstrap/arch/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript" src="assets/bootstrap/arch/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>


<!--angular-->
<script src="assets/angular-1.5/angular.js" ></script>

<style>
    .div_padre {
        text-align: center;
        width: 100%;
    }

    .div_hijo {
        width: 80%;
        display: inline-block;
        text-align: left;
    }

    /*    .ng-valid{
            border: thin solid #090;
        }
        .ng-invalid{
            border: thin solid #990000;
            
        }*/
</style>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <br><br>


        <div class="div_padre" ng-app="tercero">
            <div class="div_hijo" ng-controller="terceroControler">

                <div class="div_mensaje"></div>

                <form role="form" name="forma">                    
                    <input type="hidden" ng-model="tercero.id_customer" class="form-control" id="id_customer" >
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text"  ng-model="tercero.nombre" class="form-control" id="nombre" name="nombre" required>
                        <div ng-show="forma.nombre.$error.required">
                            <span style="color: red;">Campo obligatorio</span>                            
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="select_id_tipo_identificacion">Tipo identificacion</label>                   
                        <select ng-model="tercero.select_id_tipo_identificacion" ng-options="option.nombre  for option in tipoIdentificacion track by option.id_tipo_identificacion"  class="form-control" id="select_id_tipo_identificacion" name="select_id_tipo_identificacion" required>
                        </select> 


                        <div ng-show="forma.option{{$index}}.$error.required">
                            <span style="color: red;">Campo obligatorio</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="identificacion">Identificacion</label>
                        <input ng-model="tercero.identificacion" class="form-control" id="identificacion" name="identificacion" required>
                        <div ng-show="forma.identificacion.$error.required">
                            <span style="color: red;">Campo obligatorio</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Telefono</label>
                        <input ng-model="tercero.telefono" class="form-control" id="telefono" name="telefono" required>
                        <div ng-show="forma.telefono.$error.required" >
                            <span style="color: red;">Campo obligatorio</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha nacimiento</label>
                        <input ng-model="tercero.fecha_nacimiento" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" readonly="true" required>
                        <div ng-show="forma.fecha_nacimiento.$error.required">
                            <span style="color: red;">Campo obligatorio</span>
                        </div>
                    </div>




                    <input type="button" class="btn btn-success" ng-disabled="forma.$invalid" ng-click="fnGuardarTercero()" value="{{textoBoton}}">

                    <button type="button" class="btn btn-warning" ng-click="fnLimpiarCampos()">Limpiar</button>

                </form>

                <br>

                <div class="div_mensaje"></div>

                <br><br>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo identificacion</th>
                            <th>Identificacion</th>
                            <th>Telefono</th>
                            <th>Fecha nacimiento</th>
                            <th>Fecha sist</th>
                            <th></th>

                        </tr>

                        <tr ng-repeat="listar in terceroListar" >
                            <td>{{listar.nombre}}</td>
                            <td>{{listar.nombre_tipo_identificacion}}</td>
                            <td>{{listar.identificacion}}</td>
                            <td>{{listar.telefono}}</td>
                            <td>{{listar.fecha_nacimiento}}</td>
                            <td>{{listar.fecha_sist}}</td>
                            <td>
                                <IMG SRC="assets/editar.jpg" style="width: 20px; cursor: pointer;" ng-click="fnLlenarCampos($index)" >
                                <IMG SRC="assets/eliminar.png" style="width: 20px; cursor: pointer;" ng-click="fnEliminarTercero($index)">
                            </td>
                        </tr>
                    </table>
                </div>


            </div>

        </div>



    </body>
</html>

<script type="text/javascript">
    var abc = "";
    $("#fecha_nacimiento").datetimepicker({
        language: 'es',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd'
    });

//        planesp.push({'nombre_sucursal':'a','precio_venta':'a','descuento_max':'a','hora_inicio':'a','hora_inicio':'a'});
    angular.module("tercero", [])
            .controller('terceroControler', ['$scope', '$http', function ($scope, $http) {


                    ////tipo identificacion
                    var res = $http.post(dirTipoIdentificacion, {});
                    res.success(function (data, status, headers, config) {
                        $scope.tipoIdentificacion = data;
                    });


                    $scope.tercero = Object.assign({}, jsonTercero);
                    $scope.terceroListar = {};
                    $scope.textoBoton = "Guardar";

                    $scope.fnLlenarCampos = function (index) {
                        console.log($scope.terceroListar[index]);
                        $scope.tercero = Object.assign({}, $scope.terceroListar[index]);
                        $scope.tercero.select_id_tipo_identificacion={"id_tipo_identificacion":$scope.terceroListar[index]["id_tipo_identificacion"]};
                        $scope.textoBoton = "Editar";
                    }
                    $scope.fnLimpiarCampos = function () {
                        $scope.tercero = Object.assign({}, jsonTercero);
                        $scope.textoBoton = "Guardar";
                    }

                    $scope.fnGuardarTercero = function () {

                        $(".div_mensaje").html("");
                        $(".div_mensaje").removeClass('alert alert-danger');
                        $(".div_mensaje").removeClass('alert alert-success');
                        
                        $scope.tercero.id_tipo_identificacion=$("#select_id_tipo_identificacion").val();

                        var dataObj = Object.assign({}, $scope.tercero);
                        var res = $http.post(dirTerceroGuardar, dataObj);
                        res.success(function (data, status, headers, config) {

                            if (data["codigo"] == 0) {
                                $(".div_mensaje").addClass('alert alert-danger');
                            } else {
                                $(".div_mensaje").addClass('alert alert-success');
                            }

                            $(".div_mensaje").html(data["mensaje"]);

                            setTimeout(function () {
                                $(".div_mensaje").hide();
                            }, 3000);
//                            $scope.message = data;
                            $scope.fnListarTercero();
                        });
                        res.error(function (data, status, headers, config) {
                            alert("failure message: " + JSON.stringify({data: data}));
                        });
                    }






                    $scope.fnEliminarTercero = function (index) {


                        $(".div_mensaje").html("");
                        $(".div_mensaje").removeClass('alert alert-danger');
                        $(".div_mensaje").removeClass('alert alert-success');

                        var txt;
                        var r = confirm("¿Seguro quiere eliminar el registro?");
                        if (r == true) {
                            var dataObj = {
                                id_customer: $scope.terceroListar[index]["id_customer"],
                            };

                            var dataObj = Object.assign({}, dataObj);
                            var res = $http.post(dirTerceroEliminar, dataObj);
                            res.success(function (data, status, headers, config) {

                                if (data["codigo"] == 0) {
                                    $(".div_mensaje").addClass('alert alert-danger');
                                } else {
                                    $(".div_mensaje").addClass('alert alert-success');
                                }

                                $(".div_mensaje").html(data["mensaje"]);

                                setTimeout(function () {
                                    $(".div_mensaje").hide();
                                }, 3000);
//                            $scope.message = data;
                                $scope.fnListarTercero();
                            });
                            res.error(function (data, status, headers, config) {
                                alert("failure message: " + JSON.stringify({data: data}));
                            });
                        }




                    }




                    $scope.fnListarTercero = function () {

                        var dataObj = {
                            name: "fff",
                        };

                        var res = $http.post(dirTerceroListar, dataObj);
                        res.success(function (data, status, headers, config) {
                            $scope.terceroListar = data;
                            abc = data[0]["nombre"];
                        });
                        res.error(function (data, status, headers, config) {
                            alert("failure message: " + JSON.stringify({data: data}));
                        });

                    }

                    $scope.fnListarTercero();


                }]);
</script>

