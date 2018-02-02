/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(function ($) {
    console.log("Entre a detalles");
    $("#grid").shieldGrid({
        dataSource: {
            data: globalGridData,
            schema: {
                fields: {
                    referencia: {type: String},
                    producto: {type: String},
                    unitToBuy: {type: Number},
                    unitExpected: {type: Number},
                    unitReceived: {type: Number},
                    unitPrice: {type: Number},
                    supplier: {type: Number}
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
            {field: "referencia", width: "90px", title: "Referencia"},
            {field: "producto", width: "200px", title: "Producto"},
            {field: "unitToBuy", width: "90px", title: "Unidades a comprar"},
            {field: "unitExpected", width: "90px", title: "Unidad Esperada"},
            {field: "unitReceived", width: "90px", title: "Unidad Recibida"},
            {field: "unitPrice",  width: "90px",title: "Precio Unidad (Sin IVA)"},
            {field: "supplier",  width: "90px",title: "Proveedor / Menor precio"},
            
          


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