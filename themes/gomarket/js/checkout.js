$(function () {
  /**
   * Order - Steps
   */
  var $accordionLi = $('#accordion li');
  var idxStepCurrent = $accordionLi.index($('#accordion li.active'));
  $accordionLi.hover(
    function () {
      $accordionLi.removeClass("active");
      $(this).addClass("active");
    }, function () {
      $accordionLi.removeClass("active");
      $accordionLi.eq(idxStepCurrent).addClass("active");
    }
  );

  /**
   * User Data - Horizontal Radio Buttons
   */
  $documentType = $(".checkout .radio-horizontal");
  $documentType.click(function (event) {
    event.stopImmediatePropagation();
    var $this = $(this);
    
    // Activa el borde verde
    $documentType.removeClass("checked");
    $this.addClass("checked");
    // Muestra el formulario relacionado
    $("#natural-person, #nit").hide();
    $($this.data("show")).show();
    // Selecciona el checkbox
    $this.find("input").prop("checked", true);
  });

  /**
   * Address - Vertical Radio Buttons
   */
  $(".checkout address .radio-address").click(function (event) {
    event.stopImmediatePropagation();
    var idAddress = $(this).val();
    var $address = $("address[data-id='" + idAddress + "']");

    $("address").removeClass("selected");
    $address.addClass("selected");
  });

  /**
   * Address - Get Cities By State Available
   */
  $('select[name="id_state"]').change(function () {
    var $state = $(this);
    var $city = $('select[name="id_city"]');
    var $choose = $city.find("option:disabled");
    var idCity = $state.find("option:selected").data("idCity");
    
    $city.prop("disabled", true);
    $choose.text("Cargando...");
    
    $.getJSON("/app/services/cities", {
      id_state: $state.val()
    })
    .done(function (data) {
      $city.html("");
      $choose.appendTo($city);
      
      if (data) {
        $city.find("option:eq(0)").text("Selecciona").prop('selected', true);
        $.each(data, function (key, city) {
          $("<option/>", {
            "value": city.id,
            text: city.name
          }).appendTo($city);
        });
        $city.prop("disabled", false);

        if (typeof idCity !== "undefined") {
          $city.find('option[value="' + idCity + '"]').prop('selected', true); 
        }
      } else {
        $city.find("option:eq(0)").text("Depto. Sin Ciudad");
      }
    });
  });
  
});