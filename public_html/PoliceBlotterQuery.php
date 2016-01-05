<html>
    <head>
        <title>Police Blotter Query Test</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link href="css/jquery-ui.css" rel="stylesheet">
        <link rel="icon" href="img/myicon.png">
        <script src="js/sorttable.js"></script>
        <style type="text/css">/* Sortable tables */
            table.sortable thead {
                background-color:#eee;
                color:#666666;
                font-weight: bold;
                cursor: default;
            }</style>
	<style> /* For Dialog. To be tweaked */
	body{
		font: 62.5% "Trebuchet MS", sans-serif;
	}
	.demoHeaders {
		margin-top: 2em;
	}
	.dialog-link {
		padding: .4em 1em .4em 20px;
		text-decoration: none;
		position: relative;
	}
	.dialog-link span.ui-icon {
		margin: 0 5px 0 0;
		position: absolute;
		left: .2em;
		top: 50%;
		margin-top: -8px;
	}
	#icons {
		margin: 0;
		padding: 0;
	}
	#icons li {
		margin: 2px;
		position: relative;
		padding: 4px 0;
		cursor: pointer;
		float: left;
		list-style: none;
	}
	#icons span.ui-icon {
		float: left;
		margin: 0 4px;
	}
	.fakewindowcontain .ui-widget-overlay {
		position: absolute;
	}
	select {
		width: 200px;
	}
	</style>
    </head>
    <body>
		<!-- ui-dialog -->
        <?php
        include ('incidentDetails.php');
		?>

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script>

$( "#dialog" ).dialog({
	autoOpen: false,
	width: 400,
	modal: false,
	buttons: [
		{
			text: "Ok",
			click: function() {
				$( this ).dialog( "close" );
			}
		}
	]
});

// Link to open the dialog
$( ".dialog-link" ).click(function( event ) {
	$( "#dialog" ).dialog( "open" );
	event.preventDefault();
});
</script>

        <?php
        include ('connect.php');
        $timezone = "America/New_York";
        $incidentnumber;
        date_default_timezone_set($timezone);
        //$dbconn = pg_connect('host=cfapghpoliceblotter.cnsbqqmktili.us-east-1.rds.amazonaws.com port=5432 dbname=CfAPGHPoliceBlotter user=CfAPGHPoliceBltr password=CfAPGH2015 connect_timeout=60');
        $dbconn = pg_connect('host=' . $hostname . ' port=' . $port . ' dbname=' . $database . ' user=' . $username . ' password=' . $password . ' connect_timeout=' . $connect_timeout);
//$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=win95sux");
        /*
         * select incidentid,incidenttype,incidentnumber,incidentdate,incidenttime,address,zipcode,neighborhood,lat,lng,zone,age,gender,councildistrict
          from "PoliceBlotter2".incident
          where incidentnumber in
          (select incidentnumber
          from "PoliceBlotter2".incidentdescription
          where descriptionid in (
          select descriptionid
          from "PoliceBlotter2".description
          where description like ('%Homicide%')))
          order by incidentnumber;
         */
        // Warning: Added Limit 20 for Ease of testing to Demo jQuery UI Dialog
        $SQL = "select incidentid,incidenttype,incidentnumber,incidentdate,incidenttime,address,zipcode,neighborhood,lat,lng,zone,age,gender,councildistrict from \"PoliceBlotter2\".incident ";
        $ORDERBY = " order by incidentnumber limit 20;";
        if (isset($_POST["query"])) {
            $where = 'where ' . $_POST["query"];
        } else {
            $where = "";
        }
        //echo $where;
        $SQL = $SQL . $where . $ORDERBY;
        echo $SQL;
        $result = pg_query($dbconn, $SQL);
        BeginIncidentTable();
        $count = 1;
        while ($row = pg_fetch_row($result)) {
            $incidentnumber = $row[2];
            //Descriptiondata();
            //print $row[0] . ' ' . $row[1] . ' '.$row[2] . '</br>'; 
            PopulateIncidentTable($row);
            $count++;
        }

        EndIncidentTable();

        function BeginIncidentTable() {
            /* Display the beginning of the search results table. */
            $headings = array("count", "incidentid", "incidenttype", "incidentnumber", "incidentdate", "incidenttime", "address", "zipcode", "neighborhood", "lat", "lng", "zone", "age", "gender", "councildistrict");
            echo "<table class='sortable' align='center' cellpadding='5' border=1>";
            echo "<tr>";
            foreach ($headings as $heading) {
                echo "<th>$heading</th>";
            }
            echo "</tr>";
        }

        function PopulateIncidentTable($values) {
            global $count;
            /* Populate table with results. */
            echo "<tr>";
            echo "<td>$count</td>";
            $i = 0;
            foreach ($values as $value) {
              $i++;
              if ($i == 3) {
                // Warning: for demo purposes only - sending values as paramters. Will showupin URL. which will be used in dialog
                // Warning: this is make DB call and re-create the table each time. For Demo puposes only
                echo "<td><a class='dialog-link ui-state-default ui-corner-all' href='PoliceBlotterQuery.php?currentId=$value&incidentid=$values[0]&incidentdate=$values[3]&incidenttime=$values[4]&incidenttype=$values[1]&address=$values[5]&zipcode=$values[6]&neighborhood=$values[7]&age=$values[11]&gender=$values[12]'><span class='ui-icon ui-icon-newwin'></span>$value</a></td>";
              } else {
                echo "<td>$value</td>";
              }
            }
            echo "</tr>";
        }

        function EndIncidentTable() {
            echo "</table><br>";
        }

        function DescriptionData() {
            global $incidentnumber;
            $sql2 = 'select distinct i.incidentnumber,i.incidenttype,i.incidentdate,incidenttime,i.age,i.gender,d.section,d.description from "PoliceBlotter2".description d, "PoliceBlotter2".incident i where descriptionid in (select distinct descriptionid from "PoliceBlotter2".incidentdescription where incidentnumber in (' . $incidentnumber . ')) and incidentnumber in (' . $incidentnumber . ') order by i.incidentnumber';
            echo $sql2;
            echo "<br>";
        }
        if (isset($_GET["currentId"])) {
            echo "<script>$( '#dialog' ).dialog('open');</script>";
        }
        ?>
        <br>

    </body>
</html>
