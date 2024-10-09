<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Russell Osias Social Media</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Figtree', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            line-height: 1.8;
        }
        h1 {
            color: #ff0000;
            text-align: center;
            margin-bottom: 2rem;
        }
        h2 {
            color: #ff6666;
            margin-top: 2rem;
        }
        p, ul {
            margin: 1rem 0;
        }
        ul {
            padding-left: 1.5rem;
        }
        ul li {
            list-style-type: disc;
            margin: 0.5rem 0;
        }
        .btn-home {
            display: block;
            width: fit-content;
            margin: 2rem auto;
            padding: 0.5rem 1rem;
            background-color: #ff0000;
            color: #fff;
            border-radius: 0.5rem;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .btn-home:hover {
            background-color: #cc0000;
        }
        .section {
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: #111;
        }
        .section:hover {
            background-color: #222;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>About Us</h1>
        
        <div class="section">
            <h2>Our Mission</h2>
            <p>Russell Osias Social Media is designed to foster connections, providing a safe and engaging platform for users to communicate and share ideas.</p>
        </div>

        <div class="section">
            <h2>Who We Are</h2>
            <ul>
                <li>Created by Russell Osias, a student at Ramon Magsaysay Memorial Colleges.</li>
                <li>Focused on creating a positive user experience.</li>
                <li>Inspired by the community of General Santos City.</li>
            </ul>
        </div>

        <div class="section">
            <h2>Our Values</h2>
            <p>We believe in inclusivity, respect, and transparency. Our platform is built with these values at its core.</p>
        </div>

        <div class="section">
            <h2>Contact Us</h2>
            <p>If you have questions, reach out to us through our platform or at our main office in General Santos City.</p>
        </div>

        <a href="{{ route('home') }}" class="btn-home">Back to Home</a>
    </div>
</body>
</html>
