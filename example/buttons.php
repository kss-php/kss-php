<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <title>Styleguide Example</title>
    <link rel="stylesheet" href="css/layout.css" />
    <link rel="stylesheet" href="css/styleguide.css" />
    <link rel="stylesheet" href="css/buttons.css" />
</head>
<body>
    <header>
        Styleguide Example
    </header>

    <div id="wrapper">
        <nav role="main">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="buttons.php">Styleguide</a></li>
            </ul>
        </nav>

        <?php
            require_once('../vendor/autoload.php');

            $kss = new \Scan\Kss\Parser('css');
            $section = $kss->getSection('1.1');
            $html = '<button class="$modifierClass">Example Button</button>';
            require('block.inc.php');
        ?>

        <p>This block above was created with a simple php block:</p>
        <pre><code>&lt;?php
    require_once('../vendor/autoload.php');

    $kss = new \Scan\Kss\Parser('css');
    $section = $kss->getSection('1.1');
    $html = '&lt;button class="$modifierClass"&gt;Example Button&lt;/button&gt;';
    require('block.inc.php');
?&gt;</code></pre>
        <p>
            Take a look at the source code for more details. The goal is to remove
            the pain from creating a styleguide — document your CSS, have example
            HTML in your templates and automate as much as possible.
        </p>
        <p>
            If your project uses symfony2, take a look at the kssBundle to make
            it even easier to include KSS Styleguides in your views.
        </p>
    </div>

    <script src="js/kss.js"></script>
</body>
</html>
