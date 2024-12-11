<?php
require_once '../../../config/user_session.php';
require_once '../../../config/functions.php';
require_once __DIR__ . '/../../Controllers/CinemaController.php';

if (!isset($_SESSION['csrf_token'])) {
    regenerateCsrfToken();
}

$formMessage = null;

function isHeaderInjected($str) {
    return preg_match("/[\r\n]/", $str);
}

function sanitizeForHTML($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$cinemaController = new CinemaController();
$cinemas = $cinemaController->index();
$cinema = $cinemas[0] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrf_token)) {
        $formMessage = "Invalid CSRF token. Please try again.";
    }
    elseif (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $formMessage = "All fields are required.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formMessage = "Invalid email address.";
    }
    elseif (isHeaderInjected($email) || isHeaderInjected($subject)) {
        $formMessage = "Invalid input detected.";
    }
    else {
        $to = "contact@noravitkai.com";
        $headers = "From: contact@noravitkai.com\r\nReply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $email_subject = "Contact Form Submission: $subject";
        $email_body = "You have received a new message:\n\nName: $name\nEmail: $email\nMessage:\n$message";
        $email_body = mb_convert_encoding($email_body, 'UTF-8', 'auto');

        if (mail($to, $email_subject, $email_body, $headers)) {
            $formMessage = "Your message has been sent successfully!";
            regenerateCsrfToken();
        } else {
            $formMessage = "Failed to send your message. Please try again later.";
        }
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
$navBase = 'home_page.php#';

if ($currentPage === 'home_page.php') {
    $navLink = '#';
} else {
    $navLink = 'home_page.php#';
}
?>

<footer class="bg-zinc-900">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-2">
      <div class="border-b border-zinc-800 py-8 lg:order-last lg:border-b-0 lg:border-s lg:py-16 lg:ps-16">
        <div class="mt-8 space-y-4 lg:mt-0">

            <div>
                <h2 class="text-xl font-bold text-orange-600 sm:text-3xl">Contact Us</h2>
                <p class="mt-4 max-w-lg text-sm sm:text-base text-zinc-300">
                Have questions or need support? Fill out the form below, and we’ll get back to you as soon as possible.
                </p>
            </div>

            <form method="POST" action="" class="mt-6 w-full">
                <input type="hidden" name="csrf_token" value="<?php echo sanitizeForHTML($_SESSION['csrf_token']); ?>">
                <div class="mb-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div>
                        <label for="name" class="sr-only">Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Your Name"
                            required
                            class="w-full rounded-lg border-zinc-600 px-3 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600"
                        />
                    </div>
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Your Email"
                            required
                            class="w-full rounded-lg border-zinc-600 px-3 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600"
                        />
                    </div>
                </div>
                <div class="mb-4">
                    <label for="subject" class="sr-only">Subject</label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        placeholder="Subject"
                        required
                        class="w-full rounded-lg border-zinc-600 px-3 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600"
                    />
                </div>
                <div class="mb-4">
                    <label for="message" class="sr-only">Message</label>
                    <textarea
                        id="message"
                        name="message"
                        placeholder="Your Message"
                        rows="4"
                        required
                        class="w-full rounded-lg border-zinc-600 px-3 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600"
                    ></textarea>
                </div>
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300"
                    >
                        Send Message
                    </button>
                </div>
            </form>
        </div>
      </div>

      <div class="py-8 lg:py-16 lg:pe-16">
        <div class="hidden text-orange-600 lg:block">
            <a href="home_page.php">
                <h1 class="text-orange-600 font-black sm:text-3xl tracking-wide drop-shadow-md">
                    Fast Lane Cine
                </h1>
            </a>
        </div>

        <div class="sm:mt-8 grid grid-cols-1 gap-8 sm:grid-cols-3">
          <div>
            <p class="max-w-lg text-sm sm:text-base text-orange-600">Sitemap</p>
            <ul class="mt-6 space-y-4 text-sm">
              <li><a href="<?php echo ($currentPage === 'home_page.php') ? '#screenings' : $navLink . 'screenings'; ?>" class="text-zinc-300 transition ease-in-out duration-300 hover:opacity-75">Screenings</a></li>
              <li><a href="<?php echo ($currentPage === 'home_page.php') ? '#news' : $navLink . 'news'; ?>" class="text-zinc-300 transition ease-in-out duration-300 hover:opacity-75">News</a></li>
              <li><a href="<?php echo ($currentPage === 'home_page.php') ? '#movies' : $navLink . 'movies'; ?>" class="text-zinc-300 transition ease-in-out duration-300 hover:opacity-75">Movies</a></li>
              <li><a href="<?php echo ($currentPage === 'home_page.php') ? '#contact' : $navLink . 'contact'; ?>" class="text-zinc-300 transition ease-in-out duration-300 hover:opacity-75">Contact</a></li>
            </ul>
          </div>

          <div>
            <p class="max-w-lg text-sm sm:text-base text-orange-600">Profile</p>
            <ul class="mt-6 space-y-4 text-sm">
              <li><a href="user_login.php" class="text-zinc-300 transition ease-in-out duration-300 hover:opacity-75">Login</a></li>
              <li><a href="user_signup.php" class="text-zinc-300 transition ease-in-out duration-300 hover:opacity-75">Signup</a></li>
            </ul>
          </div>

          <div>
            <p class="max-w-lg text-sm sm:text-base text-orange-600">Contact Info</p>
            <ul class="mt-6 space-y-4 text-sm">
                <li>
                    <a href="tel:<?php echo htmlspecialchars($cinema['PhoneNumber']); ?>" class="text-zinc-300 transition ease-in-out duration-300 hover:opacity-75">
                        <?php echo htmlspecialchars($cinema['PhoneNumber']); ?>
                    </a>
                </li>
                <li>
                    <a href="mailto:<?php echo htmlspecialchars($cinema['Email']); ?>" class="text-zinc-300 transition ease-in-out duration-300 hover:opacity-75">
                        <?php echo htmlspecialchars($cinema['Email']); ?>
                    </a>
                </li>
            </ul>
          </div>
        </div>

        <div class="mt-8 border-t border-zinc-800 pt-8">
            <p class="text-xs text-zinc-600">&copy; <?php echo date('Y'); ?>. Fast Lane Cine. All rights reserved.</p>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php if ($formMessage): ?>
    <script>
        <?php if (strpos($formMessage, 'successfully') !== false): ?>
            alert("<?php echo addslashes($formMessage); ?>");
        <?php endif; ?>
    </script>
<?php endif; ?>

</body>
</html>