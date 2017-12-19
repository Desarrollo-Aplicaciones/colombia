/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(function ($) {

    $("#grid").shieldGrid({
        dataSource: {
            data: globalGridData,
            schema: {
                fields: {
                    referencia: {type: String},
                    codigo_proveedor: {type: String},
                    producto: {type: String},
                    unitToBuy: {type: Number},
                    unitsProvider: {type: Number},
                    valor: {type: Number},
                    valor_venta: {type: Number}
                    //quantityBuy: {type: Number}
                }
            }
        },
        filtering: {
            enabled: true
        },
        paging: {
            pageSize: 50,
            pageLinksCount: 9,
            messages: {
                infoBarTemplate: "pagina {1} de {2}",
                firstTooltip: "First page",
                nextTooltip: "Next page",
                lastTooltip: "Last page"
            }
        },
        columns: [
            {field: "referencia", width: "120px", title: "Referencia"},
            {field: "codigo_proveedor", width: "120px", title: "Código proveedor"},
            {field: "producto", width: "200px", title: "Producto"},
            {field: "unitToBuy", width: "60px", title: "Unidades a comprar"},
            {field: "unitsProvider", width: "60px", title: "Unidades proveedor"},
            {field: "valor", width: "60px", title: "Valor de compra"},
            {field: "valor_venta", width: "70px", title: "Valor de venta"},
            //{field: "quantityBuy", width: "90px", title: "Cantidad a comprar"},

        ],
        events: {
            filterWidgetCreating: function (e) {
                if (e.field === "id") {
                    e.options = {max: 1000};
                }
                if (e.field === "age") {
                    e.options = {min: 1};
                }
            }
        }
    });

    $(".sui-filter-row .sui-filter-cell").each(function(){
        
        var fieldHide = $(this).attr('data-field');
      console.log("acaaa");
        if(fieldHide == 'unitToBuy' || fieldHide == 'unitsProvider' || fieldHide == 'valor' || fieldHide == 'valor_venta' || fieldHide == 'quantityBuy'){
            $(this).find('input').css("display","none");
            $(this).find('span').css("display","none");
            $(this).find('button').css("display","none");
        }
    })
});

// Calback function which creates dropdown and insert it into the filtered cell. 
function myCustomFilter(cell) {
    $('<div id="customDropDown"/>')
            .appendTo(cell)
            .shieldDropDown({
                dataSource: {
                    data: ["all", "male", "female"]
                },
                events:
                        {
                            select: function (e) {
                                if (e.item != "all") {
                                    // Manually filter the grid by gender field with equals to function and selected value in the dropdown.
                                    $("#grid").swidget().filter({path: "gender", filter: "eq", value: e.item});
                                } else {
                                    // Filter by not null values - will return all values - male and female.
                                    $("#grid").swidget().filter({path: "gender", filter: "notnull", value: ""});
                                }
                            }
                        }
            });
}