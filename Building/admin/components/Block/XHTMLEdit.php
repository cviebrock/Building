<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockXHTMLEdit extends BuildingBlockEdit
{
    protected function getUiXml()
    {
        return __DIR__ . '/xhtml-edit.xml';
    }

    protected function getObjectUiValueNames()
    {
        return ['bodytext'];
    }

    // build phase

    protected function buildNavBar()
    {
        parent::buildNavBar();

        $this->navbar->popEntry();

        if ($this->isNew()) {
            $this->navbar->createEntry(Building::_('New Text Content'));
        } else {
            $this->navbar->createEntry(Building::_('Edit Text Content'));
        }
    }
}
