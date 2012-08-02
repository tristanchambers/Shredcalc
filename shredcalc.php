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
	wp_enqueue_script('jquery');

	wp_register_script('shredcalc', plugins_url('shredcalc.js', __FILE__));
	wp_enqueue_script('shredcalc');

	wp_register_style('shredcalc', plugins_url('shredcalc.css', __FILE__));
	wp_enqueue_style('shredcalc');
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
