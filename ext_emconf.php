<?php

########################################################################
# Extension Manager/Repository config file for ext "in2glossar".
########################################################################

$EM_CONF[$_EXTKEY] = array(
    'title' => 'In2glossar',
    'description' => 'Provides a glossar list for TYPO3',
    'category' => 'misc',
    'author' => 'in2code GmbH',
    'author_email' => 'service@in2code.de',
    'dependencies' => 'extbase, fluid',
    'state' => 'stable',
    'author_company' => 'in2code GmbH',
    'version' => '0.1.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-8.5.99',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
);
