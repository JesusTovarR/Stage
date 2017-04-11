<?php

namespace View;

class AbstractView extends \Controller\AbstractController {

    public function url_decoder($array) {
        array_walk_recursive($array, function(&$item, $key) {

            $item = urldecode($item);
        });

        return $array;
    }

    public function setHeaders() {
        static::$app = \Slim\Slim::getInstance();
        static::$app->response->headers->set('Content-Type', 'application/json');
    }

    private function token() {
        $a = hash("sha256", uniqid());
        return $a;
    }

    public function setToken() {
        $token = $this->token();
        $_SESSION['token'] = $token;
        $_SESSION['token_time'] = time();
        return $token;
    }

    public function destroyToken() {
        unset($_SESSION["token"]);
    }

    protected function verifier_token($token) {
        if (isset($_SESSION['token']) && isset($_SESSION['token_time'])) {
            if ($_SESSION['token'] == $token) {
                //expire au bout de 30min
                if ($_SESSION['token_time'] >= (time() - 60 * 60)) {
                    return true;
                } else {
                    unset($_SESSION['token']);
                    unset($_SESSION['token_time']);
                    throw new \Exception("Le token n'est pas valide.");
                }
            } else {
                throw new \Exception("Le token n'est pas valide.");
            }
        } else {
            throw new \Exception("Le token n'est pas valide.");
        }
    }

    public function cleanArray($array) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->cleanArray($value);
            } else {
                $value = trim($value);
                $value = stripslashes($value);
                $value = htmlspecialchars($value);
            }
        }
        return $array;
    }
    public function cleanText($array) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->cleanText($value);
            } else {
                $value= utf8_encode($value);
            }
        }
        return $array;
    }

}
