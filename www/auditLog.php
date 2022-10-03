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
    <title>Audit Log</title>
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

    <div id="body_auditLog">
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
                    <h2>Audit Log</h2>
                    <div>
                        <form method='POST'>
                            <input type='text' name='search' placeholder='search audit log' value='<?php if(isset($_POST['search'])){echo $_POST['search'];} ?>'>
                        </form>
                    </div>
                </div>
            <div>      
        <?php
        if(isset($_POST["search"]) && $_POST["search"] != ""){
            $keyword = $_POST["search"];
            $sql = "SELECT * FROM `auditlog` WHERE branchId ='".$branchSelect."' AND (name LIKE '%".$keyword."%' OR type LIKE '%".$keyword."%' OR section LIKE '%".$keyword."%' OR id LIKE '%".$keyword."%') ORDER BY `auditorder` DESC";
            $result = $con->query($sql);
            if($result!= false && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($row["type"] == "added"){
                        echo "<div class='auditList'>".$row["name"]." ".$row["type"]." data to ".$row["section"]."<a href='javascript:void(0);' class='arrowDetail'><img class='rotate' src='./assets/arrowDown.png' alt='no img'></a></div>";
                        if($row["section"] == "Employee Tab"){
                            echo "<div class='auditDetail'>Added Employee ".$row["id"]." to ".$row["section"]."</div>";
                        }
                        else if($row["section"] == "Customer Tab"){
                            echo "<div class='auditDetail'>Added Customer ".$row["id"]." to ".$row["section"]."</div>";
                        }
                        else if($row["section"] == "Stock"){
                            echo "<div class='auditDetail'>Added ".$row["id"]." to ".$row["section"]."</div>";
                        }
                    }
                    else{
                        echo "<div class='auditList'><div>".$row["name"]." ".$row["type"]." data on ".$row["section"]."</div><a href='javascript:void(0);' class='arrowDetail'><img class='rotate' src='./assets/arrowDown.png' alt='no img'></a></div>";
                        if($row["section"] == "Employee Tab"){
                            if($row["type"] == "updated"){
                                echo "<div class='auditDetail'>Updated employee detail of ".$row["id"]."</div>";
                            }
                            else{
                                echo "<div class='auditDetail'>Employee ".$row["id"]." moved to Trash Can</div>";
                            }
                        }
                        else if($row["section"] == "Customer Tab"){
                            if($row["type"] == "updated"){
                                echo "<div class='auditDetail'>Updated customer detail of ".$row["id"]."</div>";
                            }
                            else{
                                echo "<div class='auditDetail'>Customer ".$row["id"]." moved to Trash Can</div>";
                            }
                        }
                        else if($row["section"] == "Stock"){
                            echo "<div class='auditDetail'>Updated stock detail of ".$row["id"]."</div>";
                        }
                    }
                }
            }
            else{
                echo "<h2 class='emptySearch'>No audit logs were found</h2>";
            }
        }
        else {
            $sql = "SELECT * FROM auditlog WHERE branchId ='" . $branchSelect."' ORDER BY `auditorder` DESC";
            $result = $con->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    if($row["type"] == "added"){
                        echo "<div class='auditList'><div>".$row["name"]." ".$row["type"]." data to ".$row["section"]."</div><a href='javascript:void(0);' class='arrowDetail'><img class='rotate' src='./assets/arrowDown.png' alt='no img'></a></div>";
                        if($row["section"] == "Employee Tab"){
                            echo "<div class='auditDetail'>Added Employee ".$row["id"]." to ".$row["section"]."</div>";
                        }
                        else if($row["section"] == "Customer Tab"){
                            echo "<div class='auditDetail'>Added Customer ".$row["id"]." to ".$row["section"]."</div>";
                        }
                        else if($row["section"] == "Stock"){
                            echo "<div class='auditDetail'>Added ".$row["id"]." to ".$row["section"]."</div>";
                        }
                    }
                    else if($row["type"] == "restored"){
                        echo "<div class='auditList'><div>".$row["name"]." ".$row["type"]." data from ".$row["section"]."</div><a href='javascript:void(0);' class='arrowDetail'><img class='rotate' src='./assets/arrowDown.png' alt='no img'></a></div>";
                        echo "<div class='auditDetail'>restored data ".$row["id"]."</div>";
                    }
                    else{
                        echo "<div class='auditList'><div>".$row["name"]." ".$row["type"]." data on ".$row["section"]."</div><a href='javascript:void(0);' class='arrowDetail'><img class='rotate' src='./assets/arrowDown.png' alt='no img'></a></div>";
                        if($row["section"] == "Employee Tab"){
                            if($row["type"] == "updated"){
                                echo "<div class='auditDetail'>Updated employee detail of ".$row["id"]."</div>";
                            }
                            else{
                                echo "<div class='auditDetail'>Employee ".$row["id"]." moved to Trash Can</div>";
                            }
                        }
                        else if($row["section"] == "Customer Tab"){
                            if($row["type"] == "updated"){
                                echo "<div class='auditDetail'>Updated customer detail of ".$row["id"]."</div>";
                            }
                            else{
                                echo "<div class='auditDetail'>Customer ".$row["id"]." moved to Trash Can</div>";
                            }
                        }
                        else if($row["section"] == "Stock"){
                            echo "<div class='auditDetail'>Updated stock detail of ".$row["id"]."</div>";
                        }
                    }
                }
            }
        }

        echo    "</div> </div>";

        ?>
    </div>
</body>
</html>