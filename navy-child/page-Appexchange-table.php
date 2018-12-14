<?php
/**
 * Template Name: 	Appexchange Table Page
 * Navy Theme:		Appexchange Table Page
 * @package:		WordPress
 * @subpackage:		Navy Theme
 * @version:		1.0
 * @since:			1.0
 */
 
 	// Include framework options
 	$hgr_options = get_option( 'redux_options' );
 
	get_header('appexchange');
 ?>

      <div style="margin-top:100px;">
        <?php //the_content(); ?>
        
        <?php
	      global $wpdb;
	      $results = $wpdb->get_results( "SELECT * FROM wp_appexchange_leads"); // Query to fetch data from database table and storing in $results
	      if(!empty($results))                        // Checking if $results have some values or not
	      {    
		  echo "<table width='100%' border='1'>";// Adding <table> and <tbody> tag outside foreach loop so that it wont create again and again
		  echo "<tbody>";      
		  echo "<tr>";
		  echo "<th>ID</th>";
		  echo "<th>First Name</th>";
		  echo "<th>Last Name</th>";
		  echo "<th>Email</th>";
		  echo "<th>Mobile</th>";
		  echo "<th>Title</th>";
		  echo "<th>Job Function</th>";
		  echo "<th>Company</th>";
		  echo "<th>No of Emp</th>";
		  echo "<th>Country</th>";
		  echo "<th>State</th>";
		  echo "<th>Use Case</th>";
		  echo "<th>Termstime</th>";
		  echo "<th>Consent</th>";
		  echo "<th>Consent Time</th>";
		  echo "<th>Created Time</th>";
		  echo "<th>Mkto Submitted</th>";		     
		  echo "</tr>";
		  foreach($results as $row){   
			echo "<tr>";                           
			echo "<td>" . $row->id . "</td>";
			echo "<td>" . $row->fname . "</td>";
			echo "<td>" . $row->lname . "</td>";
			echo "<td>" . $row->email . "</td>";
			echo "<td>" . $row->mobile . "</td>";
			echo "<td>" . $row->title . "</td>";
			echo "<td>" . $row->jobfunction . "</td>";
			echo "<td>" . $row->company . "</td>";
			echo "<td>" . $row->numofemployees . "</td>";
			echo "<td>" . $row->country . "</td>";
			echo "<td>" . $row->state . "</td>";
			echo "<td>" . $row->usecase . "</td>";
			echo "<td>" . $row->termstime . "</td>";
			echo "<td>" . $row->consent . "</td>";
			echo "<td>" . $row->consenttime . "</td>";
			echo "<td>" . $row->createdtime . "</td>";
			echo "<td>" . $row->mktosubmitted . "</td>";
			echo "</tr>";		 
		  }
		  echo "</tbody>";
		  echo "</table>"; 

	      }
	?>
        
       </div>        
 

<?php 
 	get_footer('appexchange');
 ?>
