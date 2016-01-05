		<!-- ui-dialog -->
<div id="dialog" title="Incident Details">
  <p>
  <?php
	  // Warning: for demo purposes only. Using paramter values in the dialog. May need to make make SQL query here
	 if (isset($_GET["currentId"])) {
         echo "<table align='center' cellpadding='5' border=0>";
 		 echo "<tr><td>Incident Id</td><td>" . $_GET['incidentid'] . "</td></tr>";
 		 echo "<tr><td>Incident Number</td><td>" . $_GET['currentId'] . "</td></tr>";
 		 echo "<tr><td>Incident Type</td><td>" . $_GET['incidenttype'] . "</td></tr>";
 		 echo "<tr><td>Incident Date/Time</td><td>" . $_GET['incidentdate'] . " " . $_GET['incidenttime'] . "</td></tr>";

 		 echo "<tr><td>Neighborhood</td><td>" . $_GET['neighborhood'] . "</td></tr>";
 		 echo "<tr><td>Address</td><td>" . $_GET['address'] . " " . $_GET['zipcode'] . "</td></tr>";
 		 echo "<tr><td>Age/Gender</td><td>" . $_GET['age'] . " / " . $_GET['gender'] . "</td></tr>";
         echo "</table>";
	 }
  ?>
  </p>	
</div>
