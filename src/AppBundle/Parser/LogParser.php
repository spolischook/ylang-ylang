<?php

namespace AppBundle\Parser;

use Kassner\LogParser\LogParser as BaseLogParser;
use Kassner\LogParser\FormatException;
use AppBundle\Entity\Log;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class LogParser extends BaseLogParser
{
    /**
     * Parses one single log line
     *
     * @param string $line
     * @return Log
     * @throws FormatException
     */
    public function parseLog($line)
    {
        if (!preg_match($this->pcreFormat, $line, $matches)) {
            throw new FormatException($line);
        }

        $entry = new Log();
        $propertyAccessor = new PropertyAccessor();

        foreach (array_filter(array_keys($matches), 'is_string') as $key) {
            if ('time' === $key && true !== $stamp = strtotime($matches[$key])) {
                $entry->setStamp($stamp);
            }

            $propertyAccessor->setValue($entry, $key, $matches[$key]);
        }

        return $entry;
    }
}
