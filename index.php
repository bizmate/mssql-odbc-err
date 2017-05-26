<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->error(function (\Exception $e, Request $request, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'ERROR : ' . get_class($e) . ' code: ' . $e->getCode() . ' msg: ' . $e->getMessage();
    }

    return new Response($message);
});

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/info', function () use ($app) {
    return phpinfo();
    //return 'Hello '. $app->escape( json_encode(ini_get_all()) );
});

$app->get('/dbparam', function () use ($app) {
    return '<br>DB_DATABASE: ' . getenv('DB_DATABASE') .
    '<br>DB_USERNAME: ' . getenv('DB_USERNAME') .
    '<br>DB_PASSWORD: ' . getenv('DB_PASSWORD') .
    '<br>DB_HOSTNAME: ' . getenv('DB_HOSTNAME');
});

$app->get('/mssql', function () use ($app) {

    $serverName = getenv('DB_HOSTNAME');
    $connectionOptions = array(
        "Database" => getenv('DB_DATABASE'),
        "Uid" => getenv('DB_USERNAME'),
        "PWD" => getenv('DB_PASSWORD')
    );
    //Establishes the connection
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if($conn) {
        echo "Connection established.\n";
    } else {
        echo "Connection could not be established.\n";
        die( print_r( sqlsrv_errors(), true));
    }

    //Select Query
    $tsql= "SELECT @@Version as SQL_VERSION";
    //Executes the query
    $getResults= sqlsrv_query($conn, $tsql);
    //Error handling

    $output = "<h1> Results : </h1>";

    if ($getResults == FALSE){
        $errors = sqlsrv_errors();
        /* Display errors. */
        $output .= "<br/><br/>Error information: " ;

        foreach ( $errors as $error )
        {
            $output .= "<br/> SQLSTATE: ".$error['SQLSTATE'];
            $output .= "<br/>Code: ".$error['code'];
            $output .= "<br/>Message: ".$error['message'];
        }
        throw new Exception($output);
    }

    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $output .= "<br/> Sql version : " . $row['SQL_VERSION'];
    }
    sqlsrv_free_stmt($getResults);

    return $output;
});

$app->run();
