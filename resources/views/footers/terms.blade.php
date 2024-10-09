<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Russell Osias Social Media</title>
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
        <h1>Terms of Service</h1>
        
        <div class="section">
            <h2>Acceptance of Terms</h2>
            <p>By using Russell Osias Social Media, you agree to these terms. Please read them carefully.</p>
        </div>

        <div class="section">
            <h2>User Responsibilities</h2>
            <ul>
                <li>Provide accurate information on your profile.</li>
                <li>Respect other users and their content.</li>
                <li>Abide by community guidelines to maintain a positive environment.</li>
                <li>Report any content that violates our policies.</li>
                <li>Use the platform for lawful purposes only.</li>
            </ul>
        </div>

        <div class="section">
            <h2>Account Termination</h2>
            <p>We reserve the right to terminate accounts that violate our terms. Serious offenses may result in immediate suspension.</p>
        </div>

        <div class="section">
            <h2>Changes to Terms</h2>
            <p>We may update these terms periodically. Continued use of our platform signifies your acceptance of any changes.</p>
        </div>

        <a href="{{ route('home') }}" class="btn-home">Back to Home</a>
    </div>
</body>
</html>
