<?php

/**
 * Class Dispatcher
 */
class Dispatcher extends DispatcherCore
{
    /**
     * @param null $id_shop
     *
     * @return mixed
     */
    public function getController($id_shop = null)
    {
        $isCLI = ( php_sapi_name() == 'cli' );
        if($isCLI)
            $this->controller = "Crud";

        parent::getController($id_shop = null);
    }
}
