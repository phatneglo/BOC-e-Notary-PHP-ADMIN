<?php

namespace PHPMaker2024\eNotary;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Page Rendering Event
 */
class PageRenderingEvent extends GenericEvent
{
    public const NAME = "page.rendering";

    public function getPage(): mixed
    {
        return $this->subject;
    }
}
