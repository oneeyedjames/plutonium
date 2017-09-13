<?php

class StepsController extends Plutonium_Module_Controller {
    public function databaseAction() {
        $data = $this->request->get('data');

        $config = new Plutonium_Object($data);

        Plutonium_Database_Adapter::getInstance($config);

        // System
        Plutonium_Database_Table::getInstance('users')->create();
        Plutonium_Database_Table::getInstance('groups')->create();
        Plutonium_Database_Table::getInstance('hosts')->create();
        Plutonium_Database_Table::getInstance('domains')->create();

        // Application
        Plutonium_Database_Table::getInstance('themes')->create();
        Plutonium_Database_Table::getInstance('modules')->create();
        Plutonium_Database_Table::getInstance('resources')->create();
        Plutonium_Database_Table::getInstance('widgets')->create();

        $model = $this->getModel('hosts');

        $hosts = $model->find(array(
            'slug' => 'main'
        ));

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

        // $module = Plutonium_Module::newInstance($this->_module->application, 'site');
        // $module->install();

        // TODO 'install' core components
        // Module: system
        // Module: site
        // Module: blog (maybe)
        // Theme: charcoal
        // Widget: login
        // Widget: menu (maybe)

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
