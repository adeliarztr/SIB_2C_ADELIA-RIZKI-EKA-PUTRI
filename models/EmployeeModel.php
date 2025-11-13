<?php 
/** 
 * FILE: models/EmployeeModel.php 
 * FUNGSI: Berisi semua operasi database untuk tabel employees 
 */ 
 
class EmployeeModel { 
    private $conn; 
private $table_name = "employees";


// Constructor
public function __construct($db) {
    $this->conn = $db;
}

// METHOD 1: Read semua employees
public function getAllEmployees() {
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

// METHOD 2: Create employee baru
public function createEmployee($data) {
    $query = "INSERT INTO " . $this->table_name . " (first_name, last_name, email, department, position, salary, hire_date) VALUES (:first_name, :last_name, :email, :department, :position, :salary, :hire_date)";

    $stmt = $this->conn->prepare($query);

    // Bind parameters untuk keamanan (mencegah SQL injection)
    $stmt->bindParam(":first_name", $data['first_name']);
    $stmt->bindParam(":last_name", $data['last_name']);
    $stmt->bindParam(":email", $data['email']);
    $stmt->bindParam(":department", $data['department']);
    $stmt->bindParam(":position", $data['position']);
    $stmt->bindParam(":salary", $data['salary']);
    $stmt->bindParam(":hire_date", $data['hire_date']);

    return $stmt->execute();
}

// METHOD 3: Update employee
public function updateEmployee($id, $data) {
    $query = "UPDATE " . $this->table_name . "
              SET first_name = :first_name, last_name = :last_name,
                  email = :email, department = :department,
                  position = :position, salary = :salary, hire_date = :hire_date
              WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":first_name", $data['first_name']);
    $stmt->bindParam(":last_name", $data['last_name']);
    $stmt->bindParam(":email", $data['email']);
    $stmt->bindParam(":department", $data['department']);
    $stmt->bindParam(":position", $data['position']);
    $stmt->bindParam(":salary", $data['salary']);
    $stmt->bindParam(":hire_date", $data['hire_date']);

    return $stmt->execute();
}
// METHOD 4: Delete employee
public function deleteEmployee($id) {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}

// METHOD 5: Get single employee by ID
public function getEmployeeById($id) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// METHOD 6: Get data dari VIEW employee_summary
public function getEmployeeSummary() {
    $query = "SELECT * FROM employee_summary";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}


// METHOD 7: Get data dari VIEW department_stats
public function getDepartmentStats() {
    $query = "SELECT * FROM department_stats";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

// METHOD 8: Get data dari MATERIALIZED VIEW dashboard_summary
public function getDashboardSummary() {
    $query = "SELECT * FROM dashboard_summary";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// METHOD 9: Refresh materialized view
public function refreshDashboard() {
    $query = "REFRESH MATERIALIZED VIEW dashboard_summary";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute();
}
// Di dalam class EmployeeModel { ... }

public function getSalaryStats() {
    $sql = "SELECT department,
                   AVG(salary)::numeric(12,2) AS avg_salary,
                   MAX(salary) AS max_salary,
                   MIN(salary) AS min_salary
            FROM {$this->table_name}
            GROUP BY department
            ORDER BY department";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt;
}

public function getTenureStats() {
    $sql = "SELECT category, COUNT(*) AS employee_count FROM (
                SELECT id, CONCAT(first_name,' ', last_name) AS full_name, hire_date,
                    CASE
                        WHEN DATE_PART('year', AGE(CURRENT_DATE, hire_date)) = 0 THEN 'Junior'
                        WHEN DATE_PART('year', AGE(CURRENT_DATE, hire_date)) BETWEEN 1 AND 3 THEN 'Middle'
                        ELSE 'Senior'
                    END AS category
                FROM {$this->table_name}
            ) t
            GROUP BY category
            ORDER BY CASE WHEN category='Junior' THEN 1 WHEN category='Middle' THEN 2 ELSE 3 END";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt;
}

public function getEmployeeOverview() {
    $sql = "SELECT
                COUNT(*) AS total_employees,
                SUM(salary) AS total_salary_per_month,
                AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, hire_date)))::numeric(5,2) AS avg_years_service
            FROM {$this->table_name}";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Methods to call the Postgres table functions (optional)
public function getEmployeesBySalaryRange($min, $max) {
    $sql = "SELECT * FROM get_employees_by_salary_range(:min_salary, :max_salary)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':min_salary', $min);
    $stmt->bindValue(':max_salary', $max);
    $stmt->execute();
    return $stmt;
}

public function getDepartmentSummaryFunction() {
    $sql = "SELECT * FROM get_department_summary()";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt;
}

}
?>