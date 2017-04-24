<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name="description" content="Final Project">
    <meta name="author" content="Jen Smith">
    <title>
        @yield('title', 'Undefined')
    </title>

    <link rel="icon" type="images/png" href="images/favicon.ico">
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet'>
    <link href="/css/trivia.css" type='text/css' rel='stylesheet'>

    @stack('head')

</head>
<body>

    <header>

    </header>

    <div class="container">
        <div class="row">
            <div class="mainpage">
                @yield('content')
            </div>

            <div>
                @yield('quiz')
            </div>
        </div>
    </div>

    <footer class="foot">
        <p>Jen Tiberius Smith &copy; {{ date('Y') }}</p>
    </footer>

    @stack('body')

</body>
</html>