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
  $documentType = $(".checkout .radio-border");
  $documentType.click(function (event) {
    event.stopImmediatePropagation();
    var $this = $(this);
    // Activa el borde verde
    $documentType.removeClass("checked");
    $this.addClass("checked");
    // Muestra el formulario relacionado
    $(".ctn-document-type").hide();
    $($this.data("show")).show();
    // Selecciona el checkbox
    $this.find("input").prop("checked", true);
  });
});