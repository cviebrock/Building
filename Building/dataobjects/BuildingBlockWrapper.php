<?php

/**
 * Wrapper for BuildingBlock objects.
 *
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 *
 * @see       BuildingBlock
 */
class BuildingBlockWrapper extends SwatDBRecordsetWrapper
{


    protected function init()
    {
        parent::init();
        $this->row_wrapper_class = SwatDBClassMap::get('BuildingBlock');
        $this->index_field = 'id';
    }


}
