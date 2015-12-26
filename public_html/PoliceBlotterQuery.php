<html>
    <head>
        <title>Police Blotter Query Test</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="icon" href="img/myicon.png">
        <script src="js/sorttable.js"></script>
        <style type="text/css">/* Sortable tables */
            table.sortable thead {
                background-color:#eee;
                color:#666666;
                font-weight: bold;
                cursor: default;
            }</style>
    </head>
    <body>
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
        $SQL = "select incidentid,incidenttype,incidentnumber,incidentdate,incidenttime,address,zipcode,neighborhood,lat,lng,zone,age,gender,councildistrict from \"PoliceBlotter2\".incident ";
        $ORDERBY = " order by incidentnumber;";
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
            foreach ($values as $value) {

                echo "<td>$value</td>";
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
        ?>
        <br>

    </body>
</html>
