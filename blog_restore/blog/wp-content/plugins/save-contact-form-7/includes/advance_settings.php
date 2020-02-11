<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<h3 align='center'>Contact Form DB Advance Settings</h3>
<div>
    <div>
        <select name="nimble_settings_formnames" id="nimble_settings_formnames" >
            <option selected="selected" disabled="disabled">Select a form</option>
            <?php
            $nimble_cf7_names = nimble_get_cf7_name();
            foreach ($nimble_cf7_names as $nimble_cf_name) {
                echo "<option value='" . $nimble_cf_name['ID'] . "'>" . strtoupper($nimble_cf_name['post_name']) . "</option>";
            }
            ?>
        </select><br><br><br>
    </div>
    <div id="nimble_settings_wrapper"><h4 align="center">Please Select a Form You Have Submitted To View Its Options........!</h4></div>
</div>  

<?php
//function to create seperate admin menu page for plugin
//Action call to Populate Advance Setting Options


?>

