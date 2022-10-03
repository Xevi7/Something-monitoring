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
    <title>Trash Can</title>
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

    <div id="body_trashCan">
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
                    <h2>Trash Can</h2>
                    <div>
                        <form method='POST'>
                            <input type='text' name='search' placeholder='search data' value='<?php if(isset($_POST['search'])){echo $_POST['search'];} ?>'>
                            <button type='submit' class='emptyTrash' name='emptyTrashCan'>Empty Trash Can</button>
                        </form>
                    </div>
                </div>
            <div>
                <form method="POST">
        <?php
        if(isset($_POST["search"]) && $_POST["search"] != ""){
            $keyword = $_POST["search"];
            $sql = "SELECT * FROM `trashcan` WHERE branchId ='".$branchSelect."' AND (id LIKE '%".$keyword."%' OR tanggal LIKE '%".$keyword."%') ORDER BY `trashorder` DESC";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $dataName;
                    $detailId;
                    $dataId;
                    $dataDOB;
                    $dataAddress;
                    $detail;
                    $image;
                    $sql2;
                    if($row["type"] == "employee"){
                        $dataName = "employeeName";
                        $dataId = "employeeId";
                        $detailId = "employeePosition";
                        $dataDOB = "employeeDOB";
                        $dataAddress = "employeeAddress";
                        $detail = "Position";
                        $image = "Employee";
                        $sql2 = "SELECT * FROM `deletedemployee` WHERE branchId ='".$branchSelect."' AND `".$dataId."` ='".$row["id"]."'";
                    }
                    else if($row["type"] == "customer"){
                        $dataName = "customerName";
                        $dataId = "customerId";
                        $detailId = "membership";
                        $dataDOB = "customerDOB";
                        $dataAddress = "customerAddress";
                        $detail = "Membership";
                        $image = "Customer";
                        $sql2 = "SELECT * FROM `deletedcustomer` WHERE branchId ='".$branchSelect."' AND `".$dataId."` ='".$row["id"]."'";
                    }
                    $result2 = $con->query($sql2);
                    $row2 = $result2->fetch_assoc();
                    if($result2 != false && $result2->num_rows > 0){
                        echo "<div class='dataList'><div><img class='dataImage' src='./assets/".$image." 1.png' alt='no img'><div>".$row["id"]." deleted on ".$row["tanggal"]."</div></div><a href='javascript:void(0);' class='arrowDetail'><img class='rotate' src='./assets/arrowDown.png' alt='no img'></a></div>";
                        echo "<div class='dataDetail'> Name : ".$row2[$dataName]."<br>".$detail." : ".$row2[$detailId]."<br>ID : ".$row["id"]."<br>DOB : ".$row2[$dataDOB]."<br>Address : ".$row2[$dataAddress]."<br><button type='submit' name='restoreBtn' value='".$row["id"]."'>Restore Data</button></div>";
                    }
                }
            }
            else{
                echo "<h2 class='emptySearch'>No audit logs were found</h2>";
            }
        }
        else {
            $sql = "SELECT * FROM trashcan WHERE branchId ='" . $branchSelect."' ORDER BY `trashorder` DESC";
            $result = $con->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $dataName;
                    $detailId;
                    $dataId;
                    $dataDOB;
                    $dataAddress;
                    $detail;
                    $image;
                    $sql2;
                    if($row["type"] == "employee"){
                        $dataName = "employeeName";
                        $dataId = "employeeId";
                        $detailId = "employeePosition";
                        $dataDOB = "employeeDOB";
                        $dataAddress = "employeeAddress";
                        $detail = "Position";
                        $image = "Employee";
                        $sql2 = "SELECT * FROM `deletedemployee` WHERE branchId ='".$branchSelect."' AND `".$dataId."` ='".$row["id"]."'";
                    }
                    else if($row["type"] == "customer"){
                        $dataName = "customerName";
                        $dataId = "customerId";
                        $detailId = "membership";
                        $dataDOB = "customerDOB";
                        $dataAddress = "customerAddress";
                        $detail = "Membership";
                        $image = "Customer";
                        $sql2 = "SELECT * FROM `deletedcustomer` WHERE branchId ='".$branchSelect."' AND `".$dataId."` ='".$row["id"]."'";
                    }
                    $result2 = $con->query($sql2);
                    $row2 = $result2->fetch_assoc();
                    if($result2 != false && $result2->num_rows > 0){
                        echo "<div class='dataList'><div><img class='dataImage' src='./assets/".$image." 1.png' alt='no img'><div>".$row["id"]." deleted on ".$row["tanggal"]."</div></div><a href='javascript:void(0);' class='arrowDetail'><img class='rotate' src='./assets/arrowDown.png' alt='no img'></a></div>";
                        echo "<div class='dataDetail'> Name : ".$row2[$dataName]."<br>".$detail." : ".$row2[$detailId]."<br>ID : ".$row["id"]."<br>DOB : ".$row2[$dataDOB]."<br>Address : ".$row2[$dataAddress]."<br><button type='submit' name='restoreBtn' value='".$row["id"]."'>Restore Data</button></div>";
                    }
                }
            }
        }

        echo    "</form> </div> </div>";

        if(isset($_POST["restoreBtn"])){
            echo "<script>
                    $('#body_trashCan').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.deleteList').css('filter', 'blur(0px)')
                </script>";
            $id = $_POST["restoreBtn"];
            echo "<div class='restoreList'>
                    <div>
                    <h2>Do you wish to restore this data?</h2>";
            if(str_contains($id,"AO")){
                $sql = "SELECT * FROM deletedemployee WHERE branchId='".$branchSelect."' AND employeeId='".$id."'";
                $result = $con->query($sql);
                if($result != false && $result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<div> <table>";
                        echo "<tr>"."<td>".$row["employeeName"]."</td><td>".$row["employeePosition"]."</td><td>".$row["employeeId"]."</td><td>".$row["employeeDOB"]."</td><td>".$row["employeeAddress"]."</td></tr>";
                        echo "</table> </div>";
                    }
                }
            }
            else if(str_contains($id,"MS")){
                $sql = "SELECT * FROM deletedcustomer WHERE branchId='".$branchSelect."' AND customerId='".$id."'";
                $result = $con->query($sql);
                if($result != false && $result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<div> <table>";
                        echo "<tr>"."<td>".$row["customerName"]."</td><td>".$row["membership"]."</td><td>".$row["customerId"]."</td><td>".$row["customerDOB"]."</td><td>".$row["customerAddress"]."</td></tr>";
                        echo "</table> </div>";
                    }
                }
            }
            echo "<div> <form method='POST'>
                    <button type='submit' name='restoreConfirmation' class='cancelResBtn' value='cancel' >cancel</button>
                    <button type='submit' name='restoreConfirmation' class='yesResBtn' value='".$id."' >yes</button>
                </form> </div> </div> </div>";
        }

        if(isset($_POST["restoreConfirmation"])){
            if($_POST["restoreConfirmation"] != "cancel"){
                echo "<script>
                    $('#body_trashCan').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.restoreSuccess').css('filter', 'blur(0px)')
                </script>";
                echo "<div class='restoreSuccess'>
                            <div>
                                <img src='./assets/checkmark.png' alt='no img'>
                                <h2>File restored</h2>
                                <button onclick='restoredList()'>OK</button>
                            </div>
                        </div>";
                $id = $_POST["restoreConfirmation"];
                if(str_contains($id,"AO")){
                    echo $id;
                    $sql = "SELECT * FROM `deletedemployee`WHERE employeeId ='".$id."' AND branchId='".$branchSelect."'";
                    $result = $con->query($sql);
                    $row = $result->fetch_assoc();
                    $sql = "INSERT INTO `employee`(`employeeId`, `branchId`, `employeeName`, `employeePosition`, `employeeDOB`, `employeeAddress`) VALUES ('".$row["employeeId"]."','".$branchSelect."','".$row["employeeName"]."','".$row["employeePosition"]."','".$row["employeeDOB"]."','".$row["employeeAddress"]."')";
                    $con->query($sql);
                    $sql = "DELETE FROM `deletedemployee` WHERE employeeId ='".$id."' AND branchId='".$branchSelect."'";
                }
                else if(str_contains($id,"MS")){
                    $sql = "SELECT * FROM `deletedcustomer`WHERE customerId ='".$id."' AND branchId='".$branchSelect."'";
                    $result = $con->query($sql);
                    $row = $result->fetch_assoc();
                    $sql = "INSERT INTO `customer`(`customerId`, `branchId`, `customerName`, `membership`, `customerDOB`, `customerAddress`) VALUES ('".$row["customerId"]."','".$branchSelect."','".$row["customerName"]."','".$row["membership"]."','".$row["customerDOB"]."','".$row["customerAddress"]."')";
                    $con->query($sql);
                    $sql = "DELETE FROM `deletedcustomer` WHERE customerId ='".$id."' AND branchId='".$branchSelect."'";
                }
                $con->query($sql);
                $sql = "DELETE FROM `trashcan` WHERE id ='".$id."' AND branchId='".$branchSelect."'";
                $con->query($sql);
                $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','restored','Trash Can')";
                $con->query($sql);
            }
        }

        if(isset($_POST["emptyTrashCan"])){
            $sql = "SELECT * FROM trashcan WHERE branchId ='" . $branchSelect."'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                $count = $result->num_rows;
                $itm;
                if($count == 1){
                    $itm = "item";
                }
                else{
                    $itm = "items";
                }
                echo "<script>
                        $('#body_trashCan').children('div:nth-child(3)').css('filter', 'blur(2px)')
                        $('.emptyAsk').css('filter', 'blur(0px)')
                    </script>";
                    echo "<div class='emptyAsk'>
                                <div>
                                    <h2>are you sure you want to permanently delete ".$count." ".$itm."?</h2>
                                </div>
                                <div>
                                    <form method='POST'>
                                        <button type='submit' name='emptyConfirmation' class='cancelEmpBtn' value='cancel' >cancel</button>
                                        <button type='submit' name='emptyConfirmation' class='yesEmpBtn' value='confirm' >yes</button>
                                    </form>
                                </div>
                            </div>";
            }
        }

        if(isset($_POST["emptyConfirmation"])){
            if($_POST["emptyConfirmation"] == "confirm"){
                echo "<script>
                    $('#body_trashCan').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.emptySuccess').css('filter', 'blur(0px)')
                </script>";
                echo "<div class='emptySuccess'>
                            <div>
                                <img src='./assets/checkmark.png' alt='no img'>
                                <h2>Successfully empty Trash Can</h2>
                                <button onclick='mtedList()'>OK</button>
                            </div>
                        </div>";
                $sql = "DELETE FROM `trashcan` WHERE branchId='".$branchSelect."'";
                $con->query($sql);
                $sql = "DELETE FROM `deletedemployee` WHERE branchId='".$branchSelect."'";
                $con->query($sql);
                $sql = "DELETE FROM `deletedcustomer` WHERE branchId='".$branchSelect."'";
                $con->query($sql);
            }
        }

        ?>
    </div>
</body>
</html>