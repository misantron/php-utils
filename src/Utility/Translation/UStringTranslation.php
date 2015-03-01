<?php

namespace Utility\Translation;

class UStringTranslation extends UAbstractTranslation
{
    protected static $dictionary = array(
        'fileSize' => array(
            'b' => 'б',
            'kb' => 'Кб',
            'mb' => 'Мб',
            'gb' => 'Гб',
            'tb' => 'Тб',
            'pb' => 'Пб',
            'eb' => 'Eб',
        ),
    );
}