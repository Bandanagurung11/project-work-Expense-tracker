<!DOCTYPE html>
<html lang="en">
<head>
    <!--Meta Tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Favicons-->
    <link rel="apple-touch-icon" sizes="57x57" href="../assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--Other-->
    <title>Dashboard</title>
    <!--CSS-->
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/fontawesome.all.min.css">
    <!--JS for Graph-->
    <script src="../js/google_chart_loader.js"></script>

</head>
<body>
    
    <!-- Session and DB initialization along with redirects if null-->
    <?php

        // Import constants and secrets.
        include('../php/db/db_config.php');
        include('../php/db/sql_queries.php');
        include('../php/graph/chart_js_generator.php');

        if(!isset($_SESSION)) { 
            session_start(); 
        }

        // check if session active 
        if(!isset($_SESSION['username'])){
            navigate_to_login_page("Not logged in. log in first");
        }

        function navigate_to_login_page($error) {
            echo '<script>';
            echo 'alert("Error : '.$error.'");';
            echo 'window.location.href = "http://localhost/cashtrack/php/pages/login.php";';
            echo '</script>';
        }

    ?>

    <!-- Header for settings, history-->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="http://localhost/cashtrack/index.html">
                <img src="../assets/favicon/android-icon-36x36.png" width="30" height="30" class="d-inline-block align-top" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav mr-auto">
                    <a class="nav-item nav-link active" href=".">Dashboard <span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link" href="./history">History</a>
                    <a class="nav-item nav-link" href="./settings">Settings</a>
                    <a class="nav-item nav-link text-danger" href="../php/auth/logout.php">Log Out</a>
                </div>
                <span class="navbar-text">
                    Welcome, 
                    <span class="font-weight-bold">
                        <?php echo '@'.$_SESSION['username']; ?>
                    </span>
                </span>
            </div>
        </nav>
    </header>
    
    <!--Main page-->
    <main>
        <div class="alert alert-secondary">
            <div class="text-center p-3 h3">
                Total Balance : 
                <?php 
                    $currency = "₹";
                    $query = get_user_currency($conn,$username);
                    $result = mysqli_query($conn,$query);
                    if(!$result) {
                        show_alert("Database error!");
                    } else {
                        $row = mysqli_fetch_assoc($result);

                        switch ($row['currency_default']) {
                            case 1:
                                echo "₹";
                                break;
                            case 2:
                                $currency = "$";
                                echo "$";
                                break;
                            case 3:
                                $currency = "£";
                                echo "£";
                                break;
                            default:
                                $currency = "€";
                                echo "€";
                        } 

                        $query = get_total_balance($conn, $username);
                        $result = mysqli_query($conn,$query);
                        if(mysqli_num_rows($result) == 0) {
                            echo "0";
                        } else {
                            $row = mysqli_fetch_assoc($result);
                            echo $row['balance_after'];
                        }
                    }
                ?>
            </div>

            <!-- Pre fetching balances-->
            <?php 
                $cash_bal = 0;
                $credit_bal = 0;
                $debit_bal = 0;
                $query = get_balances($conn,$username);
                $result = mysqli_query($conn,$query);
                if(!$result) {
                    show_alert("Database error!");
                } else {
                    $row = mysqli_fetch_assoc($result);
                    $cash_bal = $row['cash_bal'];
                    $credit_bal = $row['credit_bal'];
                    $debit_bal = $row['debit_bal'];
                }
            ?>

            <!-- Setting balances with currency-->
            <div class="container p-3">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 p-1">
                        <div class="card text-center border-secondary">
                            <div class="card-header text-white bg-success">Cash Balance</div>
                            <div class="card-body">
                                <?php echo $currency.$cash_bal; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 p-1">
                        <div class="card text-center border-secondary">
                            <div class="card-header text-white bg-success">Debit Balance</div>
                            <div class="card-body">
                                <?php echo $currency.$debit_bal; ?>
                            </div>
                        </div>                
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 p-1">
                        <div class="card text-center border-secondary">
                            <div class="card-header text-white bg-danger">Credit Balance</div>
                            <div class="card-body">
                                <?php echo $currency.$credit_bal; ?>
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
        </div>

        <div class="container p-3">
            <div class="card">
                <div class="card-header">Total Balance chart</div> 
                <div class="card-body">
                    <div id="div_balance_line_chart"></div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">

                <div class="col-sm-12 col-md-6 p-3">
                    <div class="card">
                        <div class="card-header">Expenditure</div> 
                        <div class="card-body">
                            <div id="div_expenditure_pie_chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 p-3">
                    <div class="card">
                        <div class="card-header">Balance Breakdown</div> 
                        <div class="card-body">
                            <div id="div_balance_breakdown"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="container p-3">
            <div class="card">
                <div class="card-header">Common Descriptions</div> 
                <div class="card-body">
                    <table class="table table-bordered text-center text-capitalize">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Description</th>
                                <th scope="col">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = get_common_descriptions($conn,$username);
                                $result = mysqli_query($conn,$query);
                                if(!$result) {
                                  show_alert("Data could not be fetched!");
                                } else {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        $description = $row["description"];
                                        $count = $row["count"];
                                        echo '<td>'.$description.'</td>';
                                        echo '<td>'.$count.'</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </main>
    
    <!--Footer holding add and subtract buttons-->
    <div class="p-1" style="position:fixed;right:0;bottom:0;">
        <button type="button" class="btn btn-success shadow rounded-circle m-1" style="width:64px;height:64px;" data-toggle="modal" data-target="#addMoney">
            <i class="fa fa-plus"></i>
        </button>
        <button type="button" class="btn btn-danger shadow rounded-circle m-1" style="width:64px;height:64px;" data-toggle="modal" data-target="#subMoney">
            <i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-warning shadow rounded-circle m-1" style="width:64px;height:64px;" data-toggle="modal" data-target="#transferMoney">
            <i class="fa fa-exchange-alt text-white"></i>
        </button>
    </div>

    <!--Modal dialogs for adding and subtracting money and transfer money-->
    <div class="modal fade" id="addMoney" tabindex="-1" role="dialog" aria-labelledby="addMoneyCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMoneyLongTitle">Add to balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../php/routines/add_money.php" method="post" onsubmit="return validateAddAmount()" id="addMoneyForm">
                    <div class="form-group">
                        <label for="addAmount">Amount</label>
                        <input type="number" class="form-control form-control-lg" id="addAmount" name="amount" placeholder="Amount in Transaction" required>
                    </div>
                    <div class="form-group">
                        <label for="addDesc">Description</label>
                        <input type="text" class="form-control form-control-lg" id="addDesc" name="desc" placeholder="Description" required>
                    </div>
                    <input type="hidden" value="<?php echo $cash_bal+$debit_bal; ?>" name="balance">
                    <div class="form-group">
                        <label for="addAccount">Account</label>
                        <select class="custom-select custom-select-lg" name="account" id="addAccount">
                            <option value="1" selected>Cash</option>
                            <option value="2">Debit</option>
                            <option value="3">Credit</option>
                        </select>
                    </div>
                    <div class="add-credit-alert collapse alert alert-warning">
                        Warning : Adding to credit means that you're adding to your credit card due.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="addMoneyForm" class="btn btn-primary px-3">Add</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="subMoney" tabindex="-1" role="dialog" aria-labelledby="subMoneyCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subMoneyLongTitle">Subtract from balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../php/routines/sub_money.php" method="post" onsubmit="return validateSubAmount()" id="subMoneyForm">
                    <div class="form-group">
                        <label for="subAmount">Amount</label>
                        <input type="number" class="form-control form-control-lg" id="subAmount" name="amount" placeholder="Amount in Transaction" required>
                    </div>
                    <div class="form-group">
                        <label for="subDesc">Description</label>
                        <input type="text" class="form-control form-control-lg" id="subDesc" name="desc" placeholder="Description" required>
                    </div>
                    <input type="hidden" value="<?php echo $cash_bal+$debit_bal; ?>" name="balance">
                    <div class="form-group">
                        <label for="account">Account</label>
                        <select class="custom-select custom-select-lg" name="account" id="subAccount">
                            <option value="1" selected>Cash</option>
                            <option value="2">Debit</option>
                            <option value="3">Credit</option>
                        </select>
                    </div>
                    <div class="sub-credit-alert collapse alert alert-warning">
                        Warning : Subtracting from credit means that you've paid for your credit card due.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="subMoneyForm" class="btn btn-primary px-3">Subtract</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transferMoney" tabindex="-1" role="dialog" aria-labelledby="transferMoneyCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferMoneyLongTitle">Transfer balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../php/routines/transfer_money.php" method="post" onsubmit="return validateTransferAmount()" id="transferMoneyForm">
                    <div class="form-group">
                        <label for="transferAmount">Amount</label>
                        <input type="number" class="form-control form-control-lg" id="transferAmount" name="amount" placeholder="Amount in Transaction" required>
                    </div>
                    <div class="form-group">
                        <label for="transferDesc">Description</label>
                        <input type="text" class="form-control form-control-lg" id="transferDesc" name="desc" placeholder="Description" required>
                    </div>
                    <input type="hidden" value="<?php echo $cash_bal+$debit_bal; ?>" name="balance">
                    <div class="form-group">
                        <label for="transferFAccount">From Account</label>
                        <select class="custom-select custom-select-lg" name="faccount" id="transferFAccount">
                            <option value="1" selected>Cash</option>
                            <option value="2">Debit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transferTAccount">To Account</label>
                        <select class="custom-select custom-select-lg" name="taccount" id="transferTAccount">
                            <option value="1" selected>Cash</option>
                            <option value="2">Debit</option>
                            <option value="3">Credit</option>
                        </select>
                    </div>
                    <div class="transfer-credit-alert collapse alert alert-warning">
                        Warning : Transferring to credit means that you're paying back the debt.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="transferMoneyForm" class="btn btn-primary px-3">Transfer</button>
            </div>
            </div>
        </div>
    </div>

    <!--JS-->
    <script src="../../js/form_validations.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="../../js/jquery_extensions.js"></script>
</body>
</html>