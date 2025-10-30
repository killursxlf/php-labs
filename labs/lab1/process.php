<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fName = trim($_POST["fName"]);
    $lName = trim($_POST["lName"]);

    if (empty($fName) || empty($lName)) {
        echo "Error: all fields are required";
    } else {
        echo "Hello, " . htmlspecialchars($fName) . " " . htmlspecialchars($lName) . "!";
    }
} else {
    echo "no data received";
}
?>
