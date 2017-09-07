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

        Plutonium_Database_Adapter::getInstance($config);
        Plutonium_Database_Table::getInstance('hosts')->create();
        Plutonium_Database_Table::getInstance('domains')->create();
        Plutonium_Database_Table::getInstance('users')->create();
        Plutonium_Database_Table::getInstance('groups')->create();

        exit;
    }
}
