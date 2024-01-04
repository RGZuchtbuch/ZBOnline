<?php

namespace App\controller\article;

use App\query;
use App\controller\BaseController;

class Articles extends BaseController
{
	protected function get() {
		$articles = query\Article::getAll();
		return [ 'articles' => $articles ];
	}

	protected function canRead() : bool {
		return true; // anyone can read
	}
}


