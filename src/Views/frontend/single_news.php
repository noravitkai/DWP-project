<?php
include '../../../config/user_session.php';
require_once '../../Controllers/NewsController.php';

$newsController = new NewsController();
$newsId = $_GET['id'] ?? null;
$news = $newsController->getNewsById($newsId);

if (!$news) {
    header("Location: home_page.php");
    exit;
}

function getCategoryClass($category) {
    switch ($category) {
        case 'Announcement':
            return 'bg-yellow-600';
        case 'Event':
            return 'bg-orange-600';
        case 'Update':
            return 'bg-red-600';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $news['Title']; ?> - Fast Lane Cine News</title>
        <link href="../../../public/css/tailwind.css" rel="stylesheet">
    </head>
    <?php include '../frontend/frontend_navigation.php'; ?>
    <body class="bg-zinc-800 text-zinc-100">
        <main class="mx-auto max-w-screen-lg px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
            <?php if ($news['ImageURL']): ?>
                <div class="aspect-w-16 aspect-h-11 mb-6">
                    <img src="<?php echo $news['ImageURL']; ?>" alt="<?php echo $news['Title']; ?>" class="w-full h-full object-cover shadow-lg" loading="lazy">
                </div>
            <?php endif; ?>
            <div class="flex items-center space-x-4 mb-4">
                <span class="inline-block <?php echo getCategoryClass($news['Category']); ?> px-2 py-1 text-xs text-zinc-100">
                    <?php echo $news['Category']; ?>
                </span>
                <date class="text-sm text-zinc-300">
                    <?php echo date('F j, Y', strtotime($news['DatePublished'])); ?>
                </date>
            </div>
            <h1 class="text-3xl font-bold text-orange-600 mb-4"><?php echo $news['Title']; ?></h1>
            <article class="text-lg text-zinc-300 mb-8">
                <p><?php echo nl2br($news['Content']); ?></p>
            </article>
            <a href="home_page.php" class="inline-flex items-center gap-1 text-sm font-medium text-orange-600 hover:text-orange-500 transition ease-in-out duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to Home
            </a>
        </main>
        <?php include '../frontend/footer.php'; ?>
    </body>
</html>
