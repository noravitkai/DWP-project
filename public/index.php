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

        $sql = "SELECT Title, Subtitle, ReleaseYear FROM Movie";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='space-y-8'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='p-6 bg-white rounded shadow-lg'>";
                echo "<div class='flex items-baseline space-x-2'>";
                echo "<h3 class='text-xl font-semibold'>" . htmlspecialchars($row["Title"]) . "</h3>";
                echo "<h4 class='text-lg text-gray-500'>" . htmlspecialchars($row["Subtitle"]) . "</h4>";
                echo "</div>";
                echo "<p class='text-gray-600'>Release Year: " . htmlspecialchars($row["ReleaseYear"]) . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p class='text-center text-red-500'>No movies found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>