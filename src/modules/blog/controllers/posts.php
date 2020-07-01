<?php

use Plutonium\Application\Controller;

class PostsController extends Controller {
	public function defaultAction() {
		$model =& $this->getModel();
		$view  =& $this->getView();

		$posts =& $model->find(null, [
			'filters' => [
				':created > @created',
				[
					'created' => '0000-00-00'
				]
			],
			'order' => ':created DESC',
			'group' => ':id'
		]);

		$view->setRef('posts', $posts);
	}
}
