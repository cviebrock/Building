<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockAttachmentView extends BuildingBlockView
{
    protected function define()
    {
        $this->definePart('content'); // used to set link that wraps content
        $this->definePart('icon');
        $this->definePart('title');
        $this->definePart('filesize');
        $this->definePart('mime-type');
    }

    protected function displayContent(BuildingBlock $block)
    {
        if (!$block->attachment instanceof SiteAttachment) {
            throw new InvalidArgumentException(
                sprintf(
                    'The view %s can only display blocks with attachments.',
                    get_class($this)
                )
            );
        }

        $link = $this->getLink('content');
        if ($link === false) {
            $wrapper = new SwatHtmlTag('span');
        } else {
            $wrapper = new SwatHtmlTag('a');
            if ($link === true) {
                $wrapper->href = $block->attachment->getUri();
            } else {
                $wrapper->href = $link;
            }
        }

        $wrapper->class = 'building-block-attachment-wrapper';
        $wrapper->open();

        $this->displayIcon($block);

        ob_start();
        $this->displayTitle($block);
        $title = ob_get_clean();

        ob_start();
        $this->displayDetails($block);
        $details = ob_get_clean();

        if ($title != '' || $details != '') {
            echo '<span class="building-block-attachment-description">';
            echo $title, $details;
            echo '</span>';
        }

        $wrapper->close();
    }

    protected function displayDetails(BuildingBlock $block)
    {
        $parts = [];

        ob_start();
        $this->displayMimeType($block);
        $mime_type = ob_get_clean();
        if ($mime_type != '') {
            $parts[] = $mime_type;
        }

        ob_start();
        $this->displayFilesize($block);
        $filesize = ob_get_clean();
        if ($filesize != '') {
            $parts[] = $filesize;
        }

        if (count($parts) > 0) {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-attachment-details';
            $span->setContent(implode(' - ', $parts), 'text/xml');
            $span->display();
        }
    }

    protected function displayIcon(BuildingBlock $block)
    {
        if ($this->getMode('icon') > SiteView::MODE_NONE) {
            $span = new SwatHtmlTag('span');

            $mime_type_class = sprintf(
                'building-block-attachment-icon-%s',
                mb_strtolower(
                    str_replace(
                        '/',
                        '-',
                        $block->attachment->mime_type
                    )
                )
            );
            $span->class = 'building-block-attachment-icon ' . $mime_type_class;

            $span->setContent('');
            $span->display();
        }
    }

    protected function displayTitle(BuildingBlock $block)
    {
        if ($this->getMode('title') > SiteView::MODE_NONE) {
            $title = $block->attachment->title;
            if ($title == '') {
                $title = $block->attachment->getFilename();
            }

            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-attachment-title';
            $span->setContent($title);
            $span->display();
        }
    }

    protected function displayMimeType(BuildingBlock $block)
    {
        if ($this->getMode('mime-type') > SiteView::MODE_NONE) {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-attachment-mime-type';
            $span->setContent($block->attachment->getHumanFileType());
            $span->display();
        }
    }

    protected function displayFilesize(BuildingBlock $block)
    {
        if ($this->getMode('filesize') > SiteView::MODE_NONE) {
            $span = new SwatHtmlTag('span');
            $span->class = 'building-block-attachment-filesize';
            $span->setContent($block->attachment->getFormattedFileSize());
            $span->display();
        }
    }

    protected function getCSSClassNames()
    {
        return array_merge(
            parent::getCSSClassNames(),
            ['building-block-attachment-view']
        );
    }
}
