<?php
require_once APPPATH.'controllers/mallerp'.EXT;

class Edu extends Mallerp
{
    const NAME = 'edu';
    
    public function __construct() {
        parent::__construct();
    }

    protected function _get_system()
    {
        return self::NAME;
    }
}

?>
