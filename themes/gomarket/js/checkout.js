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

    //$address.find(".complete-data").show();
  });
});