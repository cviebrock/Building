<?php

/**
 * @copyright 2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockVideoEdit extends BuildingBlockEdit
{
    /**
     * @var SiteVideoMedia
     */
    protected $media;

    protected function getUiXml()
    {
        return __DIR__ . '/video-edit.xml';
    }

    protected function getMedia()
    {
        if (!$this->media instanceof SiteVideoMedia) {
            if ($this->getObject()->media instanceof SiteVideoMedia) {
                $this->media = $this->getObject()->media;
            } else {
                $media_id = $this->app->initVar('media');
                if ($media_id === null) {
                    $form = $this->ui->getWidget('edit_form');
                    $media_id = $form->getHiddenField('media');
                }

                $this->media = SwatDBClassMap::new(SiteVideoMedia::class);
                $this->media->setDatabase($this->app->db);
                if (!$this->media->load($media_id)) {
                    throw new AdminNotFoundException(
                        sprintf(
                            'Media with id “%s” not found.',
                            $media_id
                        )
                    );
                }
            }
        }

        return $this->media;
    }

    // init phase

    protected function initObject()
    {
        parent::initObject();

        $block = $this->getObject();
        if (!$this->isNew() && !$block->media instanceof SiteVideoMedia) {
            throw new AdminNotFoundException(
                'Can only edit video content.'
            );
        }
    }

    // process phase

    protected function updateObject()
    {
        parent::updateObject();

        $media = $this->getMedia();
        $this->getObject()->media = $media->id;

        $this->assignUiValuesToObject(
            $this->getObject()->media,
            [
                'title',
                'description',
            ]
        );
    }

    protected function saveObject()
    {
        parent::saveObject();

        $this->getObject()->media->save();
    }

    // build phase

    protected function buildInternal()
    {
        parent::buildInternal();

        $media = $this->getMedia();
        $media->setFileBase('media');

        $this->ui->getWidget('edit_form')->addHiddenField('media', $media->id);

        $player = $media->getMediaPlayer($this->app);
        ob_start();
        $player->display();
        $this->ui->getWidget('player')->content = ob_get_clean();
        $this->layout->addHtmlHeadEntrySet($player->getHtmlHeadEntrySet());
    }

    protected function loadObject()
    {
        parent::loadObject();

        $this->assignObjectValuesToUi(
            $this->getObject()->media,
            [
                'title',
                'description',
            ]
        );
    }

    protected function buildNavBar()
    {
        parent::buildNavBar();

        $this->navbar->popEntry();

        if ($this->isNew()) {
            $this->navbar->createEntry(Building::_('New Video Content'));
        } else {
            $this->navbar->createEntry(Building::_('Edit Video Content'));
        }
    }
}
