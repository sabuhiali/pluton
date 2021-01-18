<?php

namespace App\Controllers;

use \Pluton\Http\Controller;

class Example extends Controller {

    public function main() {

        $this->view('welcome');

    }
}