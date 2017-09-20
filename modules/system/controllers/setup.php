<?php

use Plutonium\Object;
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
        'resources',
        'widgets'
    );

    private static $_core_theme_names  = array('charcoal');
    private static $_core_module_names = array('system', 'site');
    private static $_core_widget_names = array('login', 'menu');

    public function databaseAction() {
        $data = $this->request->get('data');

        $config = new Object($data);

        AbstractAdapter::getInstance($config);

        foreach (self::$_core_tables as $table)
            Table::getInstance($table)->create();

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

        $model = $this->getModel('themes');
        foreach (self::$_core_theme_names as $slug) {
            $themes = $model->find(compact('slug'));
            if (empty($themes)) {
                $meta = new Object(Theme::getMetadata($slug));
                $meta->def('package', ucfirst($slug) . ' Theme');

                $model->save(array(
                    'name'    => $meta['package'],
                    'slug'    => $slug,
                    'descrip' => $meta['description']
                ));
            }
        }

        $model = $this->getModel('modules');
        foreach (self::$_core_module_names as $slug) {
            $modules = $model->find(compact('slug'));
            if (empty($modules)) {
                $meta = new Object(Module::getMetadata($slug));
                $meta->def('package', ucfirst($slug) . ' Module');

                $model->save(array(
                    'name'    => $meta['package'],
                    'slug'    => $slug,
                    'descrip' => $meta['description']
                ));
            }
        }

        $model = $this->getModel('widgets');
        foreach (self::$_core_widget_names as $slug) {
            $widgets = $model->find(compact('slug'));
            if (empty($widgets)) {
                $meta = new Object(Widget::getMetadata($slug));
                $meta->def('package', ucfirst($slug) . ' Widget');

                $model->save(array(
                    'name'    => $meta['package'],
                    'slug'    => $slug,
                    'descrip' => $meta['description']
                ));
            }
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
