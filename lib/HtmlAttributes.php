<?php

/**
 * @see       https://github.com/laminas/laminas-view for the canonical source repository
 * @copyright https://github.com/laminas/laminas-view/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-view/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\View\Helper;

use Laminas\Escaper\Escaper;
use Laminas\View\HtmlAttributesSet;

/**
 * Helper for creating HtmlAttributesSet objects
 */
class HtmlAttributes
{
    /**
     * Returns a new HtmlAttributesSet object, optionally initializing it with
     * the provided value.
     */
    public function __invoke(iterable $attributes = []): HtmlAttributesSet
    {
        return new HtmlAttributesSet(
            new Escaper(),
            new Escaper(),
            $attributes
        );
    }
}
