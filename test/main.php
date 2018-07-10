<script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_id='+val,
	success: function(data){
		$("#state-list").html(data);
	}
	});
}
</script>

<div class="frmDronpDown">
	<div class="row">
		<label>Country:</label><br/>
		<select name="country" id="country-list" class="demoInputBox" onChange="getState(this.value);">
		<option value="">Select Country</option>
		<?php
		foreach($results as $country) {
		?>
		<option value="<?php echo $country["id"]; ?>"><?php echo $country["name"]; ?></option>
		<?php
		}
		?>
		</select>
	</div>
	<div class="row">
		<label>State:</label><br/>
		<select name="state" id="state-list" class="demoInputBox">
		<option value="">Select State</option>
		</select>
	</div>
</div>
