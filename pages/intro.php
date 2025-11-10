<?php
session_start();
include("../includes/database.php");
include("../includes/header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>N E X U S Story</title>
    <link rel="stylesheet" href="../assets/css/paul.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet">

    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Libre Baskerville', serif;
        background: #fdfdfd;
        opacity: 0;
        transition: opacity 1.5s ease-in;
        position: relative;
    }

    body.fade-in {
        opacity: 1;
    }

    .background-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
        opacity: 0.15;
    }

    .story-section {
        max-width: 900px;
        margin: 120px auto 50px;
        padding: 0 20px;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .story-section h2 {
        font-size: 3.2rem;
        letter-spacing: 1px;
        margin-bottom: 20px;
        color: #000;
    }

    .story-section h2 span.cursive {
        font-family: 'Great Vibes', cursive;
    }

    .story-section h2 span.normal {
        font-family: Arial, sans-serif;
        font-weight: normal;
        letter-spacing: 4px;
    }

    .fade-slide-text {
        font-size: 1rem;
        line-height: 1.7;
        color: #333;
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 1.5s ease, transform 1.5s ease;
    }

    .fade-slide-text.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .category-container {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin: 60px auto 80px;
        flex-wrap: wrap;
        max-width: 1200px;
        padding: 0 20px;
        position: relative;
        z-index: 1;
    }

    .category-box {
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        background: #fff;
        width: 280px;
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease;
    }

    .category-box.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .category-box img {
        width: 100%;
        height: 380px;
        object-fit: cover;
    }

    .category-box a {
        display: block;
        padding: 12px;
        font-size: 1rem;
        font-weight: 400;
        text-decoration: none;
        background: #111;
        color: #fff;
        border-top: 1px solid #eee;
        text-align: center;
        letter-spacing: 1px;
    }

    .category-box a:hover {
        background: #444;
    }
    </style>
</head>

<body>
    <!-- Background Video -->
    <video autoplay muted loop playsinline class="background-video">
        <source src="../assets/videos/intro.mov" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Story Section -->
    <div class="story-section">
        <h2>
            <span class="cursive">Our Legacy</span> │
            <span class="normal">N E X U S</span>
        </h2>
        <p class="fade-slide-text">
            Born from the pulse of the city and stitched by Lyz & Lewis with timeless craftsmanship, N E X U S is not
            just a brand — it’s a story of culture, ambition, and authenticity.
            We set out to redefine classics through modern silhouettes, ethical sourcing, and a passion for streetwear
            that speaks across generations.
            From our roots in the heart of urban Manila to closets across the globe, every thread tells a story.
        </p>
    </div>

    <!-- Category Boxes -->
    <div class="category-container">
        <div class="category-box">
            <img src="../assets/images/home/men1a.jpg" alt="Men's Category">
            <a href="category.php?category=men">Shop Men</a>
        </div>
        <div class="category-box">
            <img src="../assets/images/home/women1b.jpg" alt="Women's Category">
            <a href="category.php?category=women">Shop Women</a>
        </div>
        <div class="category-box">
            <img src="../assets/images/home/kids1c.jpg" alt="Kids' Category">
            <a href="category.php?category=kids">Shop Kids</a>
        </div>
    </div>

    <!-- Scripts -->
    <script>
    window.addEventListener("DOMContentLoaded", () => {
        document.body.classList.add("fade-in");

        const storyText = document.querySelector(".fade-slide-text");
        setTimeout(() => {
            storyText.classList.add("visible");
        }, 600);
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.2
    });

    document.querySelectorAll('.category-box').forEach(box => {
        observer.observe(box);
    });
    </script>
</body>

</html>