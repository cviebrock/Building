<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockVideoView extends BuildingBlockView
{
    protected function define()
    {
        $this->definePart('video');
        $this->definePart('title');
        $this->definePart('description');
    }

    protected function displayContent(BuildingBlock $block)
    {
        if (!$block->media instanceof SiteVideoMedia) {
            throw new InvalidArgumentException(
                sprintf(
                    'The view %s can only display blocks with videos.',
                    get_class($this)
                )
            );
        }

        $this->displayVideo($block);
        $this->displayDetails($block);
    }

    protected function displayDetails(BuildingBlock $block)
    {
        $parts = [];

        ob_start();
        $this->displayTitle($block);
        $title = ob_get_clean();
        if ($title != '') {
            $parts[] = $title;
        }

        ob_start();
        $this->displayDescription($block);
        $description = ob_get_clean();
        if ($description != '') {
            $parts[] = $description;
        }

        if (count($parts) > 0) {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-video-details';
            $span->open();

            foreach ($parts as $part) {
                echo $part;
            }

            $span->close();
        }
    }

    protected function displayVideo(BuildingBlock $block)
    {
        if ($this->getMode('video') > SiteView::MODE_NONE) {
            $block->media->setFileBase('media');
            $player = $block->media->getMediaPlayer($this->app);
            $player->display();

            $page = $this->app->getPage();
            $page->layout->addHtmlHeadEntrySet($player->getHtmlHeadEntrySet());
        }
    }

    protected function displayTitle(BuildingBlock $block)
    {
        $title = $block->media->getTitle();
        if ($this->getMode('title') > SiteView::MODE_NONE && $title != '') {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-video-title';
            $span->setContent($title);
            $span->display();
        }
    }

    protected function displayDescription(BuildingBlock $block)
    {
        if ($this->getMode('description') > SiteView::MODE_NONE
            && $block->media->description != '') {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-video-description';
            $span->setContent($block->media->description, 'text/xml');
            $span->display();
        }
    }

    protected function getCSSClassNames()
    {
        return array_merge(
            parent::getCSSClassNames(),
            ['building-block-video-view']
        );
    }
}
