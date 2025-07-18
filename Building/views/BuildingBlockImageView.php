<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockImageView extends BuildingBlockView
{
    /**
     * @var string
     */
    protected $image_dimension_shortname = 'original';

    public function setImageDimensionShortname($shortname)
    {
        $this->image_dimension_shortname = $shortname;
    }

    protected function define()
    {
        $this->definePart('image');
        $this->definePart('title');
        $this->definePart('description');
    }

    protected function displayContent(BuildingBlock $block)
    {
        if (!$block->image instanceof SiteImage) {
            throw new InvalidArgumentException(
                sprintf(
                    'The view %s can only display blocks with images.',
                    get_class($this)
                )
            );
        }

        $this->displayImage($block);
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
            $span->class = 'building-block-image-details';
            $span->open();

            foreach ($parts as $part) {
                echo $part;
            }

            $span->close();
        }
    }

    protected function displayImage(BuildingBlock $block)
    {
        if ($this->getMode('image') > SiteView::MODE_NONE) {
            $img = $block->image->getImgTag(
                $this->image_dimension_shortname
            );
            $img->display();
        }
    }

    protected function displayTitle(BuildingBlock $block)
    {
        $title = $block->image->getTitle();
        if ($this->getMode('title') > SiteView::MODE_NONE && $title != '') {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-image-title';
            $span->setContent($title);
            $span->display();
        }
    }

    protected function displayDescription(BuildingBlock $block)
    {
        if ($this->getMode('description') > SiteView::MODE_NONE
            && $block->image->description != '') {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-image-description';
            $span->setContent($block->image->description, 'text/xml');
            $span->display();
        }
    }

    protected function getCSSClassNames()
    {
        return array_merge(
            parent::getCSSClassNames(),
            ['building-block-image-view']
        );
    }
}
