<?php

namespace AppBundle\Model;

final class Category
{
    /**
     * @return array
     */
    public static function all()
    {
        return [
            'Arts',
            'Biography',
            'Comic',
            'Fiction',
            'History',
            'Poetry',
        ];
    }
}
