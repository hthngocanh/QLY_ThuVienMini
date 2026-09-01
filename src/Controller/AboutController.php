<?php
// src/Controller/AboutController.php

require_once __DIR__ . '/BaseController.php';

class AboutController extends BaseController
{
    public function index()
    {
        $this->renderView('about/index.php');
    }
}

