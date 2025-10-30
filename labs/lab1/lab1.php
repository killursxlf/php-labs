<?php
 // Task 1
echo "Hello, World!<br>"; // Output Hello, World!

// Task 2
$str = "str"; // string
$int = 42;       // integer
$float = 3.14;   // float
$bool = true;    // boolean

echo $str . "<br>";
echo $int . "<br>";
echo $float . "<br>";
echo $bool . "<br>";

var_dump($str);
var_dump($int);
var_dump($float);
var_dump($bool);

// Task 3
$fName = "Oleksadndr";
$lName = "Tsivil";
$fullName = $fName . " " . $lName;
echo "Full name: " . $fullName . "<br>";

// Task 4
$number = 5;
if ($number % 2 == 0) {
    echo "$number even number<br>";
} else {
    echo "$number odd number<br>";
}

// Task 5
for ($i = 1; $i <= 10; $i++) {
    echo $i . " ";
}
echo "<br>";

$j = 10;
while ($j >= 1) {
    echo $j . " ";
    $j--;
}
echo "<br>";

// Task 6
$student = [
    "Name" => "Oleksandr",
    "Surname" => "Tsivil",
    "Age" => 19,
    "Specialty" => "Computer Science"
];

foreach ($student as $key => $value) {
    echo "$key: $value<br>";
}

$student["Average"] = 89.5;

echo "<pre>";
print_r($student);
echo "</pre>";
?>
