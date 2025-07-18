<?php

/**
 * Base object for CMS.
 *
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlock extends SwatDBDataObject
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $bodytext;

    /**
     * @var int
     */
    public $displayorder;

    /**
     * @var SwatDate
     */
    public $createdate;

    /**
     * @var SwatDate
     */
    public $modified_date;

    protected function init()
    {
        parent::init();

        $this->table = 'Block';

        $this->registerDateProperty('createdate');
        $this->registerDateProperty('modified_date');

        $this->registerInternalProperty(
            'attachment',
            SwatDBClassMap::get('SiteAttachment')
        );

        $this->registerInternalProperty(
            'image',
            SwatDBClassMap::get('SiteImage')
        );

        $this->registerInternalProperty(
            'media',
            SwatDBClassMap::get('SiteVideoMedia')
        );

        $this->id_field = 'integer:id';
    }
}
