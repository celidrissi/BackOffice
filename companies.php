<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Company</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<input type="hidden" id='edit-company-id'/>
					<div class="form-group">
						<label for="edit-company-name">Company Name</label>
						<input type="text" class="form-control" id="edit-company-name">
					</div>
					<div class="form-group">
						<label for="edit-company-department">Department</label>
						<input type="text" class="form-control" id="edit-company-department" pattern="[0-9][0-9]">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger mr-auto" onclick='deleteCompany()'>Delete</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save">Save changes</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Company</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="add-company-name">Company Name</label>
						<input type="text" class="form-control" id="add-company-name">
					</div>
					<div class="form-group">
						<label for="add-company-department">Department</label>
						<input type="text" class="form-control" id="add-company-department" pattern="[0-9][0-9]">
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
<!-- Modal -->
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
		<?php
		$result = action('companies_list');
		?>
		<!-- List -->
		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="row">
                <h2 class="mx-auto mt-4">
                    List of Companies
				    <button type="button" class="btn btn-primary btn-sm" id="add-button" href="#add" data-toggle="modal" data-target="#addModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </h2>
            </div>
            <div class="row">
                <table class="table table-striped table-sm text-center" >
					<thead>
					<tr>
						<th>Name</th>
						<th>Dept.</th>
						<th>Employees</th>
						<th>Edit</th>
					</tr>
					</thead>
					<tbody>
					<?php
						if ($result !== null){
							while($obj = $result->fetch_object()){
								$id = $obj->id;
								$count = action('companies_employees', $id);
								echo "
								<tr id='company-$id'>
									<td id='company-$id-name'>$obj->name</td>
									<td id='company-$id-department'>$obj->department</td>
									<td>
										<a href='./company.php?company_id=$id'>
											<i class=\"fas fa-users\">
												(".$count->num_rows.")
											</i>
										</a>
									</td>
									<td>
										<a href=\"#edit\" data-toggle=\"modal\" data-target=\"#editModal\" data-id='$obj->id'>
  											<i class=\"fas fa-edit\"></i>
										</a>
									</td>
								</tr>";
							}
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
	$(document).ready(function() {
		$('#add').click(function (event) {
			const new_name = document.getElementById('add-company-name').value;
			const new_department = document.getElementById('add-company-department').value;
			let data = new FormData();
			data.append("action", "company_add");
			data.append("company_name", new_name);
			data.append("company_department", new_department);
			request(data);
		});
		$('#editModal').on('show.bs.modal', function (event) {
			var id = $(event.relatedTarget).data('id');
			var name = document.getElementById('company-'+id+'-name').innerText;
			var department = document.getElementById('company-'+id+'-department').innerText;
			document.getElementById('edit-company-id').value = id;
			document.getElementById('edit-company-name').value = name;
			document.getElementById('edit-company-department').value = department;
		});
		$("#save").on("click", function(event){
			const id = document.getElementById('edit-company-id').value;
			const old_name = document.getElementById('company-' + id + '-name').innerText;
			const old_department = document.getElementById('company-' + id + '-department').innerText;
			const new_name = document.getElementById('edit-company-name').value;
			const new_department = document.getElementById('edit-company-department').value;
			if (old_name !== new_name || old_department !== new_department) {
				if (old_department !== new_department) {
					let data = new FormData();
					data.append("action", "company_update_department");
					data.append("company_department", new_department);
					data.append("company_id", id);
					request(data);
				}
				if (old_name !== new_name) {
					let data = new FormData();
					data.append("action", "company_update_name");
					data.append("company_name", new_name);
					data.append("company_id", id);
					request(data);
				}
			} else {
				console.log('Pas de modificiation');
			}
		});
	});
	function deleteCompany(){
		const company_id = document.getElementById('edit-company-id').value;
		let data = new FormData();
		data.append("action", "company_delete");
		data.append("company_id", company_id);
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

