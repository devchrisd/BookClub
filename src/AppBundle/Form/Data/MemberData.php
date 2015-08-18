<?php

namespace AppBundle\Form\Data;

use AppBundle\Model\BodyType;
use AppBundle\Model\Ethnicity;
use AppBundle\Model\Category;
use AppBundle\Model\Member;

final class MemberData
{
    const CLASS_NAME = __CLASS__;

    public $username;
    public $password;
    public $city;
    public $postalCode;
    public $dateOfBirth;
    public $category;
    public $height;
    public $weight;
    public $bodyType;
    public $ethnicity;
    public $email;

    /**
     * @param Member $member
     */
    public function __construct(Member $member = null)
    {
        if (!$member) {
            return;
        }

        $this->username = $member->getUsername();
        $this->password = $member->getPassword();
        $this->city = $member->getAddress()->getCity();
        $this->postalCode = $member->getAddress()->getPostalCode();
        $this->dateOfBirth = $member->getDateOfBirth();
        $this->category = (int)array_search($member->getCategory(), Category::all());
        $this->height = (string)$member->getHeight();
        $this->weight = (string)$member->getWeight();
        $this->bodyType = (int)array_search($member->getBodyType(), BodyType::all());
        $this->ethnicity = (int)array_search($member->getEthnicity(), Ethnicity::all());
        $this->email = (string)$member->getEmail();
    }
}
