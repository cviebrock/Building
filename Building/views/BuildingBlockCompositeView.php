<?php

/**
 * Special view that can display any type of block.
 *
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockCompositeView extends BuildingBlockView
{
    /**
     * @var array
     */
    protected $views = [];

    // display content

    public function display($block)
    {
        if (!$block instanceof BuildingBlock) {
            throw new InvalidArgumentException(
                sprintf(
                    'The view %s can only display BuildingBlock objects.',
                    get_class($this)
                )
            );
        }

        $view = $this->getViewForBlock($block);
        $view->display($block);
    }

    public function getViewForBlock(BuildingBlock $block)
    {
        return $this->getViewForType(
            BuildingBlockViewFactory::getViewType($block)
        );
    }

    protected function displayContent(BuildingBlock $block) {}

    // view setters

    public function setAttachmentView(BuildingBlockAttachmentView $view)
    {
        $this->views['building-block-attachment'] = $view;
    }

    public function setAudioView(BuildingBlockAudioView $view)
    {
        $this->views['building-block-audio'] = $view;
    }

    public function setXHTMLView(BuildingBlockXHTMLView $view)
    {
        $this->views['building-block-xhtml'] = $view;
    }

    public function setImageView(BuildingBlockImageView $view)
    {
        $this->views['building-block-image'] = $view;
    }

    public function setVideoView(BuildingBlockVideoView $view)
    {
        $this->views['building-block-video'] = $view;
    }

    // view getters

    public function getAttachmentView()
    {
        return $this->getViewForType('building-block-attachment');
    }

    public function getAudioView()
    {
        return $this->getViewForType('building-block-audio');
    }

    public function getXHTMLView()
    {
        return $this->getViewForType('building-block-xhtml');
    }

    public function getImageView()
    {
        return $this->getViewForType('building-block-image');
    }

    public function getVideoView()
    {
        return $this->getViewForType('building-block-video');
    }

    // helpers

    protected function getViewForType($type)
    {
        if (!isset($this->views[$type])) {
            $this->views[$type] = $this->createCompositeViewForType($type);
        }

        return $this->views[$type];
    }

    protected function createCompositeViewForType($type)
    {
        return SiteViewFactory::get($this->app, $type);
    }
}
