<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
abstract class BuildingBlockView extends SiteView
{
    /**
     * @var array
     *
     * @see BuildingBlockView::addCSSClassName()
     * @see BuildingBlockView::removeCSSClassName()
     */
    protected $css_classes = [];

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

        $container = new SwatHtmlTag('div');
        $container->id = 'block_' . $block->id;
        $container->class = implode(' ', $this->getCSSClassNames());
        $container->open();

        $this->displayContent($block);

        $container->close();
    }

    public function setCSSClassNames(array $class_names)
    {
        $this->css_classes = array_unique($class_names);
    }

    public function addCSSClassName($class_name)
    {
        $this->css_classes = array_unique(
            array_merge(
                [$class_name],
                $this->css_classes
            )
        );
    }

    public function removeCSSClassName($class_name)
    {
        $this->css_classes = array_diff(
            $this->css_classes,
            [$class_name]
        );
    }

    abstract protected function displayContent(BuildingBlock $block);

    protected function getCSSClassNames()
    {
        return array_unique(
            array_merge(
                ['building-block-view'],
                $this->css_classes
            )
        );
    }
}
