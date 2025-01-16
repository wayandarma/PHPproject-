<?php
// Include config file
require_once "includes/config.php";

// Process search and filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

// Prepare the base SQL query
$sql = "SELECT * FROM employees WHERE 1=1";

// Add search condition if search term exists
if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR address LIKE ?)";
}

// Add filter condition
switch ($filter) {
    case 'high_salary':
        $sql .= " AND salary >= 5000";
        break;
    case 'low_salary':
        $sql .= " AND salary < 5000";
        break;
}

// Add sorting
switch ($sort) {
    case 'name_asc':
        $sql .= " ORDER BY name ASC";
        break;
    case 'name_desc':
        $sql .= " ORDER BY name DESC";
        break;
    case 'salary_asc':
        $sql .= " ORDER BY salary ASC";
        break;
    case 'salary_desc':
        $sql .= " ORDER BY salary DESC";
        break;
}

// Prepare statement
$stmt = mysqli_prepare($conn, $sql);

// Bind search parameters if search term exists
if (!empty($search)) {
    $search_param = "%{$search}%";
    mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
}

// Execute statement
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StaffHub Manager - Employee Dashboard</title>
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

        .dashboard-card {
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .search-filter-container {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .search-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            font-size: 0.9rem;
            font-weight: 500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-create {
            background-color: #28a745;
            color: #fff;
        }

        .btn-view {
            background-color: #17a2b8;
            color: #fff;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn i {
            margin-right: 5px;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-info {
            background-color: #17a2b8;
            color: #fff;
        }

        @media (max-width: 768px) {
            .wrapper {
                padding: 10px;
            }

            .dashboard-card {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .search-filter-form {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .table td, .table th {
                padding: 8px;
                font-size: 0.9rem;
            }

            .btn-group {
                flex-direction: column;
                width: 100%;
            }
            
            .btn-group .btn {
                width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .wrapper {
                padding: 5px;
            }

            .dashboard-card {
                padding: 12px;
            }

            .page-header h2 {
                font-size: 1.25rem;
            }

            .table td, .table th {
                padding: 6px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="page-header">
                            <h2>StaffHub Manager</h2>
                            <div class="btn-group">
                                <a href="chart.php" class="btn btn-info mr-2">
                                    <i class="fa fa-bar-chart"></i> Show Chart
                                </a>
                                <a href="create.php" class="btn btn-create">
                                    <i class="fa fa-plus"></i> Add New Employee
                                </a>
                            </div>
                        </div>

                        <div class="search-filter-container">
                            <form method="GET" class="search-filter-form">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by name or address..." value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                                <div class="form-group">
                                    <select name="filter" class="form-control">
                                        <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>All Salaries</option>
                                        <option value="high_salary" <?php echo $filter == 'high_salary' ? 'selected' : ''; ?>>High Salary (≥ $5000)</option>
                                        <option value="low_salary" <?php echo $filter == 'low_salary' ? 'selected' : ''; ?>>Low Salary (< $5000)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="sort" class="form-control">
                                        <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                                        <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
                                        <option value="salary_asc" <?php echo $sort == 'salary_asc' ? 'selected' : ''; ?>>Salary (Low to High)</option>
                                        <option value="salary_desc" <?php echo $sort == 'salary_desc' ? 'selected' : ''; ?>>Salary (High to Low)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Apply
                                    </button>
                                </div>
                            </form>
                        </div>

                        <?php
                        if(mysqli_num_rows($result) > 0){
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Name</th>";
                            echo "<th>Address</th>";
                            echo "<th>Salary</th>";
                            echo "<th>Actions</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            $counter = 1; // Initialize counter
                            while($row = mysqli_fetch_array($result)){
                                echo "<tr>";
                                echo "<td>" . $counter . "</td>"; // Use counter instead of ID
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['address'] . "</td>";
                                echo "<td>$" . number_format($row['salary']) . "</td>";
                                echo "<td>";
                                echo '<div class="action-buttons">';
                                echo '<a href="read.php?id='. $row['id'] .'" class="btn btn-view" title="View Record"><i class="fa fa-eye"></i></a>';
                                echo '<a href="update.php?id='. $row['id'] .'" class="btn btn-edit" title="Update Record"><i class="fa fa-pencil"></i></a>';
                                echo '<a href="delete.php?id='. $row['id'] .'" class="btn btn-delete" title="Delete Record"><i class="fa fa-trash"></i></a>';
                                echo '</div>';
                                echo "</td>";
                                echo "</tr>";
                                $counter++; // Increment counter
                            }
                            echo "</tbody>";                            
                            echo "</table>";
                            echo '</div>';
                        } else{
                            echo '<div class="no-records">';
                            echo '<p class="lead"><em>No records were found.</em></p>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<?php
// Close connection
mysqli_close($conn);
?>
