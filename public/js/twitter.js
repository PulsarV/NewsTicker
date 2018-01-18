$(document).ready(function() {
  var timeIndicator = $('.time-indicator');
  var timerValue = parseInt(timeIndicator.text());
  window.setInterval(refreshTimer, 1000);

  function refreshTimer() {
    if (timerValue) {
      timerValue--;
      timeIndicator.text(timerValue + 's');
    } else {
      refreshPage();
    }
  }

  function refreshPage() {
    location.reload();
  }
});
