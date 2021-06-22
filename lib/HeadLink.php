<?php

namespace Laminas\View\Helper;

class HeadLink
{
    public function __invoke(array $attributes = null, $placement = null)
    {
        return $this;
    }

    public function appendStylesheet($href, $media = 'screen', $conditionalStylesheet = '', $extras = [])
    {
        return $this;
    }

    public function prependStylesheet($href, $media = 'screen', $conditionalStylesheet = '', $extras = [])
    {
        return $this;
    }
}
