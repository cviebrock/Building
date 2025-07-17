<?php

/**
 * Block view that adds admin action links.
 *
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockAdminCompositeView extends BuildingBlockCompositeView
{
    /**
     * @var SwatToolLink
     */
    protected $edit_link;

    /**
     * @var SwatToolLink
     */
    protected $delete_link;

    public function __construct(SiteApplication $app)
    {
        parent::__construct($app);

        $this->edit_link = new SwatToolLink();
        $this->edit_link->setFromStock('edit');
        $this->edit_link->title = Building::_('Edit');
        $this->edit_link->classes[] = 'building-block-edit-link';

        $this->delete_link = new SwatToolLink();
        $this->delete_link->setFromStock('delete');
        $this->delete_link->title = Building::_('Delete');
        $this->delete_link->classes[] = 'building-block-delete-link';
    }

    public function display($block)
    {
        ob_start();
        parent::display($block);
        $composite_view = ob_get_clean();

        if ($composite_view != '') {
            echo '<div class="building-block-admin-view">';
            $this->displayHeader($block);
            echo '<div class="building-block-admin-view-content">';
            echo $composite_view;
            echo '</div>';
            echo '</div>';
        }
    }

    protected function define()
    {
        $this->definePart('summary');
        $this->definePart('edit-link');
        $this->definePart('delete-link');
    }

    protected function displayHeader(BuildingBlock $block)
    {
        $parts = [];

        ob_start();
        $this->displaySummary($block);
        $summary = ob_get_clean();
        if ($summary != '') {
            $parts[] = $summary;
        }

        ob_start();
        $this->displayEditLink($block);
        $link = ob_get_clean();
        if ($link != '') {
            $parts[] = $link;
        }

        $type = BuildingBlockViewFactory::getViewType($block);
        if ($type === 'building-block-video') {
            $a_tag = new SwatHtmlTag('a');
            $a_tag->class = 'swat-tool-link swat-tool-link-edit';
            $a_tag->setContent(Building::_('Set Poster Frame'));
            $a_tag->href = 'Media/PosterFrame?id=' . $block->media->id;
            $parts[] = $a_tag;
        }

        ob_start();
        $this->displayDeleteLink($block);
        $link = ob_get_clean();
        if ($link != '') {
            $parts[] = $link;
        }

        if (count($parts) > 0) {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-admin-links';
            $span->open();

            foreach ($parts as $part) {
                echo $part;
            }

            $span->close();
        }
    }

    protected function displayEditLink(BuildingBlock $block)
    {
        if ($this->getMode('edit-link') > SiteView::MODE_NONE) {
            $link = $this->getLink('edit-link');

            if ($link === false) {
                $this->edit_link->sensitive = false;
            } else {
                if ($link === true) {
                    $type = BuildingBlockViewFactory::getViewType($block);
                    switch ($type) {
                        case 'building-block-audio':
                            $link = sprintf('Block/AudioEdit?id=%s', $block->id);
                            break;

                        case 'building-block-video':
                            $link = sprintf('Block/VideoEdit?id=%s', $block->id);
                            break;

                        case 'building-block-image':
                            $link = sprintf('Block/ImageEdit?id=%s', $block->id);
                            break;

                        case 'building-block-attachment':
                            $link = sprintf(
                                'Block/AttachmentEdit?id=%s',
                                $block->id
                            );
                            break;

                        case 'building-block-xhtml':
                        default:
                            $link = sprintf('Block/XHTMLEdit?id=%s', $block->id);
                            break;
                    }
                }
                $this->edit_link->link = $link;
                $this->edit_link->sensitive = true;
            }

            $this->edit_link->display();
        }
    }

    protected function displayDeleteLink(BuildingBlock $block)
    {
        if ($this->getMode('delete-link') > SiteView::MODE_NONE) {
            $link = $this->getLink('delete-link');

            if ($link === false) {
                $this->delete_link->sensitive = false;
            } else {
                if ($link === true) {
                    $link = sprintf('Block/Delete?id=%s', $block->id);
                }
                $this->delete_link->link = $link;
                $this->delete_link->sensitive = true;
            }

            $this->delete_link->display();
        }
    }

    protected function displaySummary(BuildingBlock $block)
    {
        if ($this->getMode('summary') > SiteView::MODE_NONE) {
            $type = BuildingBlockViewFactory::getViewType($block);
            switch ($type) {
                case 'building-block-audio':
                    $summary = Building::_('Audio Content');
                    break;

                case 'building-block-video':
                    $summary = Building::_('Video Content');
                    break;

                case 'building-block-image':
                    $summary = Building::_('Image Content');
                    break;

                case 'building-block-attachment':
                    $summary = Building::_('Attachment');
                    break;

                case 'building-block-xhtml':
                default:
                    $summary = Building::_('Text Content');
                    break;
            }

            $header = new SwatHtmlTag('h4');
            $header->setContent($summary);
            $header->class = 'building-block-admin-summary';
            $header->display();
        }
    }
}
