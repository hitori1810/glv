<?php
    $dictionary['User']['fields']['lock_homepage'] = array (
        'name'      => 'lock_homepage',
        'vname'     => 'LBL_LOCK_HOMEPAGE',
        'type'      => 'enum',
        'options'   => 'lock_homepage_options',
        'massupdate' => false,
    );

    $dictionary['User']['fields']['default_homepage'] = array (
        'name'      => 'default_homepage',
        'vname'     => 'LBL_DEFAULT_HOMEPAGE',
        'type'      => 'enum',
        'options'   => 'default_homepage_options',
        'massupdate' => false,
    );

    $dictionary['User']['fields']['toggle'] = array (
        'name'      => 'toggle',
        'vname'     => 'LBL_TOGGLE',
        'type'      => 'bool',
        'dbType'    => 'tinyint',
        'default'   =>  '0',
        'massupdate' => false,
    ); 

    $dictionary['User']['fields']['only_once'] = array (
        'name'      => 'only_once',
        'vname'     => 'LBL_ONLY_ONCE',
        'type'      => 'bool',
        'dbType'    => 'tinyint',
        'default'   =>  '0',
        'massupdate' => false,
    );


?>