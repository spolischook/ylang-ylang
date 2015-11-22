<?php

namespace AppBundle\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class DateTimeInterval
{
    /**
     * @var \DateTime
     * @Assert\DateTime()
     */
    public $from;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     */
    public $to;
}
