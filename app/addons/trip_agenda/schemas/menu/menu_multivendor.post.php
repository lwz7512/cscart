<?php
/**
 * Created by PhpStorm.
 * User: liwenzhi
 * Date: 14-6-23
 * Time: 上午9:59
 */

$schema['central']['agenda'] = array(
    'items' => array(
        'manage_agenda' => array(
            'href' => 'trip_agenda.manage',
            'position' => 100,
        ),
        'add_agenda' => array(
            'href' => 'trip_agenda.add',
            'position' => 200,
        ),
    ),
    'position' => 700,
);


return $schema;