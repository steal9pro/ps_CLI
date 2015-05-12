<?php

/**
 * Class Dispatcher
 */
class Dispatcher extends DispatcherCore
{
    /**
     * Dispatch
     *
     * @throws PrestaShopException
     */
    public function __construct()
    {
        $isCLI = (php_sapi_name() == 'cli');
        if ($isCLI) {
            $this->controller = "Crud";
        }

        parent::__construct();
    }
}
