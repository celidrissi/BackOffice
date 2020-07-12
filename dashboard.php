<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$mysqli = new mysqli("localhost", "root", "root", "back_office");
include './connect.php';
include './request.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="El idrissi Chafik">
	<title>Back Office</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>
<body>
<!-- Container -->
<div class="container-fluid">
	<div class="row">
		<!-- SideBar -->
		<nav id="sidebar" class="col-md-3 col-lg-2 col-sm-12 d-md-block bg-light sidebar border">
			<div class="sidebar-sticky pt-3 mb-3">
				<ul class="nav flex-column">
					<li class="nav-item mx-auto" id="sidebar-dashboard">
						<a class="nav-link" href="./dashboard.php">
							<i class="fas fa-home"></i> Dashboard
						</a>
					</li>
					<li class="nav-item mx-auto" id="sidebar-companies">
						<a class="nav-link" href="./companies.php">
							<i class="fas fa-users"></i> Companies
						</a>
					</li>
				</ul>
			</div>
		</nav>
		<!-- SideBar End -->
		<!-- Dashboard -->
		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
			<div class="row">
				<h2 class="mx-auto mt-4">Dashboard</h2>
			</div>
			<div class="row">
				<div id="chart-pie" class="mt-4 mb-4 col-md-6 col-lg-6 col-sm-6 col-xs-12">
					<canvas id="canvas-pie" data-pie="<?php echo action('employee_per_age')?>" height="200"></canvas>
				</div>
				<div id="chart-bar" class="mt-4 mb-4 col-md-6 col-lg-6 col-sm-6 col-xs-12">
					<canvas id="canvas-bar" data-bar="<?php echo str_replace('"', "'", action('employee_per_companies'));?>" height="200"></canvas>
				</div>
				<table id='table-employee-per-department' class="mt-4 mb-4 col-md-12 col-lg-12 col-sm-12 col-xs-12 table table-striped table-sm text-center">
					<thead>
					<th colspan="4">Employees per department</th>
					<tr>
						<th>Dept.</th>
						<th>First Name</th>
						<th>Name</th>
						<th>Age</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$result = action('employee_per_department');
					while($obj = $result->fetch_object()) {
						echo "
							<tr id='employee-per-department'>
								<td id='employee-$obj->id-department'> $obj->department </td>
								<td id='employee-$obj->id-first-name'>$obj->first_name</td>
								<td id='employee-$obj->id-name'> $obj->name </td>
								<td id='employee-$obj->id-age'> $obj->age </td>
							</tr>";
					}
					?>
					</tbody>
				</table>
			</div>
		</main>
	</div>
</div>
<!-- Container End -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.0-alpha/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script>
	/* ChartJS Pie (BreakDown Of Employees) */
	const pie_data = [{
		data: JSON.parse(document.getElementById('canvas-pie').dataset.pie),
		backgroundColor: [
			"rgb(255, 99, 132)",
			"rgb(54, 162, 235)",
			"rgb(255, 205, 86)",
			"rgb(86,255,142)",
			"rgb(210,86,255)"
		]
	}];
	const pie_options = {
		tooltips: {
			enabled: true,
		},
		title: {
			display: true,
			text: "Breakdown of employees (by age)"
		},
	};
	const pie_ctx = document.getElementById("canvas-pie");
	let pie_myChart = new Chart(pie_ctx, {
		type: 'doughnut',
		data: {
			labels: ["<20", "20-30", "30-40", "40-50", "50>"],
			datasets: pie_data
		},
		options: pie_options
	});

	/* ChartJS Bar (Number of Employees per Company) */
	bar_data_full = JSON.parse((document.getElementById('canvas-bar').dataset.bar).replace(/\'/g, '"'));
	bar_data_array = new Array();
	bar_labels_array = new Array();
	Object.keys(bar_data_full).forEach(key => {
		bar_data_array.push(bar_data_full[key].count);
		bar_labels_array.push(bar_data_full[key].name)
	});



	const bar_data = [{
		data: bar_data_array,
		backgroundColor: "rgb(54, 162, 235)"
	}];
	const bar_options = {
		responsive: true,
		legend: {
			display: false
		},
		tooltips: {
			enabled: true,
		},
		title: {
			display: true,
			text: "Number of Employees per Company"
		},
		hover: {
			mode: 'label'
		},
		scales: {
			yAxes: [{
				ticks: {
					stepSize: 1,
				}
			}]
		}
	};
	const bar_ctx = document.getElementById("canvas-bar");
	let bar_myChart = new Chart(bar_ctx, {
		type: 'bar',
		data: {
			labels: bar_labels_array,
			datasets: bar_data
		},
		options: bar_options
	});
</script>
</html>

