<?php
session_start();
    if(isset($_POST["username"])){
        $_SESSION["userPass"] = $_POST["username"];
    }
    $branchSelect = NULL;
    if(isset($_SESSION["branchPass"])){
        $branchSelect = $_SESSION["branchPass"];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
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

    <div id="body_home">
        <?php
        $path = str_replace('www','',getcwd());
        system('"'.$path.'mysql\bin\mysqld.exe"');
        $con = mysqli_connect("localhost","root","","somethingmonitoring");
        $sql = "SELECT * FROM branch";
        $result = $con->query($sql);

        echo "<div id='branchList'> <form method='POST'>";
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo "<button type='submit' class='branchName' name='branchIdSelected' value='" . $row["branchId"] . "'>" . $row["branchName"] . "</button>";
                if($branchSelect == NULL){
                    $branchSelect = $row["branchId"];
                    $_SESSION["branchPass"] = $branchSelect;
                }
            }
        }
        echo "</form> </div>";

        if(array_key_exists("branchIdSelected",$_POST)){
            $branchSelect = $_POST["branchIdSelected"];
            $_SESSION["branchPass"] = $branchSelect;
            $sqlPrint = "SELECT employeeId FROM employee WHERE branchId = '" . $branchSelect . "'";
            $resultEmployee = $con->query($sqlPrint);
            $sqlPrint = "SELECT customerId FROM customer WHERE branchId = '" . $branchSelect . "'";
            $resultCustomer = $con->query($sqlPrint);
            $sqlPrint = "SELECT id FROM auditlog WHERE branchId = '" . $branchSelect . "'";
            $resultAudit = $con->query($sqlPrint);
            $sqlPrint = "SELECT productName FROM stock WHERE branchId = '" . $branchSelect . "'";
            $resultStock = $con->query($sqlPrint);
            $sqlPrint = "SELECT id FROM trashcan WHERE branchId = '" . $branchSelect . "'";
            $resultTrash = $con->query($sqlPrint);
            
            $styleBlock = sprintf('
                <style type="text/css">
                   .branchName:nth-child('.$branchSelect.') {
                        background-color:%s
                    }
                </style>
                ', '#103d49');
            echo $styleBlock;

             echo   "<div id='branchStats'>
                        <div>
                            <div>
                                <h3>Employee</h3>
                                <div>
                                    <h2>" . $resultEmployee->num_rows ."</h2>
                                    <img src='./assets/Employee 1.png' alt='no img'>
                                </div>
                                <a href='./employee.php'>details -></a>
                            </div>
                            <div>
                                <h3>Customer</h3>
                                <div>
                                    <h2>" . $resultCustomer->num_rows . "</h2>
                                    <img src='./assets/Customer 1.png' alt='no img'>
                                </div>
                                <a href='./customer.php'>details -></a>
                            </div>
                            <div>
                                <h3>Audit Log</h3>
                                <div>
                                    <h2>" . $resultAudit->num_rows . "</h2>
                                    <img src='./assets/auditLog 1.png' alt='no img'>
                                </div>
                                <a href='./auditLog.php'>details -></a>
                            </div>
                        </div>
                        <div>
                            <div>
                                <h3>Sales</h3>
                                <div>
                                    <h2>1280</h2>
                                    <img src='./assets/sales 1.png' alt='no img'>
                                </div>
                                <a href='./sales.php'>details -></a>
                            </div>
                            <div>
                                <h3>Stock</h3>
                                <div>
                                    <h2>" . $resultStock->num_rows . "</h2>
                                    <img src='./assets/Box 1.png' alt='no img'>
                                </div>
                                <a href='./stock.php'>details -></a>
                            </div>
                            <div>
                                <h3>Trash Can</h3>
                                <div>
                                    <h2>" . $resultTrash->num_rows . "</h2>
                                    <img src='./assets/bin 1.png' alt='no img'>
                                </div>
                                <a href='./trashCan.php'>details -></a>
                            </div>
                        </div>
                    </div>";
        }
        else {
            echo "<h2 id='emptyStats'> Please select a branch </h2>";
        }

        ?>

    </div>
    
</body>
</html>