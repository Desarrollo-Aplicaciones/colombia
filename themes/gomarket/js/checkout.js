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
    var $input = $this.find('input[name="type_document"]');

    // Activate the green border
    $documentType.removeClass("checked");
    $this.addClass("checked");
    // Show the related form
    $("#natural-person, #nit").hide().find('input,textarea,select').prop("disabled", true);
    $($this.data("show")).show().find('input,textarea,select').prop("disabled", false);

    // Select the checkbox
    $this.find("input").prop("checked", true);

    // Activa los required del formulario activo
    // Evita - An invalid form control with name='' is not focusable
    $('[data-validate="true"]:hidden').prop("required", false);
    $('[data-validate="true"]:visible').prop("required", true);

    // Validation type of document if is person
    if (!parseInt($input.val())) {
      $input.val($("#billing-document-type").val());
    }
    
  });

  /**
   * User Data - Validation type of document
   */
  $("#billing-document-type").change(function() {
    $("#radio-person").val($(this).val());
  });

  /**
   * User Data - Customer Birthdate
   */
  $('[id^="birthdate-"]').change(function() {
    $('input[name="birthday"]').val(
      $('[id^="birthdate-year"]').val() 
      + "-" + 
      $('[id^="birthdate-month"]').val() 
      + "-" + 
      $('[id^="birthdate-day"]').val()
    );
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
          $('input[name="city_name"]').val($city.find("option:selected").text());
        }
      } else {
        $city.find("option:eq(0)").text("Depto. Sin Ciudad");
      }
    });
  });

  /**
   * Address - Add city name
   */
  $('select[name="id_city"]').change(function () {
    $('input[name="city_name"]').val($(this).find("option:selected").text());
  });

  /**
   * Address - Number Document
   */
  $('input[name="number_document"]').keyup(function () {
    $('input[name="dni"]').val($(this).val());
  });

  /**
   * Medical Formula Rx
   */
  $('.trash-rx').click(function () {
    $('input[id="upload"], input[type="file"]').val('');
    $(".btn-rx-attach").prop("disabled", false);
    $(this).hide();
  });

  $('.checkout input[type="file"]').change(function () {
    if ($(this).val()) {
      $(".btn-rx-attach").prop("disabled", true);
      $('.trash-rx').show();
    } else {
      $('.trash-rx').hide();
    }
  });
});