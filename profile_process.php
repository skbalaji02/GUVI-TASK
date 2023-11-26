<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_SESSION['stname'])) {
    $stname = $_SESSION['stname'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "registration";

        $conn = new mysqli($host, $user, $pass, $dbname);

        if (mysqli_connect_errno()) {
            die("Connection error: " . mysqli_connect_error());
        } else {
            // Update user details in the database
            $new_dob = $_POST['new_dob'];
            $new_fullname = $_POST['new_fullname'];
            $new_fav_colour = $_POST['new_fav_colour'];
            $new_fav_subject = $_POST['new_fav_subject'];

            $sql = "UPDATE user_credentials SET dob=?, fullname=?, fav_colour=?, fav_subject=? WHERE stname=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $new_dob, $new_fullname, $new_fav_colour, $new_fav_subject, $stname);
            $stmt->execute();
            $stmt->close();

            // Fetch updated details
            $sql_fetch = "SELECT dob, fullname, fav_colour, fav_subject FROM user_credentials WHERE stname=?";
            $stmt_fetch = $conn->prepare($sql_fetch);
            $stmt_fetch->bind_param("s", $stname);
            $stmt_fetch->execute();
            $result_fetch = $stmt_fetch->get_result();

            if ($result_fetch->num_rows > 0) {
                $row_fetch = $result_fetch->fetch_assoc();
                $dob = $row_fetch['dob'] ? $row_fetch['dob'] : "Not provided";
                $fullname = $row_fetch['fullname'] ? $row_fetch['fullname'] : "Not provided";
                $fav_colour = $row_fetch['fav_colour'] ? $row_fetch['fav_colour'] : "Not provided";
                $fav_subject = $row_fetch['fav_subject'] ? $row_fetch['fav_subject'] : "Not provided";
            } else {
                // User details not found
                $dob = "Not provided";
                $fullname = "Not provided";
                $fav_colour = "Not provided";
                $fav_subject = "Not provided";
            }

            $stmt_fetch->close();
            $conn->close();
        }
    }
} else {
    // User not logged in - redirect to login
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        /* Custom Styles for profile_process.php */
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(114.3deg, rgb(19, 126, 57) 0.2%, rgb(8, 65, 91) 68.5%);
            font-family: 'Poppins', sans-serif;
            color: #000000;
        }

        .container {
            max-width: 600px;
            width: 100%;
            margin: auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        h2, p, label {
            margin: 10px 0;
        }

        a {
            color: #000000;
            text-decoration: underline;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        form label {
            display: block;
            text-align: left;
            margin: 10px 0;
            color: #000000;
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #000000;
            border-radius: 5px;
            outline: none;
        }

        form input[type="submit"] {
            background: linear-gradient(114.3deg, rgb(19, 126, 57) 0.2%, rgb(8, 65, 91) 68.5%);
            color: #FFFFFF;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form input[type="submit"]:hover {
            background: linear-gradient(114.3deg, rgb(8, 65, 91) 0.2%, rgb(19, 126, 57) 68.5%);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>USER DASHBOARD</h2>
        <label>Date of Birth: <?php echo $dob; ?></label><br>
        <label>Full Name: <?php echo $fullname; ?></label><br>
        <label>Favorite Colour: <?php echo $fav_colour; ?></label><br>
        <label>Favorite Subject: <?php echo $fav_subject; ?></label><br>

        <p style="color: #19a866;">Details updated successfully!</p>
        <a href="profile.php" style="color: #000000; text-decoration: underline;">Back to Dashboard</a>
        <form action="logout.php" method="POST">
            <input type="submit" value="Logout">
        </form>
    </div>
</body>

</html>

