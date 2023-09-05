
<?php
// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'weather_API';
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// API configuration
if ($_SERVER["REQUEST_METHOD"]==="POST"){
    $new_city=$_POST["inputBox"];
    $api_key = "5b6efc31d9df5b58c06a64f0cba78094";
    $url= "https://api.openweathermap.org/data/2.5/weather?q=".urlencode($new_city)."&appid=".$api_key."&units=metric";
    $json_data = file_get_contents($url);
    if ($json_data === false) {
        echo "City not found";
    }else{
    $data = json_decode($json_data, true);
    $date=date("Y-m-d");
    $temperature = $data['main']['temp'];
    $humidity = $data['main']['humidity'];
    $pressure = $data['main']['pressure'];
    $speed = $data['wind']['speed'];
    $visibility=$data['visibility'];
    $conditions = $data['weather'][0]['description'];
    $weather_main = $data['weather'][0]['main'];
    $city=$data['name'];

    $sql="INSERT INTO 7days (Dates, City, Temperature, Conditions, Humidity, Visibility, Pressure, Speed) 
    VALUES ('$date', '$city','$temperature', '$conditions' , '$humidity' , '$visibility' , '$pressure' , '$speed')";
    if (mysqli_query($conn, $sql)) {
        // echo "Weather data inserted successfully";
    } else {
        echo "Error: " .mysqli_error($conn);
    }
    $table = "SELECT * FROM 7days where City='$city'";
    $result = mysqli_query($conn, $table);
    if ($result) {
    } else {
    echo "Error: " . mysqli_error($conn);
    }

    $weather = array();
    while ($row = mysqli_fetch_assoc($result)){
    $weather[] = $row;
}}
}
else{
    $api_key = '5b6efc31d9df5b58c06a64f0cba78094';
    $location = 'BhimDatta';
    $api_url = "https://api.openweathermap.org/data/2.5/weather?q=$location&appid=$api_key"."&units=metric";

    $response = file_get_contents($api_url);
    $data = json_decode($response, true);

    $date=date("Y-m-d");
    $temperature = $data['main']['temp'];
    $humidity = $data['main']['humidity'];
    $pressure = $data['main']['pressure'];
    $speed = $data['wind']['speed'];
    $visibility=$data['visibility'];
    $conditions = $data['weather'][0]['description'];
    $weather_main = $data['weather'][0]['main'];
    $city=$data['name'];

    $sql = "INSERT INTO 7days (Dates, City, Temperature, Conditions, Humidity, Visibility, Pressure, Speed) 
        VALUES ('$date', '$city','$temperature', '$conditions' , '$humidity' , '$visibility' , '$pressure' , '$speed')";
    $conn->query($sql);

    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error inserting data for $date: " . $conn->error . "<br>";
    }

    $table = "SELECT * FROM 7days where City='$city'" ;
    $result = mysqli_query($conn, $table);
    if ($result) {
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    $weather = array();
    while ($row = mysqli_fetch_assoc($result)){
    $weather[] = $row;
    }
}

$delete_query = "DELETE FROM 7days WHERE Dates < DATE_SUB(NOW(), INTERVAL 7 DAY)";
$conn->query($delete_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="php.css">
</head>
<body>
<form method="post" action="">
<div id="row">
      <a id="store"  href="weather.html">Back</a>
      <input type="text" placeholder="Search the location" id="input-box" name="inputBox">
      <button id="touch"><img src="download.png" id="btn"> </button>
    </div>
</form>
<h2>7-Days Forecast</h2>
<div id="container">
    <?php
    $previous_date = null;
    foreach ($weather as $day):
        $current_date = date('l, F j, Y', strtotime($day['Dates']));
        if ($current_date != $previous_date):
            if ($previous_date !== null):
                echo '</div>'; // Close the previous day's data container
            endif;
            ?>
            <div id="details">
                <h3><?php echo $current_date; ?></h3>
                <div id="dets">
                <p><i>City:</i> <?php echo $day['City'];?></p>
                <p><i>Temperature:</i> <?php echo $day['Temperature']; ?>°C</p>
                <p><i>Condition:</i> <?php echo $day['Conditions']; ?></p>
                <p><i>Humidity:</i> <?php echo $day['Humidity']; ?>%</p>
                <p><i>Pressure:</i> <?php echo $day['Pressure']; ?>hPa</p>
                <p><i>Wind Speed:</i> <?php echo $day['Speed']; ?>m/s</p>
                <p><i>Visibility:</i> <?php echo $day['Visibility']; ?></p>
        </div>
            <?php
        endif;
        $previous_date = $current_date;
    endforeach;
    if (!empty($weather)):
        echo '</div>'; // Close the last day's data container
    endif;
    ?>
</div>
</body>
</html>
