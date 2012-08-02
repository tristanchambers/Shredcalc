jQuery(document).ready(function() {
	  jQuery(".calc input").keyup(function(){
	   var levalue = stripMoney(jQuery(this).val());
	   if(!isNumber(levalue)){
	    jQuery(this).addClass("highlight");
	    jQuery("#totalcost").html("Error");
	   } else {
	    jQuery(this).removeClass("highlight");
	    calculate();
	   }
	   if(jQuery(this).hasClass("price")){
	    jQuery(this).val( "$" + levalue);
	   }
	  });
	  jQuery(".calc input").blur(function() {
	   var levalue = stripMoney(jQuery(this).val());
	   if(!levalue){
	    jQuery(this).val(0);
	    jQuery(this).removeClass("highlight");
	    calculate();
	   }
	  });
	  calculate();
});
function calculate(){
	var leform = document.calcform;
	var leoutput = document.getElementById("calcoutput");

	var employees = leform.employees.value;
	var shredtime = leform.shredtime.value;
	var workdays = 21.67; //leform.workdays.value;
	var wage = stripMoney(leform.wage.value);
	var benefits = stripMoney(leform.benefits.value);
	var shreddercost = stripMoney(leform.shreddercost.value);
	var shredderlife = leform.shredderlife.value;
	var sundry = stripMoney(leform.sundry.value);
	var recycling = stripMoney(leform.recycling.value);

//	leoutput.innerHTML = employees + " " + shredtime + " " + workdays + " " + wage + " " + benefits + " " + shreddercost + " " + shredderlife + " " + sundry + " " + recycling;

	var laborcosts = employees * (shredtime/60) * (parseFloat(wage) + parseFloat(benefits)) * workdays;
	var machinecosts = nanToZero((shreddercost / shredderlife)) + parseFloat(sundry) + parseFloat(recycling);
	var totalcosts = laborcosts + machinecosts;
	jQuery("#laborcosts").html(laborcosts.toFixed(2));
	jQuery("#machinecosts").html(machinecosts.toFixed(2));
	jQuery("#depreciation").html((nanToZero((shreddercost / shredderlife)) / workdays).toFixed(2));
	jQuery("#totaltime").html((employees * workdays * (shredtime/60)).toFixed(2));
	if(!isNaN(totalcosts)){
	 jQuery("#totalcost").html("$"+totalcosts.toFixed(2));
	} else {
	  jQuery("#totalcost").html("Error");
	}
//jQuery("#totalcost").html(employees + " " + shredtime + " " + workdays + " " + wage + " " + benefits + " " + shreddercost + " " + shredderlife + " " + sundry + " " + recycling);
}
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function stripMoney(num) {
  return num.replace(/\$|\,/g,"");
}
function nanToZero(n) {
  if(isNaN(n)){
	return(0);
  } else {
	return(n);
  }
}

