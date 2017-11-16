jQuery(function ($) {
    $("#grid").shieldGrid({
        dataSource: {
            data: globalGridData,
            schema: {
                fields: {
                    id: {type: String},
                    producto: {type: String},
                    ean: {type: String},
                    laboratorio: {type: String},
                    solicitados: {type: String},
                    warehouse_quantity: {type: String},
                    missing: {type: String}

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
            {field: "id", width: "90px", title: "ID"},
            {field: "producto", width: "200px", title: "Producto"},
            {field: "ean", width: "90px", title: "Referencia"},
            {field: "laboratorio", width: "90px", title: "Laboratorio"},
            {field: "solicitados", title: "Solicitados", width: "90px"},
            {field: "warehouse_quantity", title: "U/ Bodega", width: "90px"},
            {field: "missing", title: "Faltantes", width: "90px"}


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