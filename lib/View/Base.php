<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-combined.min.css" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" >
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery-1.11.1.min.js"></script>
    <script src="/js/jquery-1.11.1.min.js"></script>
</head>
<body>
    <div class='wrapper'>
        <header>
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="head">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="span6">
                                        <h1 class="muted">WebbyLab Test Task</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="navbar">
                                <div class="navbar-inner">
                                    <div class="container">
                                        <ul class="nav">
                                            <li>
                                                <a href="/movies">Список фильмов</a>
                                            </li>
                                            <li>
                                                <a href="/movies/creation">Добавить фильм</a>
                                            </li>
                                            <li>
                                                <a href="/movies/import">Импортировать фильмы</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="mainContainer">
            <div class="messages">
                <?php foreach ($messages['success'] as $message): ?>
                    <div class="alert alert-success" role="alert">
                            <?= $message ?>
                    </div>
                <?php endforeach; ?>

                <?php foreach ($messages['error'] as $message): ?>
                    <div class="alert alert-danger" role="alert">
                            <?= $message ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="content">
                <?= $content(); ?>
            </div>
        </div>
        <footer>
            <div class="footer-bottom">
                <div class="container">
                    <p class="pull-left"> Copyright © Sergey Pastuh 2019. All right reserved. </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>