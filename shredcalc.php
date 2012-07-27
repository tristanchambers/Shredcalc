<?php
/*
Plugin Name: Shred Calc
Description: Calculates cost of in house shredding
Version: 1.0
Author: Tristan Chambers tristan.chambers@gmail.com
*/

error_reporting(E_ALL);
add_action("widgets_init", array('shredcalc', 'register'));
class shredcalc {
  function control(){
    echo 'Calculates cost of in house shredding';
  }
  function widget($args){
    echo $args['before_widget'];
    echo $args['before_title'] . 'Shred Calc' . $args['after_title'];
    echo '
<style type="text/css">
div.calc {
	font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;
	width: 290px;
	border: 1px dashed grey;
	font-size: 12px;
	line-height: 16px;
	background: white;
}
div.calc label {
	background: #eee;
	margin: 2px;
	padding: 2px;
	width: 160px;
	display: inline-block;
	text-align: right;
	vertical-align: middle;
	margin-left: 5px;
}
div.calc input {
	width: 100px;
	font-size:12px;
	padding: 4px 2px;
	border:solid 1px grey;
	margin-left: 3px;
	text-align: right;
/*	float: left;*/
}
span.label-title {
	font-weight: bold;
	display: block;
}
span.label-caption {
	font-size: .9em;
}
span.unit {
	position: absolute;
	margin: -14px 0 0 13px ;
	opacity: .5;
	font-size: .9em;
}
input.highlight {
	-moz-box-shadow: 0 0 3px 3px rgba(100, 0, 0, 0.25);
	-webkit-box-shadow: 0 0 3px 3px rgba(100, 0, 0, 0.25);
	box-shadow: 0 0 3px 3px rgba(100, 0, 0, 0.25);
}
#calcoutput {
	text-align: center;
	padding: 10px;
}
#totalcost {
	line-height: 60px;
	font-size: 18px;
	font-weight: bold;
}
</style>

<div class="calc">
<form name="calcform">
<label for="employees"><span class="label-title">Employees</span><span class="label-caption">Number of employees</span></label><input type="text" name="employees"/>
<label for="shredtime"><span class="label-title">Time</span><span class="label-caption">Daily shred time per person</span><span class="unit">mins</span></label><input type="text" name="shredtime" />
<!--<label for="workdays"><span class="label-title">Days</span><span class="label-caption">Work days per month</span><span class="unit">days/month</span></label><input type="text" name="workdays" /> -->
<label for="wage"><span class="label-title">Wage</span><span class="label-caption">Employee average wage</span><span class="unit">$/hr.</span></label><input type="text" name="wage" class="price" />
<label for="benefits"><span class="label-title">Benefits</span><span class="label-caption">Estimated benefits</span><span class="unit">$/hr.</span></label><input type="text" name="benefits" class="price" />
<label for="shreddercost"><span class="label-title">Capital</span><span class="label-caption">Cost of shredder</span></label><input type="text" name="shreddercost" class="price" />
<label for="shredderlife"><span class="label-title">Lifespan</span><span class="label-caption">Life expectancy of shredder</span><span class="unit">months</span></label><input type="text" name="shredderlife" />
<label for="sundry"><span class="label-title">Sundry</span><span class="label-caption">Monthly cost of bags, oil, sharpening, etc.</span></label><input type="text" name="sundry" class="price" />
<label for="recycling"><span class="label-title">Recycling</span><span class="label-caption">Recycling cost (assume $7 per large bag)*</span></label><input type="text" name="recycling" class="price" />
</form>
<div id="calcoutput">
Labor costs (per month) <span class="output" id="laborcosts"></span><br>
Machine costs (per month) <span class="output" id="machinecosts"></span><br>
Machine depreciation (per month) <span class="output" id="depreciation"></span><br>
Time spent shredding (hours/month) - (does not include maintenance) <span class="output" id="totaltime"></span><br>
Total shredding costs (per month) <br> <span class="output" id="totalcost"></span><br>
</div>
</div>';
    echo $args['after_widget'];
	echo '
<script type="text/javascript" 
        src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  // You may specify partial version numbers, such as "1" or "1.3",
  //  with the same result. Doing so will automatically load the 
  //  latest version matching that partial revision pattern 
  //  (e.g. 1.3 would load 1.3.2 today and 1 would load 1.4.1).
  google.load("jquery", "1");
 
  google.setOnLoadCallback(function() {
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
</script>
	';

  }
  function register(){
    register_sidebar_widget('Shred Calc', array('shredcalc', 'widget'));
    register_widget_control('Shred Calc', array('shredcalc', 'control'));
  }
}
?>
