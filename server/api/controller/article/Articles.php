<?php

namespace App\controller\article;

use App\model;
use App\controller\BaseController;

class Articles extends BaseController
{
	protected function get() {
		$articles = model\Article::getAll();
		return [ 'articles' => $articles ];
	}

	protected function canRead() : bool {
		return true; // anyone can read
	}
}


