<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockXHTMLView extends BuildingBlockView
{


    /**
     * @var int
     *
     * @see BuildingBlockXHTMLView::setBodySummaryLength()
     */
    protected $body_summary_length = 100;




    public function setBodySummaryLength($length)
    {
        $this->body_summary_length = (int) $length;
    }




    protected function define()
    {
        $this->definePart('body');
    }




    protected function displayContent(BuildingBlock $block)
    {
        $this->displayBody($block);
    }




    protected function displayBody(BuildingBlock $block)
    {
        if ($block->bodytext != '') {
            if ($this->getMode('body') === SiteView::MODE_ALL) {
                $div = new SwatHtmlTag('div');
                $div->class = 'building-block-bodytext';
                $div->setContent($block->bodytext, 'text/xml');
                $div->display();
            } elseif ($this->getMode('body') === SiteView::MODE_SUMMARY) {
                $bodytext = SwatString::condense(
                    $block->bodytext,
                    $this->body_summary_length
                );
                $div = new SwatHtmlTag('div');
                $div->class = 'building-block-bodytext';
                $div->setContent($bodytext, 'text/xml');
                $div->display();
            }
        }
    }




    protected function getCSSClassNames()
    {
        return array_merge(
            parent::getCSSClassNames(),
            ['building-block-xhtml-view']
        );
    }


}
