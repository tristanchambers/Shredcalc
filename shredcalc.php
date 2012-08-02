<?php
/*
Plugin Name: Shred Calc
Description: Calculates cost of in house shredding
Version: 3.3.1
Author: Tristan Chambers tristan.chambers@gmail.com
*/

error_reporting(E_ALL);
add_action("widgets_init", array('shredcalc', 'register'));

function my_scripts_method() {
//	wp_register_script( 'qtip', plugins_url( 'jquery.qtip-1.0.0-rc3.min.js', __FILE__ ));
//    wp_enqueue_script( 'qtip' );
	wp_register_script('shredcalc', plugins_url('shredcalc.js', __FILE__));
	wp_enqueue_script('jquery');
	wp_enqueue_script('shredcalc');
}
 
add_action('wp_enqueue_scripts', 'my_scripts_method');

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
/*	line-height: 60px;*/
	font-size: 18px;
	font-weight: bold;
	margin-top: 10px;
}
#totalbox {
	font-size: 15px;
	padding-top: 10px;
	padding-bottom: 15px;
	width: 175px;
	margin-left: auto;
	margin-right: auto;
}
</style>

<div class="calc">
<form name="calcform">
<label for="employees"><span class="label-title">Employees</span><span class="label-caption">Number of employees</span></label><input type="text" name="employees" value="5" />
<label for="shredtime"><span class="label-title">Time</span><span class="label-caption">Daily shred time per person</span><span class="unit">mins</span></label><input type="text" name="shredtime" value="3" />
<label for="wage"><span class="label-title">Wage</span><span class="label-caption">Employee average wage</span><span class="unit">$/hr.</span></label><input type="text" name="wage" class="price" value="$16.19" />
<label for="benefits"><span class="label-title">Benefits</span><span class="label-caption">Estimated benefits</span><span class="unit">$/hr.</span></label><input type="text" name="benefits" class="price" value="$0.00" />
<label for="shreddercost"><span class="label-title">Capital</span><span class="label-caption">Cost of shredder</span></label><input type="text" name="shreddercost" class="price" value="$2000" />
<label for="shredderlife"><span class="label-title">Lifespan</span><span class="label-caption">Life expectancy of shredder</span><span class="unit">months</span></label><input type="text" name="shredderlife" value="48" />
<label for="sundry"><span class="label-title">Sundry</span><span class="label-caption">Monthly cost of bags, oil, sharpening, etc.</span></label><input type="text" name="sundry" class="price" value="$4.73" />
<label for="recycling"><span class="label-title">Recycling</span><span class="label-caption">Recycling cost (assume $7 per large bag)</span></label><input type="text" name="recycling" class="price" value="$0.00" />
</form>
<div id="calcoutput">
Labor costs $<span class="output" id="laborcosts"></span>/m<br>
Machine costs $<span class="output" id="machinecosts"></span>/m<br>
Machine depreciation $<span class="output" id="depreciation"></span>/m<br>
Time spent shredding <span class="output" id="totaltime"></span> hr/m<br>
(does not include maintenance)
<div id="totalbox">Your total shredding costs per month<div class="output" id="totalcost"></div></div>
</div>
</div>';
		echo $args['after_widget'];
	}
	function register(){
		register_sidebar_widget('Shred Calc', array('shredcalc', 'widget'));
		register_widget_control('Shred Calc', array('shredcalc', 'control'));
	}
}
?>
