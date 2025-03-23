<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tesys";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenant_name = $_POST['tenant_name'];
    $scope_of_work = $_POST['scope_of_work'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $time_from = $_POST['time_from'];
    $time_to = $_POST['time_to'];

    // Collect services needed
    $services_needed = [];
    if (isset($_POST['security_posting'])) {
        $services_needed[] = 'Security Posting';
    }
    if (isset($_POST['janitorial_deployment'])) {
        $services_needed[] = 'Janitorial Deployment';
    }
    if (isset($_POST['maintenance'])) {
        $services_needed[] = 'Maintenance';
    }
    $services_needed = json_encode($services_needed);  // Convert array to JSON

    $personnel = json_encode($_POST['personnel']);

    // Insert into database (omit permit_no since it's auto-incremented)
    $sql = "INSERT INTO work_permits (tenant_name, scope_of_work, date_from, date_to, time_from, time_to, services_needed, personnel)
            VALUES ('$tenant_name', '$scope_of_work', '$date_from', '$date_to', '$time_from', '$time_to', '$services_needed', '$personnel')";

    if ($conn->query($sql) === TRUE) {
        echo "Work permit submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Work Permit Form</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { width: 70%; margin: auto; border: 2px solid #28a745; padding: 20px; background: white; border-radius: 10px; }
        .header { text-align: center; font-size: 24px; font-weight: bold; color: #28a745; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #28a745; padding: 8px; text-align: left; }
        input, textarea, select { width: 100%; padding: 5px; border: 1px solid #28a745; border-radius: 5px; }
        .signature { margin-top: 20px; display: flex; justify-content: space-between; }
        .signature div { width: 30%; text-align: center; }
        button { background-color: #28a745; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .checkbox-group { display: flex; align-items: center; }
        .checkbox-group input { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Xentro Mall - Work Permit</div>
        <form method="POST">
            <table>
                <tr>
                    <th>Permit No.</th>
                    <td><input type="text" name="permit_no" required></td>
                    <th>Date Filed</th>
                    <td><input type="date" name="date_filed" required></td>
                </tr>
                <tr>
                    <th>This is to authorize the Tenant Whose Employees or contractor's personnel names are listed below, to undertake construction and/or other works</th>
                    <td><input type="text" name="tenant_name" required></td>
                </tr>
                <tr>
                    <th>Scope of Work</th>
                    <td><textarea name="scope_of_work" required></textarea></td>
                </tr>
                <tr>
                    <th>Permit Valid From</th>
                    <td>From<input type="date" name="date_from" required disabled></td>
                    <th>To</th>
                    <td><input type="date" name="date_to" required disabled></td>
                </tr>
                <tr>
                    <th>Time From</th>
                    <td>From<input type="time" name="time_from" required disabled></td>
                    <th>To</th>
                    <td><input type="time" name="time_to" required disabled></td>
                </tr>
                <tr>
                    <th>Services Needed</th>
                    <td colspan="3">
                        <table>
                            <tr>
                                <th>Service</th>
                                <th>Rate/hr</th>
                                <th>With Charge</th>
                                <th>No Charge</th>
                            </tr>
                            <tr>
                                <td class="checkbox-group">
                                    <input type="checkbox" name="security_posting" value="Security Posting">
                                    Security Posting
                                </td>
                                <td><input type="text" name="rate_security"></td>
                                <td><input type="checkbox" name="charge_security" value="With Charge"></td>
                                <td><input type="checkbox" name="charge_security" value="No Charge"></td>
                            </tr>
                            <tr>
                                <td class="checkbox-group">
                                    <input type="checkbox" name="janitorial_deployment" value="Janitorial Deployment">
                                    Janitorial Deployment
                                </td>
                                <td><input type="text" name="rate_janitorial"></td>
                                <td><input type="checkbox" name="charge_janitorial" value="With Charge"></td>
                                <td><input type="checkbox" name="charge_janitorial" value="No Charge"></td>
                            </tr>
                            <tr>
                                <td class="checkbox-group">
                                    <input type="checkbox" name="maintenance" value="Maintenance">
                                    Maintenance
                                </td>
                                <td><input type="text" name="rate_maintenance"></td>
                                <td><input type="checkbox" name="charge_maintenance" value="With Charge"></td>
                                <td><input type="checkbox" name="charge_maintenance" value="No Charge"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>Name of Personnel</th>
                    <td colspan="3">
                        <textarea name="personnel" required></textarea>
                    </td>
                </tr>
            </table>
            <div class="signature">
                <div>
                    <hr>
                    <p>Tenant/Contractor Authorized Signatory</p>
                </div>
                <div>
                    <hr>
                    <p>Mall Admin Authorized Signatory</p>
                </div>
                <div>
                    <hr>
                    <p>OIC Guard on Duty</p>
                </div>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
