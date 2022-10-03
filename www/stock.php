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
    <title>stock</title>
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

    <div id="body_stock">
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
                <h2>Stock</h2>
                <div>
                    <form method='POST'>
                        <input type='text' name='search' placeholder='search product' value='<?php if(isset($_POST['search'])){echo $_POST['search'];} ?>'>
                    </form>
                    <button onclick='displayAddList("stock")'><img src='./assets/plus 1.png' alt='no img'></button>
                </div>
            </div>
            <div> 
            <form method='POST'>
                <table>
                    <tr>
                        <th>Product Name</th>
                        <th>Warehouse Address</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>      
        <?php
        if(isset($_POST["search"]) && $_POST["search"] != ""){
            $keyword = $_POST["search"];
            $sql = "SELECT * FROM `stock` WHERE branchId ='".$branchSelect."' AND (productName LIKE '%".$keyword."%' OR warehouseAddress LIKE '%".$keyword."%' OR quantity LIKE '%".$keyword."%')";
            $result = $con->query($sql);
            if($result!= false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr> <td>" . $row["productName"] . "</td> <td>" . $row["warehouseAddress"] . "</td> <td>" . $row["quantity"] . "</td> <td> <button type='submit' class='editStock' name='editSto' value='".$row["productName"]."'><img src='./assets/edit.png' alt='no img'></button> <button type='submit' class='deleteStock' name='deleteSto value='".$row["productName"]."'><img src='./assets/bin 1.png' alt='no img'></button> </td>";
                }
                echo "</table>";
            }
            else{
                echo "</table> <h2 class='emptySearch'>No stocks were found</h2>";
            }
        }
        else {
            $sql = "SELECT * FROM stock WHERE branchId =" . $branchSelect;
            $result = $con->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr> <td>" . $row["productName"] . "</td> <td>" . $row["warehouseAddress"] . "</td> <td>" . $row["quantity"] . "</td> <td> <button type='submit' class='editStock' name='editSto' value='".$row["productName"]."'><img src='./assets/edit.png' alt='no img'></button> <button type='submit' class='deleteStock' name='deleteSto value='".$row["productName"]."'><img src='./assets/bin 1.png' alt='no img'></button> </td>";
                }
            }
            echo "</table>";
        }

        echo    "</form> </div> </div>";


        if(isset($_POST["namaStock"])){
            $id = $_POST["namaStock"];
            $sql = "SELECT * FROM `stock`WHERE productName ='".$id."' AND branchId='".$branchSelect."'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows == 0){
                $sql = "INSERT INTO `stock`(`productName`, `branchId`, `warehouseAddress`, `quantity`) VALUES ('".$id."','".$branchSelect."','".$_POST["gudang"]."','".$_POST["jumlahStock"]."')";
                $con->query($sql);
                $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','added','Stock')";
                $con->query($sql);
                echo "<meta http-equiv='refresh' content='0'>";
            }
        }

        if(isset($_POST['editSto'])){
            echo "<script>
                    $('#body_stock').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.editList').css('filter', 'blur(0px)')
                </script>";
            echo "<div class='editList'>
                        <div>
                            <h2>Edit stock ".$_POST["editSto"]."</h2>";
            $id = $_POST["editSto"];
            $sql = "SELECT * FROM `stock` WHERE branchId ='".$branchSelect."' AND productName='".$id."'";
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                $row = $result->fetch_assoc();
                echo "<div> 
                        <form method='POST' name='editStockForm'>
                            <div class='input-box'>
                                <span>Product Name</span>
                                <input type='text' name='editedName' value='".$row["productName"]."'>
                                <div id='errEditName'></div>
                            </div>
                            <div class='input-box'>
                                <span>Warehouse Address</span>
                                <input type='text' name='editedAddress' value='".$row["warehouseAddress"]."'>
                                <div id='errEditAddress'></div>
                            </div>
                            <div class='input-box'>
                                <span>Quantity</span>
                                <input type='number' name='editedQuantity' value='".$row["quantity"]."'>
                                <div id='errEditQuantity'></div>
                            </div>
                            <div>
                                <button type='button' onclick='closeEditList(\"stock\")'>cancel</button>
                                <input type='hidden' name='editConfirmation' value='".$id."'>
                                <input type='button' value='submit' onclick='editStockvalidation()'>
                            </div>
                        </form>
                    </div>";
            }
        }

        if(isset($_POST["editConfirmation"])){
            $id = $_POST["editConfirmation"];
            if($id != "cancel"){
                echo "<script>
                    $('#body_stock').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.editSuccess').css('filter', 'blur(0px)')
                </script>";
                echo "<div class='editSuccess'>
                        <div>
                            <img src='./assets/checkmark.png' alt='no img'>
                            <h2>Stock Edited Successfully</h2>
                            <button onclick='editedList(\"stock\")'>OK</button>
                        </div>
                    </div>";
                $sql = "SELECT * FROM `stock` WHERE branchId ='".$branchSelect."' AND productName='".$id."'";
                $result = $con->query($sql);
                $row = $result->fetch_assoc();
                if($_POST["editedName"] != $row["productName"] || $_POST["editedAddress"] != $row["warehouseAddress"] || $_POST["editedQuantity"] != $row["quantity"]){
                    $sql = "UPDATE `stock` SET `productName`='".$_POST["editedName"]."',`warehouseAddress`='".$_POST["editedAddress"]."',`quantity`='".$_POST["editedQuantity"]."' WHERE branchId ='".$branchSelect."' AND productName ='".$id."'";
                    $con->query($sql);
                    $sql = "INSERT INTO `auditlog`(`id`, `branchId`, `name`, `type`, `section`) VALUES ('".$id."','".$branchSelect."','".$user."','updated','Stock')";
                    $con->query($sql);
                }
            }
        }

        if(isset($_POST["deleteSto"])){
            echo "<script>
                    $('#body_stock').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.deleteList').css('filter', 'blur(0px)')
                </script>";
            echo    "<div class='deleteList'>
                        <div>
                            <h2>are you sure you want to move this data to trash bin?</h2>";
            $id = $_POST["deleteSto"];
            $sql = "SELECT `productName`, `warehouseAddress`, `quantity ` FROM `stock` WHERE productName ='".$id."' AND branchId=".$branchSelect;
            $result = $con->query($sql);
            if($result != false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<div> <table>";
                    echo "<tr>"."<td>".$row["productName"]."</td><td>".$row["warehouseAddress"]."</td><td>".$row["quantity"]."</td></tr>";
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
                    $('#body_stock').children('div:nth-child(3)').css('filter', 'blur(2px)')
                    $('.deleteSuccess').css('filter', 'blur(0px)')
                </script>";
                echo "<div class='deleteSuccess'>
                            <div>
                                <img src='./assets/MovedBin.png' alt='no img'>
                                <h2>File moved to trash bin</h2>
                                <button onclick='deletedList(\"stock\")'>OK</button>
                            </div>
                        </div>";
                $id = $_POST["deleteConfirmation"];
                $sql = "DELETE FROM `stock` WHERE productName ='".$id."' AND branchId='".$branchSelect."'";
                $con->query($sql);
            }
        }

        ?>
        <div class="addList">
            <div>
                <h2>Add Stock</h2>
                <form name="stock" method="POST" id="stockForm">
                    <div class="input-box">
                        <span>Product Name</span>
                        <input type="text" name="namaStock" id="name" placeholder="Enter product name here">
                        <div id="errName"></div>
                    </div>
                    <div class="input-box">
                        <span>Warehouse Address</span>
                        <input type="text" name="gudang" id="warehouses" placeholder="Enter warehouse address here">
                        <div id="errAddress"></div>
                    </div>
                    <div class="input-box">
                        <span>Quantity</span>
                        <input type="number" name="jumlahStock" id="quantity" placeholder="Enter product quantity here">
                        <div id="errQuantity"></div>
                    </div>
                    <div>
                        <button type="button" onclick="closeAddList('stock')">Cancel</button>
                        <input type="button" value="Submit" onclick="stockValidation()">
                    </div>
                </form>
            </div>
        </div>
        <div class='addSuccess'>
            <div>
                <img src='./assets/checkmark.png' alt='no img'>
                <h2>Stock Added Successfully</h2>
                <button onclick='addedList("stock")'>OK</button>
            </div>
        </div>
    </div>
</body>
</html>