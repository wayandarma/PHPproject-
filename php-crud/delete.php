<?php
// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Include config file
    require_once "includes/config.php";
    
    // Prepare a delete statement
    $sql = "DELETE FROM employees WHERE id = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            header("location: index.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($conn);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Delete Employee</title>
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

        .delete-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
            text-align: center;
        }

        .page-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .alert-wrapper {
            margin: 20px 0;
            padding: 20px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 5px;
            color: #856404;
        }

        .alert-wrapper h4 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #856404;
        }

        .alert-wrapper p {
            margin: 0;
            font-size: 0.95rem;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 25px;
            font-size: 0.95rem;
            font-weight: 500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .wrapper {
                padding: 10px;
            }

            .delete-card {
                padding: 20px;
            }

            .page-header {
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .alert-wrapper {
                padding: 15px;
                margin: 15px 0;
            }

            .btn-group {
                flex-direction: column;
                gap: 8px;
            }

            .btn {
                width: 100%;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .wrapper {
                padding: 5px;
            }

            .delete-card {
                padding: 15px;
            }

            .page-header h1 {
                font-size: 1.25rem;
            }

            .alert-wrapper {
                padding: 12px;
                margin: 12px 0;
            }

            .alert-wrapper h4 {
                font-size: 1rem;
            }

            .alert-wrapper p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="delete-card">
                        <div class="page-header">
                            <h1>Delete Employee</h1>
                        </div>
                        <div class="alert-wrapper">
                            <h4>Are you sure?</h4>
                            <p>This action cannot be undone. All information associated with this employee will be permanently deleted.</p>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <div class="btn-group">
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> No
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
