<?php

namespace JMikola\Twig;

class FacebookEventExtension extends \Twig_Extension
{
    /**
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        return array(
            'pretty_date' => new \Twig_Filter_Method($this, 'prettyDate'),
        );
    }

    /**
     * Formats a date in the style of Facebook's event times.
     *
     * @param string|DateTime     $date
     * @param string|DateTimeZone $timezone
     * @return string
     */
    public function prettyDate($date, $timezone = null)
    {
        $timezone = isset($timezone) ? ($timezone instanceof \DateTimeZone ? $timezone : new \DateTimeZone($timezone)) : null;

        if (!$date instanceof \DateTime) {
            $time = (ctype_digit($date) ? '@' : '') . $date;
            $date = $timezone ? new \DateTime($time, $timezone) : new \DateTime($time);
        }

        if ($timezone) {
            $date = clone $date;
            $date->setTimezone($timezone);
        }

        $diff = $date->diff(new \DateTime(), true);

        if ($diff->days == 0) {
            return 'Today '.$date->format('g:ia');
        } elseif ($diff->days == 1) {
            return 'Tomorrow '.$date->format('g:ia');
        } elseif ($diff->days <= 6) {
            return $date->format('l g:ia');
        } else {
            return $date->format('l, F j \a\t g:ia');
        }
    }

    /**
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'facebook_event';
    }
}
