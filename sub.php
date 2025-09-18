<?php
session_start();
include "latestevent.php";

$eventCon = new mysqli("localhost", "root", "", "tugro");

$eventFull = false;

if ($latestEvent) {
    $event_id = (int)$latestEvent['id'];
    $max = (int)$latestEvent['max_volunteers'];

    $countQuery = $eventCon->query("SELECT COUNT(*) AS total FROM event_volunteers WHERE event_id = $event_id");
    $count = $countQuery->fetch_assoc()['total'] ?? 0;

    if ($count >= $max) {
        $eventFull = true;
    }
}

$loggedIn = isset($_SESSION['user_id']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rotary Club in San Jose</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Oswald&family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="map.css?v=1">

  <style>
    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 20px;
      flex-wrap: wrap;
      background: #fff;
    }

    .nav-links {
      display: flex;
      gap: 20px;
    }

    .menu-toggle {
      display: none;
      font-size: 28px;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .nav-links {
        display: none;
        flex-direction: column;
        gap: 10px;
        width: 100%;
        margin-top: 10px;
      }
      .nav-links.active {
        display: flex;
      }
      .menu-toggle {
        display: block;
      }
    }

    /* Cards Grid */
    .cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      padding: 20px;
    }

    /* Event Section */
    .event-container {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      flex-wrap: wrap;
      padding: 20px;
    }

    .event-content,
    .event-image {
      flex: 1 1 300px;
    }

    .event-image img {
      max-width: 100%;
      border-radius: 10px;
    }

    /* Map responsive */
    .map-container iframe {
      width: 100% !important;
      height: 300px;
      border-radius: 10px;
    }

    /* Video text center fix */
    .centered-content {
      padding: 0 15px;
      text-align: center;
    }

    .centered-content h2 {
      font-size: clamp(20px, 5vw, 36px);
    }

    .centered-content p {
      font-size: clamp(14px, 3vw, 18px);
    }
  </style>
</head>
<body>
  <section class="video-section">
    <video autoplay muted loop playsinline class="background-video">
      <source src="edmon.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>

    <header class="navbar">
      <div class="logo">
        <img src="img/logo.png" alt="Rotary Logo" />
        <span class="logo-text">Rotary Club in San Jose</span>
      </div>

      <!-- Hamburger -->
      <span class="menu-toggle material-icons" onclick="toggleMenu()">menu</span>

      <nav class="nav-links" id="navMenu">
        <a href="#" data-i18n="model">MODEL</a>
        <a href="#" data-i18n="research">RESEARCH</a>
        <a href="#" data-i18n="impact">GLOBAL IMPACT</a>
        <a href="#" data-i18n="news">NEWS & REPORTS</a>
        <a href="#" data-i18n="involved">GET INVOLVED</a>
      </nav>

      <div class="navbar-right">
        <div class="language-selector">
          <label for="language">🌐</label>
          <select id="language" onchange="changeLanguage()">
            <option value="en">English</option>
            <option value="tl">Tagalog</option>
          </select>
        </div>

        <a href="login.php" class="login-link">Login</a>

        <?php if ($loggedIn): ?>
          <a href="profile.php" class="profile-link">
            <span class="material-icons">person</span>
          </a>
        <?php endif; ?>
      </div>
    </header>

    <div class="centered-content">
      <h2 class="big"><b>Welcome to the Rotary Club</b></h2>
      <p>
        The Rotary and Rotaract Clubs, under District 3810, are dedicated groups of volunteers committed to serving their communities and making a global impact. Guided by the values of integrity, diversity, friendship, and leadership, they work together to create positive and lasting change.
      </p>
      <div class="button-center">
        <a href="donationform.php" class="donate-btn">Donate to our Community</a>
      </div>
    </div>
  </section>

  <!-- ✅ Section 2: News Cards -->
  <section class="cards-grid">
    <div class="card">News 1</div>
    <div class="card">News 2</div>
    <div class="card">News 3</div>
  </section>

  <!-- ✅ Section 3: Latest Event -->
  <section class="event-container">
    <div class="event-content">
      <?php if ($latestEvent): ?>
        <h3><?= htmlspecialchars($latestEvent['title']) ?></h3>
        <p><?= htmlspecialchars($latestEvent['description']) ?></p>
        <p><b>Date:</b> <?= htmlspecialchars($latestEvent['event_date']) ?></p>
        <?php if ($eventFull): ?>
          <button class="btn btn-danger" disabled>Event Full</button>
        <?php else: ?>
          <a href="volunteer.php?event_id=<?= $latestEvent['id'] ?>" class="btn btn-primary">Join as Volunteer</a>
        <?php endif; ?>
      <?php else: ?>
        <p>No upcoming events.</p>
      <?php endif; ?>
    </div>
    <div class="event-image">
      <img src="img/event.jpg" alt="Event">
    </div>
  </section>

  <!-- ✅ Section 4: Map -->
  <section class="map-container">
    <iframe src="https://www.google.com/maps/embed?pb=..." allowfullscreen="" loading="lazy"></iframe>
  </section>

  <!-- 🔥 JS for Hamburger -->
  <script>
    function toggleMenu() {
      document.getElementById("navMenu").classList.toggle("active");
    }

    // ✅ Auto-close menu kapag nag click ng link
    document.querySelectorAll("#navMenu a").forEach(link => {
      link.addEventListener("click", () => {
        document.getElementById("navMenu").classList.remove("active");
      });
    });
  </script>
</body>
</html>
