<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fast & Furious Cinema</title>
    <link href="./css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold underline mb-8 text-center">
            Welcome to the Fast & Furious Cinema!
        </h1>

        <h2 class="text-2xl font-bold mb-8 text-center">
            Now Showing
        </h2>

        <?php
        include '../config/dbcon.php';

        $conn = dbCon();

        $sql = "SELECT Title, Subtitle, ReleaseYear FROM Movie";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($movies) > 0) {
            echo "<div class='space-y-8'>";
            foreach ($movies as $row) {
                echo "<div class='p-6 bg-white rounded shadow-lg'>";
                echo "<div class='flex flex-col'>";
                echo "<h3 class='text-xl font-semibold text-gray-900'>" . htmlspecialchars($row["Title"]) . "</h3>";
                echo "<h4 class='text-lg text-gray-900'>" . htmlspecialchars($row["Subtitle"]) . "</h4>";
                echo "</div>";
                echo "<p class='text-gray-600'>Release Year: " . htmlspecialchars($row["ReleaseYear"]) . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p class='text-center text-red-500'>No movies found.</p>";
        }

        $conn = null;
        ?>
    </div>
</body>
</html>