<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Classroom</title>
    <!-- CSS styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        
        .form-container {
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .form-container label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        
        .form-container input[type="text"],
        .form-container input[type="number"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .form-container button[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .form-container button[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Add Classroom</h1>
    
    <div class="form-container">
        <form method="POST" action="add_classroom.php">
            <label for="name">Classroom Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter the classroom name" required>
            
            
            
            <button type="submit">Add Classroom</button>
        </form>
    </div>
</body>
</html>
