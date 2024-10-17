<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Laptop Replacement Request</title>
    <style>
        .replacement-form-container {
            max-width: 600px;
            margin: 30px auto;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .replacement-form-container h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #34495e;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="date"]:focus,
        .form-group input[type="number"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .form-submit {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-submit:hover {
            background-color: #3498db;
        }
        @media (max-width: 768px) {
            .replacement-form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="replacement-form-container">
        <h2>Add Laptop Replacement Request</h2>
        <form method="POST" action="process_replacement.php" id="replacementForm">
            <div class="form-group">
                <label for="user_id">User:</label>
                <select name="user_id" id="user_id" required>
                    <?php
                    // Fetch all users to populate the dropdown
                    $users = $conn->query("SELECT id, name FROM users");
                    while ($user = $users->fetch_assoc()) {
                        echo "<option value='{$user['id']}'>{$user['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="replacement_date">Replacement Date:</label>
                <input type="date" name="replacement_date" id="replacement_date" required>
            </div>
            <div class="form-group">
                <label for="date_of_issue">Date of Issue:</label>
                <input type="date" name="date_of_issue" id="date_of_issue">
            </div>
            <div class="form-group">
                <label for="laptop_model">Laptop Model:</label>
                <input type="text" name="laptop_model" id="laptop_model" required>
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number:</label>
                <input type="text" name="serial_number" id="serial_number" required>
            </div>
            <div class="form-group">
                <label for="original_cost">Original Cost:</label>
                <input type="number" name="original_cost" id="original_cost" required>
            </div>
            <div class="form-group">
                <label for="amount_received">Amount Received:</label>
                <input type="number" name="amount_received" id="amount_received" required>
            </div>
            <div class="form-group">
                <label for="issue">Issue:</label>
                <textarea name="issue" id="issue" required></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <button type="submit" class="form-submit">Add Replacement Request</button>
        </form>
    </div>

    <script>
    document.getElementById('replacementForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        fetch('process_replacement.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Replacement request added successfully!');
                // Optionally, you can refresh the replacement details here
                // loadReplacementDetails(currentMonth);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
    </script>
</body>
</html>