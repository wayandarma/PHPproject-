<?php
// Include config file
require_once "includes/config.php";

// Fetch salary data
$sql = "SELECT name, salary FROM employees ORDER BY salary DESC";
$result = mysqli_query($conn, $sql);

$names = [];
$salaries = [];

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_array($result)){
        $names[] = $row['name'];
        $salaries[] = $row['salary'];
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StaffHub Manager - Salary Chart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .container-fluid {
            padding: 0;
        }

        .chart-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            height: 60vh;
            width: 100%;
            margin: 20px 0;
        }

        .page-header {
            padding: 15px 0;
        }

        .page-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            color: #2c3e50;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background-color: #6c757d;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #5a6268;
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

            .chart-card {
                padding: 15px;
                margin-bottom: 15px;
            }

            .chart-container {
                height: 50vh;
            }

            .page-header {
                padding: 10px 0;
            }

            .page-header h2 {
                font-size: 1.25rem;
            }

            .btn-back {
                width: 100%;
                justify-content: center;
                margin-top: 15px;
            }
        }

        @media (max-width: 480px) {
            .wrapper {
                padding: 5px;
            }

            .chart-container {
                height: 40vh;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="chart-card">
                        <div class="page-header">
                            <h2>Employee Salary Chart</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="salaryChart"></canvas>
                        </div>
                        <a href="index.php" class="btn-back">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>        
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salaryChart');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($names); ?>,
                datasets: [{
                    label: 'Employee Salary ($)',
                    data: <?php echo json_encode($salaries); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            },
                            font: {
                                size: 11
                            }
                        },
                        title: {
                            display: true,
                            text: 'Salary ($)',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 11
                            }
                        },
                        title: {
                            display: true,
                            text: 'Employee Name',
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Employee Salary Distribution',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 11
                            },
                            padding: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Salary: $' + context.raw.toLocaleString();
                            }
                        },
                        titleFont: {
                            size: 12
                        },
                        bodyFont: {
                            size: 11
                        },
                        padding: 8
                    }
                }
            }
        });
    </script>
</body>
</html>
