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
    <title>customer</title>
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

    <div id="body_customer">
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
                <h2>Customer</h2>
                <div>
                    <form method='POST'>
                        <input type='text' name='search' placeholder='search customer' value='<?php if(isset($_POST['search'])){echo $_POST['search'];} ?>'>
                    </form>
                    <button onclick='displayAddList("customer")'><img src='./assets/plus 1.png' alt='no img'></button>
                </div>
            </div>
            <div> 
            <form method='POST'>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Membership</th>
                        <th>ID</th>
                        <th>DOB</th>
                        <th>Address</th>
                        <th></th>
                    </tr>      
        <?php
        if(isset($_POST["search"]) && $_POST["search"] != ""){
            $keyword = $_POST["search"];
            $sql = "SELECT * FROM `customer` WHERE branchId ='".$branchSelect."' AND (customerId LIKE '%".$keyword."%' OR customerName LIKE '%".$keyword."%' OR membership LIKE '%".$keyword."%' OR customerAddress LIKE '%".$keyword."%' OR customerDOB LIKE '%".$keyword."%')";
            $result = $con->query($sql);
            if($result!= false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr> <td>" . $row["customerName"] . "</td> <td>" . $row["membership"] . "</td> <td>" . $row["customerId"] . "</td> <td>" . $row["customerDOB"] . "</td> <td>" . $row["customerAddress"] . "</td> <td> <button type='submit' class='editCustomer' name='editCust' value='".$row["customerId"]."'><img src='./assets/edit.png' alt='no img'></button> <button type='submit' class='deleteCustomer' name='deleteCust' value='".$row["customerId"]."'><img src='./assets/bin 1.png' alt='no img'></button> </td>";
                }
                echo "</table>";
            }
            else{
                echo "</table> <h2 class='emptySearch'>No customers were found</h2>";
            }
        }
        else {
            $sql = "SELECT * FROM customer WHERE branchId =" . $branchSelect;
            $result = $con->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr> <td>" . $row["customerName"] . "</td> <td>" . $row["membership"] . "</td> <td>" . $row["customerId"] . "</td> <td>" . $row["customerDOB"] . "</td> <td>" . $row["customerAddress"] . "</td> <td> <button type='submit' class='editCustomer' name='editCust' value='".$row["customerId"]."'><img src='./assets/edit.png' alt='no img'></button> <button type='submit' class='deleteCustomer' name='deleteCust' value='".$row["customerId"]."'><img src='./assets/bin 1.png' alt='no img'></button> </td>";
                }
            }
            echo "</table>";
        }

        echo    "</form> </div> </div>";


        if(isset($_POST["namaCust"])){
            $id = uniqueId();
            $sql = "INSERT INTO `customer`(`customerId`, `branchId`, `customerName`, `membership`, `customerDOB`, `customerAddress`) VALUES ('".$id."','".$branchSelect."','".$_POST["namaCust"]."','".$_POST["membership"]."','".$_POST["tanggalCust"]."','".$_POST["alamatCust"]."')";
            $con->query($sql);
            $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','added','Customer Tab')";
            $con->query($sql);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        
        function uniqueId(){
            global $branchSelect, $con, $result;
            $id = "MS" . $branchSelect;
            for($i = 0; $i < 4; $i++){
                $id .= rand(0,9);
            }
            $sql = "SELECT customerId FROM customer WHERE branchId =" . $branchSelect . " AND customerId ='" . $id . "'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                uniqueId();
            }
            $sql = "SELECT customerId FROM deletedcustomer WHERE branchId =" . $branchSelect . " AND customerId ='" . $id . "'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                uniqueId();
            }
            else{
                return $id;
            }
        }

        if(isset($_POST['editCust'])){
            echo "<script>
                    $('#body_customer').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.editList').css('filter', 'blur(0px)')
                </script>";
            echo "<div class='editList'>
                        <div>
                            <h2>Edit customer ".$_POST["editCust"]."</h2>";
            $id = $_POST["editCust"];
            $sql = "SELECT * FROM `customer` WHERE branchId ='".$branchSelect."' AND customerId='".$id."'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                $row = $result->fetch_assoc();
                echo "<div> 
                        <form method='POST' name='editCustomerForm'>
                            <div class='input-box'>
                                <span>Name</span>
                                <input type='text' name='editedName' value='".$row["customerName"]."'>
                                <div id='errEditName'></div>
                            </div>
                            <div class='input-box'>
                                <span>membership</span>
                                <input type='text' name='editedMembership' value='".$row["membership"]."'>
                                <div id='errEditMem'></div>
                            </div>
                            <div class='input-box'>
                                <span>Address</span>
                                <input type='text' name='editedAddress' value='".$row["customerAddress"]."'>
                                <div id='errEditAddress'></div>
                            </div>
                            <div>
                                <button type='button' onclick='closeEditList(\"customer\")'>cancel</button>
                                <input type='hidden' name='editConfirmation' value='".$id."'>
                                <input type='button' value='submit' onclick='editCustomervalidation()'>
                            </div>
                        </form>
                    </div>";
            }
        }

        if(isset($_POST["editConfirmation"])){
            $id = $_POST["editConfirmation"];
            if($id != "cancel"){
                echo "<script>
                    $('#body_customer').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.editSuccess').css('filter', 'blur(0px)')
                </script>";
                echo "<div class='editSuccess'>
                        <div>
                            <img src='./assets/checkmark.png' alt='no img'>
                            <h2>Customer Edited Successfully</h2>
                            <button onclick='editedList(\"customer\")'>OK</button>
                        </div>
                    </div>";
                $sql = "SELECT * FROM `customer` WHERE branchId ='".$branchSelect."' AND customerId='".$id."'";
                $result = $con->query($sql);
                $row = $result->fetch_assoc();
                if($_POST["editedName"] != $row["customerName"] || $_POST["editedMembership"] != $row["membership"] || $_POST["editedAddress"] != $row["customerAddress"]){
                    $sql = "UPDATE `customer` SET `customerName`='".$_POST["editedName"]."',`membership`='".$_POST["editedMembership"]."',`customerAddress`='".$_POST["editedAddress"]."' WHERE branchId ='".$branchSelect."' AND customerId ='".$id."'";
                    $con->query($sql);
                    $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','updated','Customer Tab')";
                    $con->query($sql);
                }
            }
        }

        if(isset($_POST["deleteCust"])){
            echo "<script>
                    $('#body_customer').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.deleteList').css('filter', 'blur(0px)')
                </script>";
            echo    "<div class='deleteList'>
                        <div>
                            <h2>are you sure you want to move this data to trash bin?</h2>";
            $id = $_POST["deleteCust"];
            $sql = "SELECT `customerName`, `membership`, `customerId`, `customerDOB`, `customerAddress` FROM `customer` WHERE customerId ='".$id."' AND branchId='".$branchSelect."'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<div> <table>";
                    echo "<tr>"."<td>".$row["customerName"]."</td><td>".$row["membership"]."</td><td>".$row["customerId"]."</td><td>".$row["customerDOB"]."</td><td>".$row["customerAddress"]."</td></tr>";
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
                    $('#body_customer').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.deleteSuccess').css('filter', 'blur(0px)')
                </script>";
                echo "<div class='deleteSuccess'>
                            <div>
                                <img src='./assets/MovedBin.png' alt='no img'>
                                <h2>File moved to trash bin</h2>
                                <button onclick='deletedList(\"customer\")'>OK</button>
                            </div>
                        </div>";
                $id = $_POST["deleteConfirmation"];
                $sql = "SELECT * FROM `customer`WHERE customerId ='".$id."' AND branchId='".$branchSelect."'";
                $result = $con->query($sql);
                $row = $result->fetch_assoc();
                $sql = "INSERT INTO `deletedcustomer`(`customerId`, `branchId`, `customerName`, `membership`, `customerDOB`, `customerAddress`) VALUES ('".$row["customerId"]."','".$branchSelect."','".$row["customerName"]."','".$row["membership"]."','".$row["customerDOB"]."','".$row["customerAddress"]."')";
                $con->query($sql);
                $sql = "INSERT INTO `trashcan`(`id`, `branchId`, `tanggal`,`type`) VALUES ('".$id."','".$branchSelect."','".date("Y/m/d")."','customer')";
                $con->query($sql);
                $sql = "DELETE FROM `customer` WHERE customerId ='".$id."' AND branchId='".$branchSelect."'";
                $con->query($sql);
                $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','deleted','Customer Tab')";
                $con->query($sql);
            }
        }

        ?>
        <div class="addList">
            <div>
                <h2>Add Customer</h2>
                <form name="customer" method="POST" id="customerForm">
                    <div class="input-box">
                        <span>Name</span>
                        <input type="text" name="namaCust" id="name" placeholder="Enter name here">
                        <div id="errName"></div>
                    </div>
                    <div class="input-box">
                        <span>Membership</span>
                        <input type="text" name="membership" id="position" placeholder="Enter position here">
                        <div id="errMem"></div>
                    </div>
                    <div class="input-date">
                        <span>DOB</span>
                        <input type="date" name="tanggalCust" id="date">
                        <div id="errDate"></div>
                    </div>
                    <div class="input-box">
                        <span>Address</span>
                        <input type="text" name="alamatCust" id="address" placeholder="Enter address here">
                        <div id="errAddress"></div>
                    </div>
                    <div>
                        <button type="button" onclick="closeAddList('customer')">Cancel</button>
                        <input type="button" value="Submit" onclick="customerValidation()">
                    </div>
                </form>
            </div>
        </div>
        <div class='addSuccess'>
            <div>
                <img src='./assets/checkmark.png' alt='no img'>
                <h2>Customer Added Successfully</h2>
                <button onclick='addedList("customer")'>OK</button>
            </div>
        </div>
    </div>
</body>
</html>