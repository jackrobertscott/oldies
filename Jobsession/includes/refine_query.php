<form method="GET" id="ref_query_sub" action="search.php">
<div class="ref_search">
    <div class="ref_title">
      	<p><span class="icon-rocket"></span> Search</p>
    </div>
    <span class="toggle_search">
		<div class="search_holder" style="border-top: 1px solid #d6d6d6;">
			<input type="text" placeholder="Key Word Search" name="search_query" id="s" value="<?php if(!empty($_GET['search_query'])){echo $_GET['search_query'];} ?>"/>
		</div><!-- search_holder -->
		<div class="ref_subcat">
		  <p><?php if($_GET['gen_cat_form'] != 'null' && !empty($_GET['gen_cat_form'])){ echo '<span class="icon-checkmark"></span>';} ?>Category</p>
		  <div class="ref_option_toggle over_cat_click">
		  	<p class="icon-list" title="hide/show"></p>
		  </div>
		</div>
		<div class="ref_overflow over_cat">
		  <ul>
		  	<?php if($_GET['gen_cat_form'] != 'null' && !empty($_GET['gen_cat_form'])){ echo "<li class='ref_lime_select'><p>" . $_GET['gen_cat_form'] . "</p></li>";} ?>
			<li><p>Accounting</p></li>
			<li><p>Administration</p></li>
			<li><p>Advertising, Arts and Media</p></li>
			<li><p>Banking and Financial Services</p></li>
			<li><p>Call Centre and Customer Service</p></li>
			<li><p>CEO and General Management</p></li>
			<li><p>Community Services and Development</p></li>
			<li><p>Construction</p></li>
			<li><p>Consulting and Strategy</p></li>
			<li><p>Design and Architecture</p></li>
			<li><p>Education and Training</p></li>
			<li><p>Engineering</p></li>
			<li><p>Farming, Animals and Conservation</p></li>
			<li><p>Government and Defence</p></li>
			<li><p>Healthcare and Medical</p></li>
			<li><p>Hospitality and Tourism</p></li>
			<li><p>Human Resources and Recruitment</p></li>
			<li><p>Information and Communication Technology</p></li>
			<li><p>Insurance and Superannuation</p></li>
			<li><p>Legal</p></li>
			<li><p>Manufacturing, Transportation and Logistics</p></li>
			<li><p>Marketing and Communications</p></li>
			<li><p>Mining, Resources and Energy</p></li>
			<li><p>Real Estate and Property</p></li>
			<li><p>Retail and Consumer Products</p></li>
			<li><p>Sales</p></li>
			<li><p>Science and Technology</p></li>
			<li><p>Self Employment</p></li>
			<li><p>Sport and Recreation</p></li>
			<li><p>Trades and Services</p></li>
		  </ul>
		</div>
		<div class="ref_subcat">
		<p><?php if($_GET['gen_loc_form'] != 'null' && !empty($_GET['gen_loc_form'])){ echo '<span class="icon-checkmark"></span>';} ?>Location</p>
		<div class="ref_option_toggle over_loc_click">
			<p class="icon-list" title="hide/show"></p>
		</div>
		</div>
		<div class="ref_overflow over_loc">
		  <ul>
		  	<?php if($_GET['gen_loc_form'] != 'null' && !empty($_GET['gen_loc_form'])){ echo "<li class='ref_lime_select'><p>" . $_GET['gen_loc_form'] . "</p></li>";} ?>
			<li><p>Australian Capital Territory</p></li>
			<li><p>Nothern Territory</p></li>
			<li><p>New South Wales</p></li>
			<li><p>Queensland</p></li>
			<li><p>South Australia</p></li>
			<li><p>Tasmania</p></li>
			<li><p>Western Australia</p></li>
			<li><p>Victoria</p></li>
		  </ul>
		</div>
		<div class="ref_subcat">
		  <p><?php if($_GET['gen_sal_form'] != 'null' && !empty($_GET['gen_sal_form'])){ echo '<span class="icon-checkmark"></span>';} ?>Type</p>
		  <div class="ref_option_toggle over_sal_click">
		  	<p class="icon-list" title="hide/show"></p>
		  </div>
		</div>
		<div class="ref_overflow over_sal">
		  <ul>
		  	<?php if($_GET['gen_sal_form'] != 'null' && !empty($_GET['gen_sal_form'])){ echo "<li class='ref_lime_select'><p>" . $_GET['gen_sal_form'] . "</p></li>";} ?>
			<li><p>To Be Discussed</p></li>
			<li><p>Full Time</p></li>
			<li><p>Part Time</p></li>
			<li><p>Casual</p></li>
			<li><p>Contract</p></li>
			<li><p>Temporary</p></li>
		  </ul>
		</div>
	  
	  <!-- Need to make it so that when the user closes ref_overflow, it removes the value of that from the search -->
	  
	  	<input type="hidden" name="gen_loc_form" value="<?php if(!empty($_GET['gen_loc_form'])){ echo $_GET['gen_loc_form']; }else{ echo 'null'; } ?>">
	  	<input type="hidden" name="gen_sal_form" value="<?php if(!empty($_GET['gen_sal_form'])){ echo $_GET['gen_sal_form']; }else{ echo 'null'; } ?>">
	  	<input type="hidden" name="gen_cat_form" value="<?php if(!empty($_GET['gen_cat_form'])){ echo $_GET['gen_cat_form']; }else{ echo 'null'; } ?>">
	  	<div class="ref_submit" onClick="document.forms['ref_query_sub'].submit();">
	    	<p>Submit</p>
	  	</div>
  	</span>
</div>
</form>