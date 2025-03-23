<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tradename = $_POST['tradename'];
    $store_premises = $_POST['store_premises'];
    $store_location = $_POST['store_location'];
    $ownership = $_POST['ownership'];
    $company_name = $_POST['company_name'];
    $business_address = $_POST['business_address'];
    $tin = $_POST['tin'];
    $office_tel = $_POST['office_tel'];
    $tenant_representative = $_POST['tenant_representative'];
    $contact_person = $_POST['contact_person'];
    $position = $_POST['position'];
    $contact_tel = $_POST['contact_tel'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $prepared_by = $_POST['prepared_by'];

    // Handling multiple checkbox selections for requirements
    $requirements = isset($_POST['requirements']) ? implode(", ", $_POST['requirements']) : "";

    $sql = "INSERT INTO tenants (tradename, store_premises, store_location, ownership, company_name, business_address, tin, office_tel, tenant_representative, contact_person, position, contact_tel, mobile, email, prepared_by, requirements)
            VALUES ('$tradename', '$store_premises', '$store_location', '$ownership', '$company_name', '$business_address', '$tin', '$office_tel', '$tenant_representative', '$contact_person', '$position', '$contact_tel', '$mobile', '$email', '$prepared_by', '$requirements')";

    if ($conn->query($sql) === TRUE) {
        header("Location: registration.php"); // Redirect to registration page
        exit(); // Ensure script stops executing after redirect
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
