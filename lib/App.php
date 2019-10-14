<?php

use Model\Utils\DatabaseConnection;

final class App
{
	private static $config = [];

	public static function config()
	{
     	return self::$config;
	}

	public static function log($message)
	{
        $datetime = (new DateTime('now'))->format('Y-m-d H:i:s');

		file_put_contents(
            self::config()['log_file'],
            '['.$datetime.'] '.$message.PHP_EOL,
            FILE_APPEND
        );
	}

	function __construct($config)
	{
		self::$config = $config;
	}

    private function startRouter()
    {
        $router = new Router();

        $router->defineRoute('get', 'movies', 'Controller\Movies\Index');
        $router->defineRoute('get', 'movies/%id', 'Controller\Movies\Show');
        $router->defineRoute('delete', 'movies/%id', 'Controller\Movies\Delete');

        $router->defineRoute('get', 'movies/creation', 'Controller\Movies\Creation\Show');
        $router->defineRoute('post', 'movies', 'Controller\Movies\Create');

        $router->defineRoute('get', 'movies/import', 'Controller\Movies\Import\Show');
        $router->defineRoute('post', 'movies/import', 'Controller\Movies\Import\Create');

        $router->defineRoute('get', 'not_found', 'Controller\NotFound\Show');

        $router->defineRedirect('get', '', 'movies');

        $router->start();
    }

	public function start()
	{
        session_start();
		DatabaseConnection::testConnection();
        $this->startRouter();
	}
}