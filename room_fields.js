function pickedUpRoom(){
  var x = document.getElementById("nr_te_rriturve");
  var y = document.getElementById("nr_femijeve");
  var z = document.getElementById("nr_i_dhomave");
  if(document.getElementById("dhoma").value == "junior_suite") {
    x.setAttribute("max",4);
    y.setAttribute("max",3);
    z.setAttribute("max",6);
    x.value="1";
    y.value="0";
    z.value = "1";
  }
  else if(document.getElementById("dhoma").value == "dhome_3-vendeshe") {
    x.setAttribute("max",3);
    y.setAttribute("max",3);
    z.setAttribute("max",6);
    x.value="1";
    y.value="0";
    z.value = "1";
  }
  else if(document.getElementById("dhoma").value == "") {
    x.setAttribute("max",0);
    y.setAttribute("max",-1);
    y.setAttribute("min",0);
    z.setAttribute("max",0);
    x.value="";
    y.value="";
    z.value="";
  }
  else {
    x.setAttribute("max",2);
    y.setAttribute("max",2);
    z.setAttribute("max",6);
    x.value="1";
    y.value="0";
    z.value = "1";
  }
}

function pickedUpFromDate(){
  var from = document.getElementById("from");
  var to = document.getElementById("to");
  var minDate = from.value.split("-");
  day=minDate[2];
  month=minDate[1];
  year=minDate[0];

  if(month==1 || month==3 || month==5 || month==7 || month==8 || month==10 || month==12) {
    if(day==31) {
      day = 1;
      month++;
    }
    else
      day++;
  }
  else if(month==4 || month==6 || month==9 || month==11) {
    if(day==30) {
      day = 1;
      month++;
    }
    else
      day++;
  }
  else {
    if(year%4==0 && year%100==0 || year%400==0) {
      if(day==29){
        day = 1;
        month++;
      }
      else day++;
  }
  else {
    if(day==28){
      day = 1;
      month++;
    }
    else day++;
  }
}

if(day < 10) day = "0" + day;
if(""+month.length < 2) month = "0" + month;
minDate[2]=day;
minDate[1]=month;
minDate[0]=year;
minDate = minDate.join('-');
to.setAttribute("min",minDate);
}
