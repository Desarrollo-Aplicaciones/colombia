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
});