<?php
$schema['central']['content']['items']['ct_faq'] = array(
    'attrs' => array(
        'class'=>'is-addon'
    ),
    'href' => 'ct_faq.manage',
    'position' => 500,
    'subitems' => array(
        'ct_faq_global_questions' => array(
            'href' => 'ct_faq.global_questions',
            'position' => 200
        ),
    )
);
return $schema;