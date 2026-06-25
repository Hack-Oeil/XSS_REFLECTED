<?php
namespace App\Controller;

use Yoop\AbstractController;
use WebSocket\Client;

class HomeController extends AbstractController
{
    public function index()
    {
        $flag = null;
        if (!empty($_COOKIE['jwt'])) {
            if (trim($_COOKIE['jwt']) === 'eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.oAGlkpMXimwPPVO5q16E3T-Sak68dJenUZA2S3vklYu-hPZkm40IzD1uR5zEM2DDtg9QIlA6ZavOizhWjOh0Db9BIHECoGumjGY_ylhxKFsy7jcf4YFTwlq-6VJWyyPLu-Dm5vORzxDJ-wSLEO42re3Wqjvk_xxjoVBEx5zoFeT1vpUOq5BEhgYTnxiAfqQ7KbINxUy77UjjiidLTqGYWjTGH9Va6AZf6pw6eY5j_HXt3EDz4im5qjBjPzUCb2lKrTzOukqVo40oylmYdUaSc-PoDKebZoMFr_Jd2-Ix31ElwKHYTSie9cOPS8WWd0kkmmCsd5V99znEgkmC1UsRbA') {

                $flag = $this->getFlag('DEFAULT_CTF_FLAG');
                // '<div class="alert alert-success" style="margin: 10px;"><strong>Bien joué !!! <br /><br />Le flag est : </strong>' . SHA1('c05b3dcee8be33daa935cfb51d9ba9be3a4c8ecd') . '</div>';
            }
        }

        // On désactive les CSP pour permettre les injections
        return $this->render('index', ['q' => $_GET['q'] ?? '', 'flag' => $flag], true);
    }

    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['url'])) {
                $url = $_POST['url'];

                if (strpos($url, 'http') !== 0) {
                    $url = 'http://' . $url;
                }
                $this->callBot([
                    "host" => 'http://ho-webserver',
                    "actions" => [
                        [
                            "url" => "http://ho-webserver/cookies_admin_6acd1465bf472"
                        ],
                        ["sleep" => 2000],
                        [
                            "url" => $url
                        ],
                        ["sleep" => 2000]
                    ]
                ]);

                $this->redirectToRoute('/');
                return;
            } else {
                $this->redirectToRoute('/contact');
                return;
            }
        }

        return $this->render('contact');
    }

    public function cookiesAdmin()
    {
        setcookie('jwt', 'eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.oAGlkpMXimwPPVO5q16E3T-Sak68dJenUZA2S3vklYu-hPZkm40IzD1uR5zEM2DDtg9QIlA6ZavOizhWjOh0Db9BIHECoGumjGY_ylhxKFsy7jcf4YFTwlq-6VJWyyPLu-Dm5vORzxDJ-wSLEO42re3Wqjvk_xxjoVBEx5zoFeT1vpUOq5BEhgYTnxiAfqQ7KbINxUy77UjjiidLTqGYWjTGH9Va6AZf6pw6eY5j_HXt3EDz4im5qjBjPzUCb2lKrTzOukqVo40oylmYdUaSc-PoDKebZoMFr_Jd2-Ix31ElwKHYTSie9cOPS8WWd0kkmmCsd5V99znEgkmC1UsRbA', 0, '/');
        echo 'ok';
    }

}
