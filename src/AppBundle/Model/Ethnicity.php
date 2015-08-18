<?php

namespace AppBundle\Model;

final class Ethnicity
{
    /**
     * @return array
     */
    public static function all()
    {
        return [
            'Caucasian (white)',
            'African American (black)',
            'Asian',
            'Hispanic',
            'First Nations',
            'East Indian',
            'Middle Eastern',
            'Other',
            'Rather Not Say',
        ];
    }
}
