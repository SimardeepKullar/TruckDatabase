<!DOCTYPE html>
<html style="background-image: url('background.png');">
    <head>
        <meta charset="UTF-8" />
        <link rel="icon" type="image/x-icon" href="https://www.ryerson.ca/tmu_favicon.ico">
        <link href="styles.css" rel="stylesheet" type="text/css">
        <title>CPS 510 Assignment 9</title>
    </head>

    <header>
        <h1 class="center" style="margin-bottom: 5px">
            Truck Company Database Management System
        </h1>
        <h2 class="center" style="margin: 5px">
            Web UI mySQL
        </h2>
        <h4 class="center" style="margin: 5px">
            By: Simardeep, Jaskeerat, Amit
        </h4>
        <hr size="10" class="divider">
    </header>
    <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        // Create connection to Oracle
        //$conn = oci_connect('username', 'password',
        //'localhost');
        if (!$conn) {
            $m = oci_error();
            echo $m['message'];
        } else {
            echo "<p>Successfully connected with oracle database</p>";
        }
    ?>
    <body>
        <?php
            // Drop Tables ---------------------------------------------------------------------
            if (isset($_POST['button1'])) {
                echo "<p>";
                $drop = 'DROP TABLE loads CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table loads dropped<br>";
                }

                $drop = 'DROP TABLE driver CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table driver dropped<br>";
                }

                $drop = 'DROP TABLE trailer CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table trailer dropped<br>";
                }

                $drop = 'DROP TABLE truck CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table truck dropped<br>";
                }

                $drop = 'DROP TABLE supplier CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table supplier dropped<br>";
                }

                $drop = 'DROP TABLE employee CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table employee dropped<br>";
                }
                echo "</p>";
            }

            // Create Tables -------------------------------------------------------------------
            if (isset($_POST['button2'])) {    
                echo "<p>";
                $create = 'CREATE TABLE employee (
                    employee_id         NUMBER,
                    first_name          VARCHAR2(30 CHAR) NOT NULL,
                    last_name           VARCHAR2(30 CHAR) NOT NULL,
                    dob                 DATE NOT NULL,
                    phone               VARCHAR2(12 CHAR),
                    email               VARCHAR2(30 CHAR),
                    CONSTRAINT employee_pk PRIMARY KEY (employee_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table employee created<br>";
                }

                $create = 'CREATE TABLE supplier (
                    supplier_id         VARCHAR2(5 CHAR),
                    supplier_name       VARCHAR2(50 CHAR) NOT NULL,
                    unit_number         NUMBER NOT NULL,
                    street              VARCHAR2(50 CHAR) NOT NULL,
                    city                VARCHAR2(20 CHAR) NOT NULL,
                    stateOrProvince     VARCHAR2(25 CHAR) NOT NULL,
                    country             VARCHAR2(20 CHAR) NOT NULL,
                    phone               VARCHAR2(12 CHAR),
                    email               VARCHAR2(30 CHAR),
                    employee_id         NUMBER,
                    CONSTRAINT supplier_emp_fk FOREIGN KEY (employee_id) REFERENCES employee (employee_id),
                    CONSTRAINT supplier_pk PRIMARY KEY (supplier_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table supplier created<br>";
                }

                $create = 'CREATE TABLE truck (
                    truck_id            NUMBER,
                    manufacturer        VARCHAR2(25 CHAR) NOT NULL,
                    transmission        VARCHAR2(15 CHAR) NOT NULL,
                    license_plate       VARCHAR2(7 CHAR) NOT NULL,
                    supplier_id         VARCHAR2(5 CHAR),
                    employee_id         NUMBER,
                    CONSTRAINT truck_supplier_fk FOREIGN KEY (supplier_id) REFERENCES supplier (supplier_id),
                    CONSTRAINT truck_emp_fk FOREIGN KEY (employee_id) REFERENCES employee (employee_id),
                    CONSTRAINT truck_pk PRIMARY KEY (truck_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table truck created<br>";
                }

                $create = "CREATE TABLE trailer (
                    trailer_id              NUMBER,
                    trailer_size            NUMBER NOT NULL,
                    license_plate           VARCHAR2(7 CHAR) NOT NULL,
                    supplier_id             VARCHAR2(5 CHAR),
                    employee_id             NUMBER,
                    truck_id                NUMBER,
                    typeoftrailer           VARCHAR2(7 CHAR),
                        check (typeoftrailer in ('Reefer', 'Dry Van')),
                    CONSTRAINT trailer_truck_fk FOREIGN KEY (truck_id) REFERENCES truck (truck_id),
                    CONSTRAINT trailer_supplier_fk FOREIGN KEY (supplier_id) REFERENCES supplier (supplier_id),
                    CONSTRAINT trailer_emp_fk FOREIGN KEY (employee_id) REFERENCES employee (employee_id),
                    CONSTRAINT trailer_pk PRIMARY KEY (trailer_id)
                )";
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table trailer created<br>";
                }

                $create = 'CREATE TABLE driver (
                    driver_id           NUMBER,
                    first_name          VARCHAR2(30 CHAR) NOT NULL,
                    last_name           VARCHAR2(30 CHAR) NOT NULL,
                    dob                 DATE NOT NULL,
                    unit_number         NUMBER NOT NULL,
                    apart_number        NUMBER,
                    street              VARCHAR2(50 CHAR) NOT NULL,
                    city                VARCHAR2(20 CHAR) NOT NULL,
                    province            VARCHAR2(20 CHAR) NOT NULL,
                    postal_code         VARCHAR2(7 CHAR) NOT NULL,
                    license_plate       VARCHAR2(20 CHAR) NOT NULL,
                    phone               VARCHAR2(12 CHAR),
                    driver              VARCHAR2(30 CHAR),
                    truck_id		    NUMBER,
                    supplier_id 		VARCHAR2(5 CHAR),
                    employee_id         NUMBER,
                    CONSTRAINT driver_truck_fk FOREIGN KEY (truck_id) REFERENCES truck (truck_id),
                    CONSTRAINT driver_supplier_fk FOREIGN KEY (supplier_id) REFERENCES supplier (supplier_id),
                    CONSTRAINT driver_emp_fk FOREIGN KEY (employee_id) REFERENCES employee (employee_id),
                    CONSTRAINT driver_pk PRIMARY KEY (driver_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table driver created<br>";
                }

                $create = "CREATE TABLE loads (
                    load_id             VARCHAR2(20 CHAR),
                    supplier_id         VARCHAR2(20 CHAR),
                    employee_id	        NUMBER,
                    delivery_date       DATE NOT NULL,
                    destination         VARCHAR2(20 CHAR) NOT NULL,
                    pay_rate            NUMBER NOT NULL,
                    typeofload          VARCHAR2(10 CHAR),
                        check (typeofload in ('Short', 'Medium', 'Long')),
                    typeofhaulage       VARCHAR2(15 CHAR),
                        check (typeofhaulage in ('Food', 'Dry Material')),
                    CONSTRAINT load_suppiler_fk FOREIGN KEY (supplier_id) REFERENCES supplier (supplier_id),
                    CONSTRAINT load_emp_fk FOREIGN KEY (employee_id) REFERENCES employee (employee_id),
                    CONSTRAINT load_pk PRIMARY KEY (load_id)
                )";
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table loads created<br>";
                }
                echo "</p>";
            }

            // Populate Tables -----------------------------------------------------------------
            if (isset($_POST['button3'])) {     
                echo "<p>";
                $employeeArray = ["INSERT INTO employee VALUES(8529, 'Parmeet', 'Sidhu', DATE '1992-1-12', '647-232-5452', 'parmeet.sidhu@gmail.com')",
                "INSERT INTO employee VALUES(4311, 'Jasmeet', 'Singh', DATE '1990-4-3', '647-062-0524', 'jasmeet.singh@gmail.com')",
                "INSERT INTO employee VALUES(7362, 'Joginder', 'Bassi', DATE '1971-4-16', '647-267-8432', 'joginder.jb@gmail.com')",
                "INSERT INTO employee VALUES(2542, 'Navraj', 'Goraya', DATE '1989-11-3', '647-022-5111', 'navraj.xo@gmail.com')",
                "INSERT INTO employee VALUES(2426, 'Jaskarn', 'Singh', DATE '1990-10-20', '647-025-4141', 'jaskarn.singh@gmail.com')"];
                foreach ($employeeArray as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "Populated employee table<br>";

                $supplierArray = ["INSERT INTO supplier VALUES('CHR', 'CH Robinson', 6155, 'Tomken Rd.', 'Mississauga', 'Ontario', 'Canada', '905-672-2427', 'dispatch@chrobinson.ca', 8529)",
                "INSERT INTO supplier VALUES('TRP', 'Transplace', 1185, 'North Service Rd.', 'Oakville', 'Ontario', 'Canada', '800-387-7108', 'dispatch@transplace.ca', 4311)",
                "INSERT INTO supplier VALUES('WAL', 'Walmart', 702, 'S.W. 8th', 'St. Bentonville', 'Arkansas', 'USA', '905-451-6307', 'supply@walmart.ca', 7362)",
                "INSERT INTO supplier VALUES('COS', 'Costco', 1801, '10th Ave NW', 'Issaquah', 'Washinton', 'USA', '905-450-9300', 'cos@costco.com', 2542)",
                "INSERT INTO supplier VALUES('AMZ', 'Amazon', 440, 'Terry Ave N', 'Seattle', 'Washington', 'USA', '888-280-4331', 'amz@amazon.com', 2426)"];
                foreach ($supplierArray as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "Populated supplier table<br>";

                $truckArray = ["INSERT INTO truck VALUES(2024, 'Kenworth', 'Manual', 'JSC 016', 'CHR', 8529)",
                "INSERT INTO truck VALUES(5075, 'Peterbilt', 'Manual', 'VX3 901', 'TRP', 4311)",
                "INSERT INTO truck VALUES(8788, 'Volvo', 'Automatic', 'VVN 760', 'WAL', 7362)",
                "INSERT INTO truck VALUES(2055, 'Freightliner', 'Automatic', 'FXX 055', 'COS', 2542)",
                "INSERT INTO truck VALUES(0178, 'Kenworth', 'Manual', 'MAA 782', 'AMZ', 2426)"];
                foreach ($truckArray as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "Populated truck table<br>";

                $trailerArray = ["INSERT INTO trailer VALUES(8044, 53, 'IKT 111', 'CHR', 8529, 2024, 'Reefer')",
                "INSERT INTO trailer VALUES(9323, 48, 'DAT 901', 'TRP', 4311, 5075, 'Reefer')",
                "INSERT INTO trailer VALUES(2167, 53, 'BOI 342', 'WAL', 7362, 8788, 'Dry Van')",
                "INSERT INTO trailer VALUES(8203, 48, 'SUS 999', 'COS', 2542, 2055, 'Dry Van')",
                "INSERT INTO trailer VALUES(6573, 48, 'LOL 348', 'AMZ', 2426, 0178, 'Dry Van')"];
                foreach ($trailerArray as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "Populated trailer table<br>";

                $driverArray = ["INSERT INTO driver VALUES(295, 'Shubhdeep', 'Sidhu', DATE '1993-6-11', 645, NULL, 'Islington Avenue', 'Rexdale', 'Ontario', 'M6F 4N6', 'S7360-04841-55443', '226-778-8524', 'sidhuMoosewala@hotmail.com', 8788, 'WAL', 7362)",
                "INSERT INTO driver VALUES(678, 'Amritpal', 'Dhillon', DATE '1993-1-10', 5323, NULL, 'Ray Lawson', 'Malton', 'Ontario', 'M7Y6R3', 'D8954-48566-40344', '905-750-2035','apdillon@hotmail.com', 2024, 'CHR', 8529)",
                "INSERT INTO driver VALUES(043, 'Karan', 'Aujla', DATE '1997-1-18', 351, NULL, 'Financial Drive', 'Brampton', 'Ontario', 'L6Y 0P9', 'A1456-16165-16552', '416-350-4664', NULL, 5075, 'TRP', 4311)",
                "INSERT INTO driver VALUES(543, 'Anmol', 'Bains', DATE '1970-4-4', 765, NULL, 'Copernicus Dr', 'Mississauga', 'Ontario', 'M2U 2K3', 'B1608-96367-11479', '780-269-3595', NULL, 0178, 'AMZ', 2426)",
                "INSERT INTO driver VALUES(234, 'Sukhman', 'Nagra', DATE '1980-6-6', 325, NULL, 'Olivia Mary', 'Brampton', 'Ontario', 'M9Y 2R7', 'N7858-91669-07402', NULL,'sukhnagra@hotmail.com', 2055, 'COS', 2542)"];
                foreach ($driverArray as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "Populated driver table<br>";

                $loadsArray = ["INSERT INTO loads VALUES('CYZ820', 'CHR', 8529, DATE '2022-10-10', 'Flint', 20, 'Short', 'Food')",
                "INSERT INTO loads VALUES('OJH321', 'TRP', 4311, DATE '2022-11-7', 'Texas', 30, 'Long', 'Dry Material')",
                "INSERT INTO loads VALUES('LUP591', 'WAL', 7362, DATE '2022-10-8', 'Sarnia', 23, 'Short', 'Food')",
                "INSERT INTO loads VALUES('VTQ820', 'COS', 2542, DATE '2022-11-17', 'New Mexico', 35, 'Long','Dry Material')",
                "INSERT INTO loads VALUES('JND081', 'AMZ', 2426, DATE '2022-10-20', 'New York', 26, 'Medium', 'Food')"];
                foreach ($loadsArray as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "Populated loads table<br>";

                echo "</p>";
            }
            // Query Tables --------------------------------------------------------------------
            if (isset($_POST['button4'])) {     
                echo "<p>";
                $query = 'Select * From employee';
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                        foreach ($row as $item) {
                            echo $item," ";
                        }
                        echo "<br/>";
                    }
                }
                echo "<br>";

                $query = 'Select * From supplier';
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                        foreach ($row as $item) {
                            echo $item," ";
                        }
                        echo "<br/>";
                    }
                }
                echo "<br>";

                $query = 'Select * From truck';
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                        foreach ($row as $item) {
                            echo $item," ";
                        }
                        echo "<br/>";
                    }
                }
                echo "<br>";

                $query = 'Select * From trailer';
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                        foreach ($row as $item) {
                            echo $item," ";
                        }
                        echo "<br/>";
                    }
                }
                echo "<br>";

                $query = 'Select * From driver';
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                        foreach ($row as $item) {
                            echo $item," ";
                        }
                        echo "<br/>";
                    }
                }
                echo "<br>";

                $query = 'Select * From loads';
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                        foreach ($row as $item) {
                            echo $item," ";
                        }
                        echo "<br/>";
                    }
                }
                echo "</p>";
            }
            // Interact with DBMS --------------------------------------------------------------
            if (isset($_POST['button5'])) {
                $sqlcommand = $_POST['sqlCommand'];
                $stid = oci_parse($conn, $sqlcommand);
                $usecommand = oci_execute($stid);
                if($usecommand){
                    echo "<strong>$sqlcommand</strong>: has been executed.<br>";
                } else {
                    echo "<strong>$sqlcommand</strong>: is invalid.<br>";
                }
                echo "<br>";
            }
            if (isset($_POST['button6'])) {
                $sqlquery = $_POST['sqlQuery'];
                $stid = oci_parse($conn, $sqlquery);
                $usequery = oci_execute($stid);
                if($usequery){
                    echo "<strong>$sqlquery</strong>: has been executed. See result below.<br>";
                    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                        foreach ($row as $item) {
                            echo $item," ";
                        }
                        echo "<br/>";
                    }
                } else {
                    echo "<strong>$sqlquery</strong>: is invalid.<br>";
                }
                echo "<br>";
            }   
        ?>
    </body>
    <img src="https://images.unsplash.com/photo-1591768793355-74d04bb6608f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1172&q=80" 
        style="width: 500px; height:auto; border: 5px solid #0095ff; position: absolute; right: 10px; top: 135px;">

    <form method="post"> 
        <input type="submit" name="button1" value="Drop Tables"/> 
        <input type="submit" name="button2" value="Create Tables"/>
        <input type="submit" name="button3" value="Populate Tables"/>
        <input type="submit" name="button4" value="Query Tables"/><br><br>
        To interact with the database use the input box below.<br>
        Enter an SQL command: <input type="text" name="sqlCommand" placeholder="SQL Command..."/>
        <input type="submit" name="button5" value="Use Command"/><br><br>
        To query the database use the input box below.<br>
        Enter an SQL query: <input type="text" name="sqlQuery" placeholder="SQL Query..."/>
        <input type="submit" name="button6" value="Query"/>
    </form>
</html>