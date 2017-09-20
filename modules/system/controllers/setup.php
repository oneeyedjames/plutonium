<?php

use Plutonium\Application\Controller;

class SetupController extends Controller {
    private static $_core_tables = array(
        'hosts',
        'domains',
        'users',
        'groups',
        'themes',
        'modules',
        'resources',
        'widgets'
    );

    private static $_core_theme_names  = array('charcoal');
    private static $_core_module_names = array('system', 'site');
    private static $_core_widget_names = array('login', 'menu');

    public function databaseAction() {
        $data = $this->request->get('data');

        $config = new Plutonium_Object($data);

        Plutonium_Database_Adapter::getInstance($config);

        foreach (self::$_core_tables as $table)
            Plutonium_Database_Table::getInstance($table)->create();

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
            // TODO create default admin user
        }

        $model = $this->getModel('themes');
        foreach (self::$_core_theme_names as $slug) {
            $themes = $model->find(compact('slug'));
            if (empty($themes))
                $model->save(Plutonium_Theme::getMetadata($slug));
        }

        $model = $this->getModel('modules');
        foreach (self::$_core_module_names as $slug) {
            $modules = $model->find(compact('slug'));
            if (empty($modules))
                $model->save(Plutonium_Module::getMetadata($slug));
        }

        $model = $this->getModel('widgets');
        foreach (self::$_core_widget_names as $slug) {
            $widgets = $model->find(compact('slug'));
            if (empty($widgets))
                $model->save(Plutonium_Widget::getMetadata($slug));
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
