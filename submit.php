<?php
// db_config.php already included here
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data (example)
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

    // Insert data into database or process as needed
    $sql = "INSERT INTO tenantsheet (tradename, store_premises, store_location, ownership, company_name, business_address, tin, office_tel, tenant_representative, contact_person, position, contact_tel, mobile, email, prepared_by) 
            VALUES ('$tradename', '$store_premises', '$store_location', '$ownership', '$company_name', '$business_address', '$tin', '$office_tel', '$tenant_representative', '$contact_person', '$position', '$contact_tel', '$mobile', '$email', '$prepared_by')";

    if (mysqli_query($conn, $sql)) {
        // Redirect with success parameter
        header("Location: form.php?success=true");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
