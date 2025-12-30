<?php
// home.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/favicon.png">

    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f7f9fc; 
            color: #333; 
            margin: 0; 
            padding: 0; 
        }
        header { 
            background: #4a90e2; 
            color: white; 
            padding: 20px; 
            text-align: center; 
        }
        header img {
            height: 50px;
            vertical-align: middle;
            margin-right: 10px;
        }
        main { 
            max-width: 800px; 
            margin: 40px auto; 
            padding: 30px 40px; 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
        }
        a { 
            color: #4a90e2; 
            text-decoration: none; 
        }
        a:hover { 
            text-decoration: underline; 
        }
        code { 
            background: #eee; 
            padding: 2px 6px; 
            border-radius: 4px; 
            font-family: monospace; 
        }
        h2 { 
            color: #333; 
            border-bottom: 2px solid #eee; 
            padding-bottom: 5px; 
        }
        ul { 
            padding-left: 20px; 
        }
        footer { 
            text-align: center; 
            margin-top: 40px; 
            font-size: 0.9rem; 
            color: #777; 
        }
        footer a { color: #4a90e2; }
        .logo-title {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-title">
            <img src="/assets/logo.png" alt="Pulse Logo">
            <h1><?= $title ?></h1>
        </div>
    </header>

    <main>
        <p>Welcome to <strong>Pulse</strong> — your lightweight OpenSwoole microframework for PHP 8.4+.</p>

        <h2>Getting Started</h2>
        <ul>
            <li>Check the <a href="https://github.com/fariv/pulse" target="_blank">GitHub repository</a> for documentation and examples.</li>
            <li>Run your server: <code>php bin/server.php</code></li>
            <li>Access in browser: <a href="http://localhost:9501">http://localhost:9501</a></li>
        </ul>

        <h2>Features</h2>
        <ul>
            <li>PSR-7 compatible HTTP requests & responses</li>
            <li>Simple routing system</li>
            <li>Middleware support</li>
            <li>View rendering with <code>view()</code> helper</li>
            <li>OpenSwoole powered server</li>
        </ul>

        <h2>Next Steps</h2>
        <p>Create your own controllers, and views inside <code>app/Controllers</code>, and <code>app/Views</code>. You can add your own ORM or database abstraction layer and Model in <code>app/</code> directory if wanted</p>

        <footer>
            <p>Made with ❤️ by <a href="https://github.com/fariv" target="_blank">Ashraful Ferdous</a></p>
        </footer>
    </main>
</body>
</html>
