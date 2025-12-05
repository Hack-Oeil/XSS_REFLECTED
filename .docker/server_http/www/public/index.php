<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use WebSocket\Client;

function render($name, $params = []){
    ob_start();
    extract($params);
    include(('templates/'.$name.'.phtml'));
    $out1=ob_get_contents();ob_end_clean();
    
    $out1=str_replace('{{FLAG}}', $params['flag']??"", $out1);
    return $out1;
}



function callBot($url) {
    $client = new \WebSocket\Client("ws://botserver:8282");
    try {
        $client->text(json_encode([
            "host" => 'http://web_apache',
            "actions" => [
                 [
                    "url" => "http://web_apache/cookies_admin_6acd1465bf472"
                ],
                [ "sleep" => 2000 ],
                [
                    "url" => $url
                ],
                [ "sleep" => 2000 ]
            ],
        ]));
    } 
    catch(Exception $e){}
    finally{$client->close();}
}


if(!defined('HO_LICENSE')) {
    include((__DIR__ . '/../vendor/autoload.php'));
}


$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $cookies = $request->getCookieParams();
    if (!empty($cookies['jwt'])) {
        if(trim($cookies['jwt']) === 'eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.oAGlkpMXimwPPVO5q16E3T-Sak68dJenUZA2S3vklYu-hPZkm40IzD1uR5zEM2DDtg9QIlA6ZavOizhWjOh0Db9BIHECoGumjGY_ylhxKFsy7jcf4YFTwlq-6VJWyyPLu-Dm5vORzxDJ-wSLEO42re3Wqjvk_xxjoVBEx5zoFeT1vpUOq5BEhgYTnxiAfqQ7KbINxUy77UjjiidLTqGYWjTGH9Va6AZf6pw6eY5j_HXt3EDz4im5qjBjPzUCb2lKrTzOukqVo40oylmYdUaSc-PoDKebZoMFr_Jd2-Ix31ElwKHYTSie9cOPS8WWd0kkmmCsd5V99znEgkmC1UsRbA') {
            $data['flag'] = '<div class="alert alert-success"><strong>Bien joué !!! <br /><br />Le flag est : </strong>'.SHA1('c05b3dcee8be33daa935cfb51d9ba9be3a4c8ecd').'</div>'; 
        }
    }
    $response->getBody()->write(render('index', $data??[]));
    return $response;
});

$app->get('/contact', function (Request $request, Response $response, $args) {
    $response->getBody()->write(render('contact'));
    return $response;
});


$app->post('/contact', function (Request $request, Response $response, $args) {
    $postData = $request->getParsedBody();    
    if (!empty($postData['url'])) {
       callBot($postData['url']);
       return $response->withHeader('Location', '/')->withStatus(302);
    } else {
        $response->withHeader('Location', '/contact')->withStatus(302);
    }
    return $response;
});


// Pour le bot
$app->get('/cookies_admin_6acd1465bf472', function (Request $request, Response $response, $args) {
    $response = $response->withAddedHeader('Set-Cookie', 'jwt=eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.oAGlkpMXimwPPVO5q16E3T-Sak68dJenUZA2S3vklYu-hPZkm40IzD1uR5zEM2DDtg9QIlA6ZavOizhWjOh0Db9BIHECoGumjGY_ylhxKFsy7jcf4YFTwlq-6VJWyyPLu-Dm5vORzxDJ-wSLEO42re3Wqjvk_xxjoVBEx5zoFeT1vpUOq5BEhgYTnxiAfqQ7KbINxUy77UjjiidLTqGYWjTGH9Va6AZf6pw6eY5j_HXt3EDz4im5qjBjPzUCb2lKrTzOukqVo40oylmYdUaSc-PoDKebZoMFr_Jd2-Ix31ElwKHYTSie9cOPS8WWd0kkmmCsd5V99znEgkmC1UsRbA; path=/');
    $response->getBody()->write('ok');
    return $response;
});

$app->run();