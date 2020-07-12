<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	if(isset($_POST['action'])){
		$action = $_POST['action'];
		if(isset($_POST['company_id'])){ $company_id = $_POST['company_id']; } else { $company_id = ''; }
		if(isset($_POST['company_name'])){ $company_name = $_POST['company_name'];} else { $company_name = ''; }
		if(isset($_POST['company_department'])){ $company_department = $_POST['company_department'];} else { $company_department = ''; }
		if(isset($_POST['employee_id'])){ $employee_id = $_POST['employee_id'];} else { $employee_id = ''; }
		if(isset($_POST['employee_id_company'])){ $employee_id_company = $_POST['employee_id_company'];} else { $employee_id_company = ''; }
		if(isset($_POST['employee_name'])){ $employee_name = $_POST['employee_name'];} else { $employee_name = ''; }
		if(isset($_POST['employee_first_name'])){ $employee_first_name = $_POST['employee_first_name'];} else { $employee_first_name = ''; }
		if(isset($_POST['employee_age'])){ $employee_age = $_POST['employee_age'];} else { $employee_age = ''; }
		if(isset($_POST['json'])){ $json = $_POST['json'];} else { $json = false; }
		print action($action, $company_id, $company_name, $company_department, $employee_id, $employee_id_company, $employee_name, $employee_first_name, $employee_age, $json);
	}
	function action($action, $company_id = '', $company_name= '', $company_department= '',
					$employee_id= '', $employee_id_company= '', $employee_name= '', $employee_first_name= '', $employee_age= '',
					$json = false){
		switch ($action) {
			case 'companies_list' :
				$query = "SELECT * FROM companies;";
				$result = run($query, $json, $type = 'select');
				break;
			case 'companies_employees' :
				$query = "SELECT * FROM employees where employees.id_company = $company_id;";
				$result = run($query, $json, $type = 'select');
				break;
			case 'employee_per_department' :
				$query = "SELECT companies.department, employees.id, employees.name, employees.first_name, employees.age FROM employees, companies WHERE employees.id_company = companies.id ORDER BY department";
				$result = run($query, $json, $type = 'select');
				break;
			case 'employee_per_companies' :
				$query = "SELECT c.name, count(e.id) as count FROM companies c LEFT JOIN employees e ON c.id = e.id_company GROUP BY c.name";
				$result = run($query, $json, $type = 'select');
				$array = array();
				while($row = $result->fetch_object()){
					array_push($array, $row);
				}
				$result = json_encode($array, true);
				break;
			case 'employee_per_age' :
				$query = "(SELECT COUNT(employees.age) as count from employees WHERE employees.age < 20)
						UNION ALL
						(SELECT COUNT(employees.age) as count from employees WHERE employees.age >= 20 AND employees.age <30)
						UNION ALL
						(SELECT COUNT(employees.age) as count from employees WHERE employees.age >= 30 AND employees.age <40)
						UNION ALL
						(SELECT COUNT(employees.age) as count from employees WHERE employees.age >= 40 AND employees.age <50)
						UNION ALL
						(SELECT COUNT(employees.age) as count from employees WHERE employees.age >= 50)";
				$result = run($query, $json, $type = 'select');
				$array = array();
				while($row = $result->fetch_object()){
					array_push($array, (int)$row->count);
				}
				$result = json_encode($array, true);
				break;
			case 'company_update_name' :
				$query = "UPDATE companies SET companies.name = '$company_name' where companies.id = $company_id;";
				$result = run($query, $json, $type = 'update');
				break;
			case 'company_update_department' :
				$query = "UPDATE companies SET companies.department = $company_department where companies.id = $company_id;";
				$result = run($query, $json, $type = 'update');
				break;
			case 'employee_update_id_company' :
				$query = "UPDATE employees SET id_company = $employee_id_company WHERE employees.id = $employee_id;";
				$result = run($query, $json, $type = 'update');
				break;
			case 'employee_update_name' :
				$query = "UPDATE employees SET name = '$employee_name' WHERE employees.id = $employee_id;";
				$result = run($query, $json, $type = 'update');
				break;
			case 'employee_update_first_name' :
				$query = "UPDATE employees SET first_name = '$employee_first_name' WHERE employees.id = $employee_id;";
				$result = run($query, $json, $type = 'update');
				break;
			case 'employee_update_age' :
				$query = "UPDATE employees SET age = $employee_age WHERE employees.id = $employee_id;";
				$result = run($query, $json, $type = 'update');
				break;
			case 'company_delete' :
				$query = "DELETE FROM companies WHERE companies.id = $company_id;";
				$result = run($query, $json, $type = 'delete');
				break;
			case 'employee_delete' :
				$query = "DELETE FROM employees WHERE employees.id = $employee_id;";
				$result = run($query, $json, $type = 'delete');
				break;
			case 'company_add' :
				$query = "INSERT INTO companies (id, name, department) VALUES (NULL, '$company_name', $company_department)";
				$queryCheck = "SELECT count(companies.id) as count from companies WHERE companies.name = '$company_name' AND companies.department = $company_department";
				$result = run($queryCheck, $json, $type = 'check');
				$count = $result->fetch_object()->count;
				if ($count == 0) {
					$result = run($query, $json, $type = 'add');
				} else {
					return "{\"Insert\": \"Already Exist\"}";
				}
				break;
			case 'employee_add' :
				$query = "INSERT INTO employees (id, id_company, name, first_name, age) VALUES (NULL, $employee_id_company, '$employee_first_name', '$employee_name', $employee_age)";
				$queryCheck = "SELECT count(employees.id) as count from employees WHERE employees.name = '$employee_name' AND employees.first_name = '$employee_first_name'";
				$result = run($queryCheck, $json, $type = 'check');
				$count = $result->fetch_object()->count;
				if ($count == 0) {
					$result = run($query, $json, $type = 'add');
				} else {
					return "{\"Insert\": \"Already Exist\"}";
				}
				break;
			default:
				header('Content-Type: application/json');
				return "{\"Error\": \"No corresponding action\"}";
		}
		return $result;
	}
	function run($query, $json = false, $type){

		$mysqli = new mysqli("localhost", "root", "root", "back_office");

		if ($mysqli->connect_errno) {
			printf("Can't Connect : %s\n", $mysqli->connect_error);
			exit();
		}

		$result = $mysqli->query($query);

		if ($json) {
			$myArray = array();
			while ($row = $result->fetch_object()) {
				array_push($myArray, $row);
			}
			$result = json_encode($myArray, true);
			header('Content-Type: application/json');
			return $result;
		} else {
			if ($type === 'update' || $type === 'delete' || $type === 'add') {
				header('Content-Type: application/json');
				if ($result == 1) return "{\"$type\": \"true\"}";
				if ($result == 0) return "{\"$type\": \"false\"}";
			}
			return $result;
		}
		return 'Error';
	}


