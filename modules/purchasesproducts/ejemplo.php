<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ejemplo
 *
 * @author desarrollo
 */
class ejemplo {
    
    public function ejemploa() {
        echo "{
                class: 'table-striped table-bordered table-condensed',
                columnas: [
                    
                    { leyenda: 'Profesión', style: 'width:200px;', columna: 'Profesion_id', ordenable: true, filtro: function(){
                        return anexGrid_select({
                            data: [
                                { valor: '', contenido: 'Todos' },
                                { valor: '1', contenido: 'Abogado' },
                                { valor: '2', contenido: 'Bombero' },
                                { valor: '3', contenido: 'Doctor' },
                                { valor: '4', contenido: 'Ingeniero Civil' },
                                { valor: '5', contenido: 'Ingeniero de Sistemas' },
                                { valor: '6', contenido: 'Músico' }
                            ]
                        });
                    } },
                    { leyenda: 'Empleado', class: '', ordenable: true, columna: 'Nombre', filtro: true },
                    { leyenda: 'Correo', style: 'width:300px;', ordenable: true, filtro: true, columna: 'Correo' },
                    { leyenda: 'Sexo', style: 'width:120px;', columna: 'Sexo', filtro: function(){
                        return anexGrid_select({
                            data: [
                                { valor: '', contenido: 'Todos' },
                                { valor: '1', contenido: 'Masculino' },
                                { valor: '2', contenido: 'Femenino' }
                            ]
                        });
                    } },
                    { leyenda: 'Sueldo', style: 'width:100px;', ordenable: true, columna: 'Sueldo' },
                    { leyenda: 'Registro', style: 'width:100px;', ordenable: true, columna: 'FechaRegistro' }
                ],
                modelo: [
                    { class: 'text-center', formato: function(tr, obj, valor){
                        return anexGrid_dropdown({
                            contenido: '<i class=\"glyphicon glyphicon-cog\"></i>',
                            class: 'btn btn-primary btn-xs',
                            target: '_blank',
                            id: 'editar-' + obj.id,
                            data: [
                                { href: '#', contenido: 'Editar' },
                                { href: '#', contenido: 'Eliminar' }
                            ]
                        });
                        }
                    },
                    { propiedad: 'Profesion.Nombre' },
                    { style: '', class: '', formato: function(tr, obj, valor){
                        return obj.Nombre + ' ' + obj.Apellido;
                    }}
                    
                ],
                url: '../modules/purchasesproducts/purchasesproducts.php',
                paginable: true,
                filtrable: true,
                limite: [20, 60, 100],
                columna: 'id',
                columna_orden: 'DESC'
            };";
    }
}

$pur = new ejemplo();
$pur->ejemploa();