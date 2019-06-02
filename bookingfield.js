function bookingNrValidation() {
  var booking = document.getElementById("booking_id");
  var bookingValue = booking.value.trim();
  if(/^[0-9]+$/.test(bookingValue))
    return true;
  else
    return false;
}

function insertAfterInput() {
    if(!bookingNrValidation()) {
      var newEl = document.createElement('div');
      newEl.innerHTML = "<p style='color:red;margin-left:30px;' >Kjo fushe duhet te plotesohet dhe te permbaje vetem numra</p>";
      var ref = document.getElementById("btnkerko");
      ref.parentNode.insertBefore(newEl, ref.nextSibling);
    }
}
