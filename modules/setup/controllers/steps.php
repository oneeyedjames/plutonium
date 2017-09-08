<?php

class StepsController extends Plutonium_Module_Controller {
    public function databaseAction() {
        $data = $this->request->get('data');

        $config = new Plutonium_Object(array(
            'hostname' => $data['hostname'],
            'username' => $data['username'],
            'password' => $data['password'],
            'dbname'   => $data['dbname'],
            'driver'   => $data['driver']
        ));

        //header('Content-type: text/plain');

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

        die('It\'s Over');
    }
}
