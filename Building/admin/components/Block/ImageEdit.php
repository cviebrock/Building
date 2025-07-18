<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockImageEdit extends BuildingBlockEdit
{
    /**
     * @var SiteImageSet
     */
    protected $image_set;

    protected function getUiXml()
    {
        return __DIR__ . '/image-edit.xml';
    }

    protected function getImageSetShortname()
    {
        return 'block';
    }

    protected function getImageSet()
    {
        if (!$this->image_set instanceof SiteImageSet) {
            if ($this->getObject()->image instanceof SiteImage) {
                $this->image_set = $this->getObject()->image->image_set;
            } else {
                $this->image_set = SwatDBClassMap::new(SiteImageSet::class);
                $this->image_set->setDatabase($this->app->db);
                $shortname = $this->getImageSetShortname();
                if (!$this->image_set->loadByShortname($shortname)) {
                    throw new AdminNotFoundException(
                        sprintf(
                            'Image set with shortname “%s” not found.',
                            $shortname
                        )
                    );
                }
            }
        }

        return $this->image_set;
    }

    protected function getNewImageInstance()
    {
        $image = SwatDBClassMap::new(SiteImage::class);
        $image->setDatabase($this->app->db);
        $image->image_set = $this->getImageSet();

        return $image;
    }

    // init phase

    protected function initInternal()
    {
        parent::initInternal();

        if ($this->isNew()) {
            $this->ui->getWidget('image_upload')->required = true;
        }
    }

    protected function initObject()
    {
        parent::initObject();

        $block = $this->getObject();
        if (!$this->isNew() && !$block->image instanceof SiteImage) {
            throw new AdminNotFoundException(
                'Can only edit image content.'
            );
        }
    }

    // process phase

    protected function updateObject()
    {
        parent::updateObject();

        $this->processImage();

        if ($this->getObject()->image instanceof SiteImage) {
            $this->assignUiValuesToObject(
                $this->getObject()->image,
                [
                    'title',
                    'description',
                ]
            );
        }
    }

    protected function processImage()
    {
        $upload = $this->ui->getWidget('image_upload');
        if ($upload->isUploaded()) {
            $block = $this->getObject();

            $image = $this->getNewImageInstance();
            $image->setFileBase('../images');
            $image->process($upload->getTempFileName());

            // Delete the old image. Prevents browser/CDN caching.
            if (!$this->isNew()) {
                $block->image->setFileBase('../images');
                $block->image->delete();
            }

            $block->image = $image;
        }
    }

    protected function saveObject()
    {
        parent::saveObject();

        if ($this->getObject()->image instanceof SiteImage) {
            $this->getObject()->image->save();
        }
    }

    // build phase

    protected function loadObject()
    {
        parent::loadObject();

        $image = $this->getObject()->image;
        $preview = $this->ui->getWidget('image_preview');

        $preview->visible = true;
        $preview->image = $image->getUri('thumb', '../');
        $preview->width = $image->getWidth('thumb');
        $preview->height = $image->getHeight('thumb');
        $preview->preview_image = $image->getUri('original', '../');
        $preview->preview_width = $image->getWidth('original');
        $preview->preview_height = $image->getHeight('original');

        if ($this->getObject()->image instanceof SiteImage) {
            $this->assignObjectValuesToUi(
                $this->getObject()->image,
                [
                    'title',
                    'description',
                ]
            );
        }
    }

    protected function buildNavBar()
    {
        parent::buildNavBar();

        $this->navbar->popEntry();

        if ($this->isNew()) {
            $this->navbar->createEntry(Building::_('New Image Content'));
        } else {
            $this->navbar->createEntry(Building::_('Edit Image Content'));
        }
    }
}
