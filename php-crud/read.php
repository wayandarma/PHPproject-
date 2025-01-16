<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "includes/config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM employees WHERE id = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["name"];
                $address = $row["address"];
                $salary = $row["salary"];
            } else{
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($conn);
} else{
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StaffHub Manager - View Employee</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.5;
            color: #333;
        }

        .wrapper {
            width: 100%;
            min-height: 100vh;
            padding: 15px;
        }

        .employee-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .page-header {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .info-group {
            margin-bottom: 25px;
        }

        .info-group label {
            display: block;
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .info-group p {
            font-size: 1rem;
            color: #2c3e50;
            margin: 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
        }

        .btn-back i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .wrapper {
                padding: 10px;
            }

            .employee-card {
                padding: 15px;
            }

            .page-header {
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .info-group {
                margin-bottom: 20px;
            }

            .btn-back {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .wrapper {
                padding: 5px;
            }

            .employee-card {
                padding: 12px;
            }

            .page-header h1 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="employee-card">
                        <div class="page-header">
                            <h1>Employee Details</h1>
                        </div>
                        <div class="info-group">
                            <label>Name</label>
                            <p><?php echo $name; ?></p>
                        </div>
                        <div class="info-group">
                            <label>Address</label>
                            <p><?php echo $address; ?></p>
                        </div>
                        <div class="info-group">
                            <label>Salary</label>
                            <p>$<?php echo number_format($salary); ?></p>
                        </div>
                        <a href="index.php" class="btn-back">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
