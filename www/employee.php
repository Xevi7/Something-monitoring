<?php
    session_start();
    $branchSelect = NULL;
    if(isset($_SESSION["branchPass"])){
        $branchSelect = $_SESSION["branchPass"];
    }
    $user = $_SESSION["userPass"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>employee</title>
    <link rel="stylesheet" href="./css/style.css?v=<?php echo time(); ?>">
    <script src="./javascript/jquery.js"></script>
    <script defer src="./javascript/script.js"></script>
</head>
<body>
    <div id="header">
        <div>
            <div>
                <a href="./home.php">
                    <img src="./assets/home 1.png" alt="no img">
                    <h2>HOME</h2>
                </a>
            </div>
            <hr>
            <div>
                <h2>Something's Monitoring</h2>
            </div>
        </div>
        <div>
            <form action="something-monitoring.php"><button>Log out</button></form>
            <img src="./assets/person 1.png" alt="no img">
        </div>
    </div>

    <div id="body_employee">
        <?php
        $path = str_replace('www','',getcwd());
        system('"'.$path.'mysql\bin\mysqld.exe"');
        $con = mysqli_connect("localhost","root","","somethingmonitoring");
        $sql = "SELECT * FROM branch";
        $result = $con->query($sql);

        echo "<div id='branchList'> <form method='POST' action='home.php'>";
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo "<button type='submit' class='branchName' name='branchIdSelected' value='" . $row["branchId"] . "'>" . $row["branchName"] . "</button>";
            }
        }
        echo "</form> </div>";
        $styleBlock = sprintf('
            <style type="text/css">
               .branchName:nth-child('.$branchSelect.') {
                    background-color:%s
                }
            </style>
            ', '#103d49');
        echo $styleBlock;
        ?>
            <div>
                <div class='tabHeader'>
                <h2>Employee</h2>
                <div>
                    <form method='POST'>
                        <input type='text' name='search' placeholder='search employee' value='<?php if(isset($_POST['search'])){echo $_POST['search'];} ?>'>
                    </form>
                    <button onclick='displayAddList("employee")'><img src='./assets/plus 1.png' alt='no img'></button>
                </div>
            </div>
            <div> 
            <form method='POST'>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>ID</th>
                        <th>DOB</th>
                        <th>Address</th>
                        <th></th>
                        </tr>      
        <?php
        if(isset($_POST["search"]) && $_POST["search"] != ""){
            $keyword = $_POST["search"];
            $sql = "SELECT * FROM `employee` WHERE branchId ='".$branchSelect."' AND (employeeId LIKE '%".$keyword."%' OR employeeName LIKE '%".$keyword."%' OR employeePosition LIKE '%".$keyword."%' OR employeeAddress LIKE '%".$keyword."%' OR employeeDOB LIKE '%".$keyword."%')";
            $result = $con->query($sql);
            if($result!= false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr> <td>" . $row["employeeName"] . "</td> <td>" . $row["employeePosition"] . "</td> <td>" . $row["employeeId"] . "</td> <td>" . $row["employeeDOB"] . "</td> <td>" . $row["employeeAddress"] . "</td> <td> <button type='submit' class='editEmployee' name='editEmp' value='".$row["employeeId"]."'><img src='./assets/edit.png' alt='no img'></button> <button type='submit' class='deleteEmployee' name='deleteEmp' value='".$row["employeeId"]."'><img src='./assets/bin 1.png' alt='no img'></button> </td>";
                }
                echo "</table>";
            }
            else{
                echo "</table> <h2 class='emptySearch'>No employees were found</h2>";
            }
        }
        else {
            $sql = "SELECT * FROM employee WHERE branchId =" . $branchSelect;
            $result = $con->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr> <td>" . $row["employeeName"] . "</td> <td>" . $row["employeePosition"] . "</td> <td>" . $row["employeeId"] . "</td> <td>" . $row["employeeDOB"] . "</td> <td>" . $row["employeeAddress"] . "</td> <td> <button type='submit' class='editEmployee' name='editEmp' value='".$row["employeeId"]."'><img src='./assets/edit.png' alt='no img'></button> <button type='submit' class='deleteEmployee' name='deleteEmp' value='".$row["employeeId"]."'><img src='./assets/bin 1.png' alt='no img'></button> </td>";
                }
            }
            echo "</table>";
        }

        echo    "</form> </div> </div>";


        if(isset($_POST["nama"])){
            $id = uniqueId();
            $sql = "INSERT INTO `employee`(`employeeId`, `branchId`, `employeeName`, `employeePosition`, `employeeDOB`, `employeeAddress`) VALUES ('".$id."','".$branchSelect."','".$_POST["nama"]."','".$_POST["posisi"]."','".$_POST["tanggal"]."','".$_POST["alamat"]."')";
            $con->query($sql);
            $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','added','Employee Tab')";
            $con->query($sql);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        
        function uniqueId(){
            global $branchSelect, $con, $result;
            $id = "AO" . $branchSelect;
            for($i = 0; $i < 4; $i++){
                $id .= rand(0,9);
            }
            $sql = "SELECT employeeId FROM employee WHERE branchId =" . $branchSelect . " AND employeeId ='" . $id . "'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                uniqueId();
            }
            $sql = "SELECT employeeId FROM deletedemployee WHERE branchId =" . $branchSelect . " AND employeeId ='" . $id . "'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                uniqueId();
            }
            else{
                return $id;
            }
        }

        if(isset($_POST['editEmp'])){
            echo "<script>
                    $('#body_employee').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.editList').css('filter', 'blur(0px)')
                </script>";
            echo "<div class='editList'>
                        <div>
                            <h2>Edit employee ".$_POST["editEmp"]."</h2>";
            $id = $_POST["editEmp"];
            $sql = "SELECT * FROM `employee` WHERE branchId ='".$branchSelect."' AND employeeId='".$id."'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                $row = $result->fetch_assoc();
                echo "<div> 
                        <form method='POST' name='editEmployeeForm'>
                            <div class='input-box'>
                                <span>Name</span>
                                <input type='text' name='editedName' value='".$row["employeeName"]."'>
                                <div id='errEditName'></div>
                            </div>
                            <div class='input-box'>
                                <span>Position</span>
                                <input type='text' name='editedPosition' value='".$row["employeePosition"]."'>
                                <div id='errEditPos'></div>
                            </div>
                            <div class='input-box'>
                                <span>Address</span>
                                <input type='text' name='editedAddress' value='".$row["employeeAddress"]."'>
                                <div id='errEditAddress'></div>
                            </div>
                            <div>
                                <button type='button' onclick='closeEditList(\"employee\")'>cancel</button>
                                <input type='hidden' name='editConfirmation' value='".$id."'>
                                <input type='button' value='submit' onclick='editEmployeevalidation()'>
                            </div>
                        </form>
                    </div>";
            }
        }

        if(isset($_POST["editConfirmation"])){
            $id = $_POST["editConfirmation"];
            if($id != "cancel"){
                echo "<script>
                    $('#body_employee').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.editSuccess').css('filter', 'blur(0px)')
                </script>";
                echo "<div class='editSuccess'>
                        <div>
                            <img src='./assets/checkmark.png' alt='no img'>
                            <h2>Employee Edited Successfully</h2>
                            <button onclick='editedList(\"employee\")'>OK</button>
                        </div>
                    </div>";
                $sql = "SELECT * FROM `employee` WHERE branchId ='".$branchSelect."' AND employeeId='".$id."'";
                $result = $con->query($sql);
                $row = $result->fetch_assoc();
                if($_POST["editedName"] != $row["employeeName"] || $_POST["editedPosition"] != $row["employeePosition"] || $_POST["editedAddress"] != $row["employeeAddress"]){
                    $sql = "UPDATE `employee` SET `employeeName`='".$_POST["editedName"]."',`employeePosition`='".$_POST["editedPosition"]."',`employeeAddress`='".$_POST["editedAddress"]."' WHERE branchId ='".$branchSelect."' AND employeeId ='".$id."'";
                    $con->query($sql);
                    $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','updated','Employee Tab')";
                    $con->query($sql);
                }
            }
        }

        if(isset($_POST["deleteEmp"])){
            echo "<script>
                    $('#body_employee').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.deleteList').css('filter', 'blur(0px)')
                </script>";
            echo    "<div class='deleteList'>
                        <div>
                            <h2>are you sure you want to move this data to trash bin?</h2>";
            $id = $_POST["deleteEmp"];
            $sql = "SELECT `employeeName`, `employeePosition`, `employeeId`, `employeeDOB`, `employeeAddress` FROM `employee` WHERE employeeId ='".$id."' AND branchId=".$branchSelect;
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<div> <table>";
                    echo "<tr>"."<td>".$row["employeeName"]."</td><td>".$row["employeePosition"]."</td><td>".$row["employeeId"]."</td><td>".$row["employeeDOB"]."</td><td>".$row["employeeAddress"]."</td></tr>";
                    echo "</table> </div>";
                }
            }
            echo            "
                            <div>
                                <form method='POST'>
                                    <button type='submit' name='deleteConfirmation' class='cancelDelBtn' value='cancel' >cancel</button>
                                    <button type='submit' name='deleteConfirmation' class='yesDelBtn' value='".$id."' >yes</button>
                                </form>
                            </div>
                        </div>
                    </div>";
        }

        if(isset($_POST["deleteConfirmation"])){
            if($_POST["deleteConfirmation"] != "cancel"){
                echo "<script>
                    $('#body_employee').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.deleteSuccess').css('filter', 'blur(0px)')
                </script>";
                echo "<div class='deleteSuccess'>
                            <div>
                                <img src='./assets/MovedBin.png' alt='no img'>
                                <h2>File moved to trash bin</h2>
                                <button onclick='deletedList(\"employee\")'>OK</button>
                            </div>
                        </div>";
                $id = $_POST["deleteConfirmation"];
                $sql = "SELECT * FROM `employee`WHERE employeeId ='".$id."' AND branchId='".$branchSelect."'";
                $result = $con->query($sql);
                $row = $result->fetch_assoc();
                $sql = "INSERT INTO `deletedemployee`(`employeeId`, `branchId`, `employeeName`, `employeePosition`, `employeeDOB`, `employeeAddress`) VALUES ('".$id."','".$branchSelect."','".$row["employeeName"]."','".$row["employeePosition"]."','".$row["employeeDOB"]."','".$row["employeeAddress"]."')";
                $con->query($sql);
                $sql = "INSERT INTO `trashcan`(`id`, `branchId`, `tanggal`,`type`) VALUES ('".$id."','".$branchSelect."','".date("Y/m/d")."','employee')";
                $con->query($sql);
                $sql = "DELETE FROM `employee` WHERE employeeId ='".$id."' AND branchId='".$branchSelect."'";
                $con->query($sql);
                $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','deleted','Employee Tab')";
                $con->query($sql);
            }
        }

        ?>
        <div class="addList">
            <div>
                <h2>Add Employee</h2>
                <form name="employee" method="POST" id="employeeForm">
                    <div class="input-box">
                        <span>Name</span>
                        <input type="text" name="nama" id="name" placeholder="Enter name here">
                        <div id="errName"></div>
                    </div>
                    <div class="input-box">
                        <span>Position</span>
                        <input type="text" name="posisi" id="position" placeholder="Enter position here">
                        <div id="errPos"></div>
                    </div>
                    <div class="input-date">
                        <span>DOB</span>
                        <input type="date" name="tanggal" id="date">
                        <div id="errDate"></div>
                    </div>
                    <div class="input-box">
                        <span>Address</span>
                        <input type="text" name="alamat" id="address" placeholder="Enter address here">
                        <div id="errAddress"></div>
                    </div>
                    <div>
                        <button type="button" onclick="closeAddList('employee')">Cancel</button>
                        <input type="button" value="Submit" onclick="employeeValidation()">
                    </div>
                </form>
            </div>
        </div>
        <div class='addSuccess'>
            <div>
                <img src='./assets/checkmark.png' alt='no img'>
                <h2>Employee Added Successfully</h2>
                <button onclick='addedList("employee")'>OK</button>
            </div>
        </div>
    </div>
</body>
</html>