<?php

namespace AppBundle\Model;

final class BodyType
{
    /**
     * @return array
     */
    public static function all()
    {
        return [
            'Slim',
            'Fit',
            'Muscular',
            'Average/medium',
            'Shapely toned',
            'A few extra pounds',
            'Full sized',
            'Zaftig (Voluptuous/Curvy)'
        ];
    }
}
