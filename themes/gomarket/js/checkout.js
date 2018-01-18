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
   * Horizontal Radio Buttons 
   */
  $("input[name='documentType']").click(function () {
    $("input[name='documentType']").parent().removeClass("checked");
    $(this).parent().addClass("checked");
  });
});