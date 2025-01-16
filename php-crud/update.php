<?php
// Include config file
require_once "includes/config.php";
 
// Define variables and initialize with empty values
$name = $address = $salary = "";
$name_err = $address_err = $salary_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive integer value.";
    } else{
        $salary = $input_salary;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err)){
        // Prepare an update statement
        $sql = "UPDATE employees SET name=?, address=?, salary=? WHERE id=?";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_address, $param_salary, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StaffHub Manager - Update Employee</title>
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

        .form-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }

        .page-header {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .page-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            color: #495057;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 0.95rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 5px;
            font-size: 0.875rem;
            color: #dc3545;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            font-size: 0.95rem;
            font-weight: 500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #007bff;
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

            .form-card {
                padding: 20px;
            }

            .page-header {
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .form-group {
                margin-bottom: 15px;
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

            .form-card {
                padding: 15px;
            }

            .page-header h2 {
                font-size: 1.25rem;
            }

            .form-control {
                padding: 10px;
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
                    <div class="form-card">
                        <div class="page-header">
                            <h2>Update Employee</h2>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                                <?php if(!empty($name_err)): ?>
                                    <span class="invalid-feedback"><?php echo $name_err;?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                                <?php if(!empty($address_err)): ?>
                                    <span class="invalid-feedback"><?php echo $address_err;?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>Salary</label>
                                <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                                <?php if(!empty($salary_err)): ?>
                                    <span class="invalid-feedback"><?php echo $salary_err;?></span>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                            <div class="btn-group">
                                <input type="submit" class="btn btn-primary" value="Submit">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Cancel
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
