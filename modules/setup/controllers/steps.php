<?php

class StepsController extends Plutonium_Module_Controller {
    public function databaseAction() {
        $data = $this->request->get('data');

        $config = new Plutonium_Object(array(
            'hostname' => 'localhost',
            'username' => $data['username'],
            'password' => $data['password'],
            'dbname'   => $data['database'],
            'driver'   => 'MySQL'
        ));

        $db = Plutonium_Database_Adapter::getInstance($config);

        var_dump($db);exit;
    }
}
