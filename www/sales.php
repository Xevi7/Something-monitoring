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
    <title>Sales</title>
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

    <div id="body_sales">
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
                    <h2>Sales</h2>
                    <div>
                        <form>
                            <input type='text' name='search' placeholder='search sales data'>
                        </form>
                    </div>
                </div>
                <div class="salesImage">
                    <img src="./assets/graph.png" alt="no img">
                </div>
                <div class="tabHeader">
                    <h2>Recent Sales</h2>
                </div>
                <div class="recentSales">
                        <div class="salesList">
                            <h3>December 2021</h3>
                            <hr>
                            <div>148 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>November 2021</h3>
                            <hr>
                            <div>132 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>October 2021</h3>
                            <hr>
                            <div>117 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>September 2021</h3>
                            <hr>
                            <div>120 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>August 2021</h3>
                            <hr>
                            <div>43 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>July 2021</h3>
                            <hr>
                            <div>76 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>June 2021</h3>
                            <hr>
                            <div>84 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>May 2021</h3>
                            <hr>
                            <div>110 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>April 2021</h3>
                            <hr>
                            <div>90 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>March 2021</h3>
                            <hr>
                            <div>110 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>February 2021</h3>
                            <hr>
                            <div>130 sales were made</div>
                        </div>
                        <div class="salesList">
                            <h3>January 2021</h3>
                            <hr>
                            <div>120 sales were made</div>
                        </div>
                    </table>
                </div>
            </div>      
        
    </div>
</body>
</html>