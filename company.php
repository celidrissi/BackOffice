<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include './connect.php'
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
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Employee</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<input type="hidden" id='edit-employee-id'/>
					<div class="form-group">
						<label for="edit-employee-company">Company</label>
						<select class="form-control" id='edit-employee-company'>
						</select>
					</div>
					<div class="form-group">
						<label for="edit-employee-first-name">First Name</label>
						<input type="text" class="form-control" id="edit-employee-first-name">
					</div>
					<div class="form-group">
						<label for="edit-employee-name">Name</label>
						<input type="text" class="form-control" id="edit-employee-name">
					</div>
					<div class="form-group">
						<label for="edit-employee-first-age">Age</label>
						<input type="text" class="form-control" id="edit-employee-age" pattern="[0-9][0-9]">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger mr-auto" onclick='deleteEmployee()'>Delete</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save" data-id="lol">Save changes</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Employee</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<input type="hidden" id='add-employee-company-id' value="<?php echo $_GET['company_id'] ?>">
					<div class="form-group">
						<label for="add-employee-company">Company</label>
						<select class="form-control" id='add-employee-company'>
						</select>
					</div>
					<div class="form-group">
						<label for="add-employee-first-name">First Name</label>
						<input type="text" class="form-control" id="add-employee-first-name">
					</div>
					<div class="form-group">
						<label for="add-employee-name">Name</label>
						<input type="text" class="form-control" id="add-employee-name">
					</div>
					<div class="form-group">
						<label for="add-employee-age">Age</label>
						<input type="number" class="form-control" id="add-employee-age" pattern="[0-9][0-9]">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="add">Add Company</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal End-->
<!-- container -->
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
		<?php
		$result = action('companies_employees',$_GET['company_id']);
		?>
		<!-- List -->
		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="row">
                <h2 class="mx-auto mt-4">
                    List of Employees
				    <button type="button" class="btn btn-primary btn-sm" id="add-button" href="#add" data-toggle="modal" data-target="#addModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </h2>
            </div>
            <div class="row">
                <table class="table table-striped table-sm text-center">
					<thead>
						<tr>
							<th>Name</th>
							<th>First Name</th>
							<th>Age</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
					<?php
					while($obj = $result->fetch_object()){
						echo "
							<tr id='employee-$obj->id'>
							    <div hidden id='employee-$obj->id-id-company'>$obj->id_company</div>
								<td id='employee-$obj->id-name'>$obj->name</td>
								<td id='employee-$obj->id-first-name'>$obj->first_name</td>
								<td id='employee-$obj->id-age'>$obj->age</td>
								<td>
									<a href=\"#edit\" data-toggle=\"modal\" data-target=\"#editModal\" id='show-modal' data-id='$obj->id'>
										<i class=\"fas fa-edit\" ></i>
									</a>
								</td>
							</tr>";
					}
					$result->close();
					?>
					</tbody>
				</table>
            </div>
		</main>
		<!-- List End -->
	</div>
</div>
<!-- Container End -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script>
	let selectCreated = false;
	let selectedIndex;
	$(document).ready(function() {
		$('#editModal').on('shown.bs.modal', function (event) {
			const id = $(event.relatedTarget).data('id');
			const id_company = document.getElementById('employee-'+id+'-id-company').innerText;
			const name = document.getElementById('employee-'+id+'-name').innerText;
			const first_name = document.getElementById('employee-'+id+'-first-name').innerText;
			const age = document.getElementById('employee-'+id+'-age').innerText;

			if (!selectCreated){
				const select = document.getElementById('edit-employee-company');
				let data = new FormData();
				let selectedIndex;
				data.append("action", "companies_list");
				const companies = request(data, true).then((companies) => {
					companies.forEach(function(company) {
						const option = document.createElement('option');
						if (id_company === company.id) selectedIndex = company.id;
						option.appendChild( document.createTextNode(company.name) );
						option.value = company.id;
						select.append(option);
					});
					document.getElementById('edit-employee-company').selectedIndex = selectedIndex - 1;
				});
				selectCreated = true;
			}

			document.getElementById('edit-employee-id').value = id;
			document.getElementById('edit-employee-company').value = id_company;
			document.getElementById('edit-employee-name').value = name;
			document.getElementById('edit-employee-first-name').value = first_name;
			document.getElementById('edit-employee-age').value = age;
		});
		$("#save").on("click", function(event){
			const id = document.getElementById('edit-employee-id').value;

			const old_id_company = document.getElementById('employee-'+id+'-id-company').innerText;
			const old_name = document.getElementById('employee-'+id+'-name').innerText;
			const old_first_name = document.getElementById('employee-'+id+'-first-name').innerText;
			const old_age = document.getElementById('employee-'+id+'-age').innerText;

			const new_company_id = document.getElementById('edit-employee-company').value;
			const new_name = document.getElementById('edit-employee-name').value;
			const new_first_name = document.getElementById('edit-employee-first-name').value;
			const new_age = document.getElementById('edit-employee-age').value;

			if (old_id_company !== new_company_id || old_name !== new_name || old_first_name !== new_first_name || old_age !== new_age) {
				if (old_id_company !== new_company_id) {
					let data = new FormData();
					data.append("action", "employee_update_id_company");
					data.append("employee_id_company", new_company_id);
					data.append("employee_id", id);
					request(data, false);
				}
				if (old_name !== new_name) {
					let data = new FormData();
					data.append("action", "employee_update_name");
					data.append("employee_name", new_name);
					data.append("employee_id", id);
					request(data, false);
				}
				if (old_first_name !== new_first_name) {
					let data = new FormData();
					data.append("action", "employee_update_first_name");
					data.append("employee_first_name", new_first_name);
					data.append("employee_id", id);
					request(data, false);
				}
				if (old_age !== new_age) {
					let data = new FormData();
					data.append("action", "employee_update_age");
					data.append("employee_age", new_age);
					data.append("employee_id", id);
					request(data, false);
				}
			} else {
				console.log('No modification');
			}
		});
		$("#addModal").on('shown.bs.modal', function (event) {
			const select = document.getElementById('add-employee-company');
			const id_company = document.getElementById('add-employee-company-id').value;
			let data = new FormData();
			let selectedIndex;
			data.append("action", "companies_list");
			const companies = request(data, true).then((companies) => {
				companies.forEach(function(company) {
					const option = document.createElement('option');
					option.appendChild( document.createTextNode(company.name) );
					if (id_company === company.id) selectedIndex = company.id;
					option.value = company.id;
					select.append(option);
				});
				document.getElementById('add-employee-company').selectedIndex = selectedIndex - 1;
			});
		});
		$('#add').click(function (event) {
			const new_employee_company_id = document.getElementById('add-employee-company').value;
			const new_employee_first_name = document.getElementById('add-employee-first-name').value;
			const new_employee_name = document.getElementById('add-employee-name').value;
			const new_employee_age = document.getElementById('add-employee-age').value;
			let data = new FormData();
			data.append("action", "employee_add");
			data.append("employee_id_company", new_employee_company_id);
			data.append("employee_name", new_employee_name);
			data.append("employee_first_name", new_employee_first_name);
			data.append("employee_age", new_employee_age);
			request(data);
		});
	});
	function deleteEmployee(){
		const employee_id = document.getElementById('edit-employee-id').value;
		let data = new FormData();
		data.append("action", "employee_delete");
		data.append("employee_id", employee_id);
		request(data);
	}
	async function request(data, json){
		if (json) data.append("json", true);
		const requestOptions = {
			method: 'POST',
			body: data,
			redirect: 'follow',
			headers: new Headers({'accept': 'application/json'})
		};
		const url = './request.php';
		return await fetch(url, requestOptions)
			.then((response)=> { if (json) { return response.json();} else { document.location.reload(true);} })
			.catch(error => console.log('error', error));
	}
</script>
</html>

