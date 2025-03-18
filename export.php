<?php
$dotenv = parse_ini_file(__DIR__ . '/.env');

$host = $dotenv['DB_HOST'];
$port = $dotenv['DB_PORT'];
$dbname = $dotenv['DB_NAME'];
$user = $dotenv['DB_USER'];
$password = $dotenv['DB_PASSWORD'];

try {
    $dsn = "pgsql:host=$host;
            port=$port;
            dbname=$dbname;
            sslmode=require";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // Query to join two tables on Left join
    $query = '
        SELECT u.first_name, u.last_name, u.email, w.balance 
        FROM public."User" u
        LEFT JOIN public."Wallet" w ON u.id = w.user_id
    ';
    
    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data) {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        // Save JSON to a file
        $filePath = 'user_data.json';
        file_put_contents($filePath, $jsonData);

        echo "Data successfully saved to $filePath";
    } else {
        echo " No data found.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
