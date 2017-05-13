<!DOCTYPE html>
<html>
<head>
    <title>
        @yield('title', 'DayStocker')
    </title>

    <meta charset='utf-8'>
    <link rel="icon" type="images/png" href="images/favicon.ico">
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="/js/stocker.js"></script>


    <link href="/css/stocker.css" type='text/css' rel='stylesheet'>
    <link href="/css/stocks.css" type='text/css' rel='stylesheet'>

    @stack('head')

</head>
<body>

    <div id='content'>
        @if(Session::get('message') != null)
            <div class='message'>{{ Session::get('message') }}</div>
        @endif

        <header>
            <a href='/'>
                <img id='logo' src='/images/dayTrader_world.png' alt='DayStocker Logo'>
            </a>
            <span class="dayText">DAYSTOCKER</span>
            <nav>
                <ul>
                    @if(Auth::check())
                        <li><a href='/'>Home</a></li>
                        <li><a href='/stocks/search'>Search</a></li>
                        <li>
                            <form method='POST' id='logout' action='/logout'>
                                {{csrf_field()}}
                                <a href='#' onClick='document.getElementById("logout").submit();'>Logout</a>
                            </form>
                        </li>
                        <li><a href='/about'>About</a></li>
                    @else
                        <li><a href='/'>Home</a></li>
                        <li><a href='/login'>Login</a></li>
                        <li><a href='/register'>Register</a></li>
                        <li><a href='/about'>About</a></li>
                    @endif
                </ul>
            </nav>

        </header>

        <section>
            
            @yield('content')
        </section>

        <footer>
            &copy; {{ date('Y') }} &nbsp;&nbsp;
            <a href='https://github.com/qedpro10/a4' target='_blank'><i class='fa fa-github'></i> View on Github</a> &nbsp;&nbsp;
            <a href='http://a4.rogue42.me' target='_blank'><i class='fa fa-link'></i> View on Production</a>
        </footer>

    </div>



    @stack('body')

</body>
</html>
