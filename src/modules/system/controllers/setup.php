<?php

use Plutonium\AccessObject;
use Plutonium\Application\Theme;
use Plutonium\Application\Module;
use Plutonium\Application\Widget;
use Plutonium\Application\Controller;
use Plutonium\Database\AbstractAdapter;
use Plutonium\Database\Table;

class SetupController extends Controller {
	private static $_core_tables = array(
		'hosts',
		'domains',
		'users',
		'groups',
		'themes',
		'modules',
		'widgets'
	);

	private static $_core_theme_names  = array('charcoal');
	private static $_core_module_names = array('system', 'site');
	private static $_core_widget_names = array('login', 'menu');

	public function databaseAction() {
		$data = $this->request->get('data');

		$config = new AccessObject($data);

		AbstractAdapter::getInstance($config);

		foreach (self::$_core_tables as $table)
			Table::getInstance($table)->create();

		foreach (self::$_core_theme_names as $slug)
			Theme::newInstance($this->module->application, $slug)->install();

		foreach (self::$_core_module_names as $slug)
			Module::newInstance($this->module->application, $slug)->install();

		foreach (self::$_core_widget_names as $slug)
			Widget::newInstance($this->module->application, $slug)->install();

		$model = $this->getModel('hosts');
		$hosts = $model->find(array('slug' => 'main'));
		if (empty($hosts)) {
			$model->save(array(
				'name'         => 'Main Host',
				'slug'         => 'main',
				'descrip'      => 'The main host for this Plutonium instance',
				'default'      => true,
				'meta_keyword' => 'Plutonium',
				'meta_descrip' => 'A Plutonium-Powered website'
			));
		}

		$model = $this->getModel('users');
		$users = $model->find();
		if (empty($users)) {
			$model->save(array(
				'name' => 'Administrator',
				'user' => 'admin',
				'pass' => 'admin'
			));
		}

		$site = Module::newInstance($this->module->application, 'site');

		$model = $site->getModel('pages');
		$pages = $model->find();
		if (empty($pages)) {
			$model->save(array(
				'name' => 'Home',
				'slug' => 'home',
				'body' => '<h1>Welcome!</h1>'
			));
		}

		exit;

		// $file = PU_PATH_BASE . DS . 'config.php';
		// $data = $this->buildConfig($data);

		// file_put_contents($file, $data);
	}

	protected function buildConfig($config) {
		$output = '<?php' . PHP_EOL . PHP_EOL;

		foreach ($config as $key => $value)
			$output .= '$config[\'database\'][\'' . $key . '\'] = \'' . $value . '\';' . PHP_EOL;

		$output .= PHP_EOL;

		return $output;
	}
}
