<?php

class PostsController extends Plutonium_Module_Controller_Abstract {
	public function defaultAction() {
		$model =& $this->getModel();
		$view  =& $this->getView();
		
		$posts =& $model->find(NULL, array(
			'filters' => array(
				':created > @created',
				array(
					'created' => '0000-00-00'
				)
			),
			'order' => ':created DESC',
			'group' => ':id'
		));
		
		$view->setRef('posts', $posts);
		
		foreach ($posts as $post) {
			$post->created = new Plutonium_Utility_Date($post->created);
			$post->updated = new Plutonium_Utility_Date($post->updated);
		}
	}
}
