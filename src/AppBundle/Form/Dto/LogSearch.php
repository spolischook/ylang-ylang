<?php

namespace AppBundle\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LogSearch
{
    /**
     * @var string
     * @Assert\Type(type="string")
     */
    public $search;

    /**
     * @var bool
     * @Assert\Type(type="bool")
     */
    public $isRegExp;

    /**
     * @var array
     * @Assert\Type(type="array")
     */
    public $files;

    /**
     * @var DateTimeInterval[]
     * @Assert\Type(type="array")
     */
    public $timeIntervals;

    /**
     * @var array
     * @Assert\Type(type="array")
     */
    public $users;
}
