<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start a transaction
    mysqli_begin_transaction($conn);
    error_log("Transaction started.");

    // Sanitize and fetch form data for tenant info
    $tradename = mysqli_real_escape_string($conn, $_POST['tradename']);
    $store_premises = mysqli_real_escape_string($conn, $_POST['store_premises']);
    $store_location = mysqli_real_escape_string($conn, $_POST['store_location']);
    $ownership = mysqli_real_escape_string($conn, $_POST['ownership']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $business_address = mysqli_real_escape_string($conn, $_POST['business_address']);
    $tin = mysqli_real_escape_string($conn, $_POST['tin']);
    $office_tel = mysqli_real_escape_string($conn, $_POST['office_tel']);
    $tenant_representative = mysqli_real_escape_string($conn, $_POST['tenant_representative']);
    $contact_person = mysqli_real_escape_string($conn, $_POST['contact_person']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $contact_tel = mysqli_real_escape_string($conn, $_POST['contact_tel']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $prepared_by = mysqli_real_escape_string($conn, $_POST['prepared_by']);

    // Sanitize and fetch form data for document submission
    $business_type = mysqli_real_escape_string($conn, $_POST['business_type']);
    $requirements = isset($_POST['requirements']) && !empty($_POST['requirements']) ? implode(", ", $_POST['requirements']) : ''; // Combine selected requirements into a string
    if (empty($_POST['requirements'])) {
        echo "At least one requirement must be selected.";
        exit;
    }

    // Handle the uploaded file
    if (isset($_FILES['documents']) && $_FILES['documents']['error'] == 0) {
        $file_name = $_FILES['documents']['name'];
        $file_tmp = $_FILES['documents']['tmp_name'];
        $file_size = $_FILES['documents']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf']; // Only allow PDF files

        // Check file extension
        if (in_array($file_ext, $allowed_extensions)) {
            // Check file size (5MB max, for example)
            if ($file_size <= 5 * 1024 * 1024) {
                $file_path = "uploads/" . $file_name;
                // Move the uploaded file to the desired directory
                if (move_uploaded_file($file_tmp, $file_path)) {
                    error_log("File uploaded successfully: " . $file_name);
                } else {
                    error_log("Failed to move uploaded file.");
                    echo "Failed to move uploaded file.";
                    mysqli_rollback($conn); // Rollback transaction
                    exit;
                }
            } else {
                echo "File size exceeds the maximum limit (5MB).";
                mysqli_rollback($conn); // Rollback transaction
                error_log("File size exceeds limit.");
                exit;
            }
        } else {
            echo "Invalid file type. Only PDF files are allowed.";
            mysqli_rollback($conn); // Rollback transaction
            error_log("Invalid file type.");
            exit;
        }
    } else {
        $file_name = ''; // No file uploaded
    }

    // Insert tenant data into the database
    $query_tenantsheet = "INSERT INTO tenantsheet (tradename, store_premises, store_location, ownership, company_name, business_address, tin, office_tel, tenant_representative, contact_person, position, contact_tel, mobile, email, prepared_by) 
                     VALUES ('$tradename', '$store_premises', '$store_location', '$ownership', '$company_name', '$business_address', '$tin', '$office_tel', '$tenant_representative', '$contact_person', '$position', '$contact_tel', '$mobile', '$email', '$prepared_by')";
    
    // Insert document submission data into the database
    $query_submission = "INSERT INTO submissions (business_type, requirements, document_file) 
                         VALUES ('$business_type', '$requirements', '$file_name')";

    if (mysqli_query($conn, $query_tenantsheet) && mysqli_query($conn, $query_submission)) {
        mysqli_commit($conn); // Commit transaction
        error_log("Form submitted successfully.");
        echo "Form submitted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
        mysqli_rollback($conn); // Rollback transaction
        error_log("Error during submission: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Information and Document Submission Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #eafaf1;
            padding: 20px;
        }
        .form-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            max-width: 200px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-secondary {
            background-color: #ffffff;
            color: #28a745;
            border: 1px solid #28a745;
        }
        .btn-secondary:hover {
            background-color: #e2f0e2;
        }
        .form-label {
            font-weight: bold;
        }
        h3, h5 {
            color: #28a745;
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="logo-container">
        <img src="img/logo.jpg" alt="xentro mall logo">
    </div>

    <h3 class="text-center">Xentro Mall - Tenant Information and Document Submission</h3>

    <form action="" method="POST" enctype="multipart/form-data">
        
        <!-- Tenant Information Fields -->
        <div class="mb-3">
            <label class="form-label">Trade Name</label>
            <input type="text" class="form-control" name="tradename" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Use of Store Premises</label>
            <input type="text" class="form-control" name="store_premises" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location/s</label>
            <input type="text" class="form-control" name="store_location" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ownership</label>
            <select class="form-select" name="ownership" required>
                <option value="Corporation">Corporation</option>
                <option value="Sole Proprietor">Sole Proprietor</option>
                <option value="Partnership">Partnership</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Company Name</label>
            <input type="text" class="form-control" name="company_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Business Address</label>
            <textarea class="form-control" name="business_address" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tax Identification Number (TIN)</label>
            <input type="text" class="form-control" name="tin">
        </div>
        <div class="mb-3">
            <label class="form-label">Office Telephone Number</label>
            <input type="text" class="form-control" name="office_tel">
        </div>
        <div class="mb-3">
            <label class="form-label">Tenant Representative</label>
            <input type="text" class="form-control" name="tenant_representative">
        </div>
        <div class="mb-3">
            <label class="form-label">Contact Person</label>
            <input type="text" class="form-control" name="contact_person">
        </div>
        <div class="mb-3">
            <label class="form-label">Position</label>
            <input type="text" class="form-control" name="position">
        </div>
        <div class="mb-3">
            <label class="form-label">Telephone Number</label>
            <input type="text" class="form-control" name="contact_tel">
        </div>
        <div class="mb-3">
            <label class="form-label">Mobile Number</label>
            <input type="text" class="form-control" name="mobile">
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email">
        </div>

        <h5>Consent</h5>
        <p>
            I hereby give full consent to the Lessor to collect, record, organize, store, update, use, consolidate, block, erase or process information, whether personal, sensitive or privileged, pertaining to myself and the subject hereof.
        </p>

        <div class="mb-3">
            <label class="form-label">Prepared by:</label>
            <input type="text" class="form-control" name="prepared_by">
        </div>

        <!-- Document Submission Fields -->
        <div class="mb-3">
            <label class="form-label">Business Type</label>
            <div>
                <label>
                    <input type="radio" name="business_type" value="corporation" onclick="toggleRequirements()"> Corporation
                </label>
                <label>
                    <input type="radio" name="business_type" value="sole" onclick="toggleRequirements()"> Sole Proprietorship
                </label>
                <label>
                    <input type="radio" name="business_type" value="franchisee" onclick="toggleRequirements()"> Franchisee
                </label>
            </div>
        </div>

        <div id="corporation-requirements" style="display:none;">
            <h6>Corporation Requirements:</h6>
            <?php 
            $corporation_requirements = [
                "Letter of Intent/Concept Papers", "Company Profile", "SEC Registration", 
                "Secretary’s Certificate of Authorized Signatory", "BIR Form 2303", 
                "2 Valid IDs with 3 Specimen Signatures of Authorized Signatory"
            ];
            foreach ($corporation_requirements as $req) {
                echo "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='requirements[]' value='".htmlspecialchars($req, ENT_QUOTES)."' required>
                        <label class='form-check-label'>$req</label>
                      </div>";
            }
            ?>
        </div>

        <div id="sole-requirements" style="display:none;">
            <h6>Sole Proprietorship Requirements:</h6>
            <?php 
            $sole_requirements = [
                "Letter of Intent/Concept Papers", "DTI permit", "BIR Form 2303",
                "2 Valid IDs with 3 Specimen Signatures of Authorized Signatory"
            ];
            foreach ($sole_requirements as $req) {
                echo "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='requirements[]' value='".htmlspecialchars($req, ENT_QUOTES)."' required>
                        <label class='form-check-label'>$req</label>
                      </div>";
            }
            ?>
        </div>

        <div id="franchisee-requirements" style="display:none;">
            <h6>Franchisee Requirements:</h6>
            <?php 
            $franchisee_requirements = [
                "Letter of Intent/Concept Papers", "Enrollment letter from Franchisor", 
                "Photocopy of Franchise Agreement", "BIR Form 2303", 
                "2 Valid IDs with 3 Specimen Signatures of Authorized Signatory"
            ];
            foreach ($franchisee_requirements as $req) {
                echo "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='requirements[]' value='".htmlspecialchars($req, ENT_QUOTES)."' required>
                        <label class='form-check-label'>$req</label>
                      </div>";
            }
            ?>
        </div>

        <div>
            <label for="upload-input" class="form-label">Upload All Documents:</label>
            <input type="file" id="upload-input" name="documents" accept=".pdf" required class="form-control">
        </div>

        <div class="button-group mt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="register.php" class="btn btn-secondary">
                <i class="bi bi-arrow-right-circle"></i> Next
            </a>
        </div>
    </form>
</div>

<script>
    function toggleRequirements() {
        var type = document.querySelector('input[name="business_type"]:checked').value;
        var corporationCheckboxes = document.getElementById('corporation-requirements');
        var soleCheckboxes = document.getElementById('sole-requirements');
        var franchiseCheckboxes = document.getElementById('franchisee-requirements');
        var uploadInput = document.getElementById('upload-input');
        
        corporationCheckboxes.style.display = "none";
        soleCheckboxes.style.display = "none";
        franchiseCheckboxes.style.display = "none";
        
        if (type === "corporation") {
            corporationCheckboxes.style.display = "block";
        } else if (type === "sole") {
            soleCheckboxes.style.display = "block";
        } else if (type === "franchisee") {
            franchiseCheckboxes.style.display = "block";
        }
    }
</script>

</body>
</html>
