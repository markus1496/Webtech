var teamPointsTable = document.getElementById('teamPointsTable');
var submitPtsBtn = document.getElementById('submitBody');
var addPts = document.getElementById('addPts');
var lol = document.getElementById('lolko');
var hid = document.getElementById('hid');
window.onload = function() {
  console.log(lol);
  if (hid != null) {
    //addPts.style.display = 'none';
    //teamPointsTable.style.display = 'block';
  } else {

  }

  submitPtsBtn.onclick = function() {
    //addPts.style.display = 'none';
    //addPts.style.visibility = 'hidden';
  }
}

function formSubmit(house_number)
{
  document.forms[0].house_number.value = house_number;
  document.forms[0].submit();
}