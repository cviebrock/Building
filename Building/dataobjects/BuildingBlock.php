<?php

/**
 * Base object for CMS.
 *
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 *
 * @property int             $id
 * @property ?string         $bodytext
 * @property ?int            $displayorder
 * @property ?SwatDate       $createdate
 * @property ?SwatDate       $modified_date
 * @property ?SiteAttachment $attachment
 * @property ?SiteImage      $image
 * @property ?SiteVideoMedia $media
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
            SwatDBClassMap::get(SiteAttachment::class)
        );

        $this->registerInternalProperty(
            'image',
            SwatDBClassMap::get(SiteImage::class)
        );

        $this->registerInternalProperty(
            'media',
            SwatDBClassMap::get(SiteVideoMedia::class)
        );

        $this->id_field = 'integer:id';
    }
}
