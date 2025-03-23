<?php
// db_config.php should be in the same directory or update the path if it's elsewhere
require 'db_config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data
    $business_type = $_POST['business_type'];
    $requirements = isset($_POST['requirements']) ? implode(", ", $_POST['requirements']) : ''; // Combine selected requirements into a string

    // Handle the uploaded file
    if (isset($_FILES['documents']) && $_FILES['documents']['error'] == 0) {
        $file_name = $_FILES['documents']['name'];
        $file_tmp = $_FILES['documents']['tmp_name'];
        $file_path = "uploads/" . $file_name;

        // Move the uploaded file to the desired directory
        move_uploaded_file($file_tmp, $file_path);
    } else {
        $file_name = ''; // No file uploaded
    }

    // Insert the data into the database
    $query = "INSERT INTO submissions (business_type, requirements, document_file) VALUES ('$business_type', '$requirements', '$file_name')";
    if (mysqli_query($conn, $query)) {
        echo "Form submitted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Submission Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Include Bootstrap Icons -->
    <style>
        body {
            background-color: #eafaf1; /* Light green background */
            padding: 20px;
        }
        .form-container {
            max-width: 800px;
            margin: auto;
            background: #fff; /* White background for the form */
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
            background-color: #28a745; /* Green color for submit button */
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838; /* Darker green on hover */
            border-color: #1e7e34;
        }
        .btn-secondary {
            background-color: #ffffff; /* White background for the next button */
            color: #28a745; /* Green text */
            border: 1px solid #28a745;
        }
        .btn-secondary:hover {
            background-color: #e2f0e2; /* Light green on hover */
        }
        .form-label {
            font-weight: bold;
        }
        .form-check-label {
            color: #333;
        }
        h3 {
            color: #28a745; /* Green for headers */
        }
        h5, h6 {
            color: #28a745;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="logo-container">
        <img src="img/logo.jpg" alt="Logo">
    </div>

    <h3 class="text-center">Document Submission Form</h3>
    
    <form action="submit_form.php" method="POST" enctype="multipart/form-data">
        
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
            <h6>Corporation:</h6>
            <?php 
            $requirements = [
                "Letter of Intent/Concept Papers", "Company Profile", "SEC Registration", 
                "Secretary’s Certificate of Authorized Signatory", "BIR Form 2303", 
                "2 Valid IDs with 3 Specimen Signatures of Authorized Signatory"
            ];
            foreach ($requirements as $req) {
                echo "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='requirements[]' value='".htmlspecialchars($req, ENT_QUOTES)."' required>
                        <label class='form-check-label'>$req</label>
                      </div>";
            }
            ?>
        </div>

        <div id="sole-requirements" style="display:none;">
            <h6>Sole Proprietorship:</h6>
            <?php 
            $partnership_requirements = [
                "Letter of Intent/Concept Papers", "DTI permit", "BIR Form 2303",
                "2 Valid IDs with 3 Specimen Signatures of Authorized Signatory"
            ];
            foreach ($partnership_requirements as $req) {
                echo "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='requirements[]' value='".htmlspecialchars($req, ENT_QUOTES)."' required>
                        <label class='form-check-label'>$req</label>
                      </div>";
            }
            ?>
        </div>

        <div id="franchisee-requirements" style="display:none;">
            <h6>Franchisee:</h6>
            <?php 
            $franchisee_requirements = [
                "Letter of Intent/Concept Papers", "Enrollment letter from Franchisory Dealer", 
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
            <label for="upload-input" class="form-label">Upload All Documents (in one file):</label>
            <input type="file" id="upload-input" name="documents" accept=".pdf,.zip" required class="form-control">
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
        
        // Reset all checkboxes and disable file input
        corporationCheckboxes.style.display = "none";
        soleCheckboxes.style.display = "none";
        franchiseCheckboxes.style.display = "none";
        uploadInput.disabled = false;
        
        // Show and enable relevant checkboxes based on selected type
        if (type === 'corporation') {
            corporationCheckboxes.style.display = "block";
        } else if (type === 'sole') {
            soleCheckboxes.style.display = "block";
        } else if (type === 'franchisee') {
            franchiseCheckboxes.style.display = "block";
        }

        // Only one file upload allowed
        document.getElementById('upload-input').required = true;
    }
</script>

</body>
</html>
