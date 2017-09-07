<?php

class StepsController extends Plutonium_Module_Controller {
    public function defaultAction() {
        $request = $this->module->request;
        var_dump($request);exit;
    }
}
