<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockAttachmentEdit extends BuildingBlockEdit
{
    /**
     * @var SiteAttachmentSet
     */
    protected $attachment_set;

    protected function getUiXml()
    {
        return __DIR__ . '/attachment-edit.xml';
    }

    protected function getAttachmentSetShortname()
    {
        return 'block';
    }

    protected function getAttachmentSet()
    {
        if (!$this->attachment_set instanceof SiteAttachmentSet) {
            if ($this->getObject()->attachment instanceof SiteAttachment) {
                $this->attachment_set =
                    $this->getObject()->attachment->attachment_set;
            } else {
                $this->attachment_set = SwatDBClassMap::new(SiteAttachmentSet::class);
                $this->attachment_set->setDatabase($this->app->db);
                $shortname = $this->getAttachmentSetShortname();
                if (!$this->attachment_set->loadByShortname($shortname)) {
                    throw new AdminNotFoundException(
                        sprintf(
                            'Attachment set with shortname “%s” not found.',
                            $shortname
                        )
                    );
                }
            }
        }

        return $this->attachment_set;
    }

    protected function getNewAttachmentInstance()
    {
        $attachment = SwatDBClassMap::new(SiteAttachment::class);
        $attachment->setDatabase($this->app->db);
        $attachment->attachment_set = $this->getAttachmentSet();

        return $attachment;
    }

    // init phase

    protected function initInternal()
    {
        parent::initInternal();

        if ($this->isNew()) {
            $this->ui->getWidget('file_upload')->required = true;
        }
    }

    protected function initObject()
    {
        parent::initObject();

        $block = $this->getObject();
        if (!$this->isNew() && !$block->attachment instanceof SiteAttachment) {
            throw new AdminNotFoundException(
                'Can only edit attachment content.'
            );
        }
    }

    // process phase

    protected function updateObject()
    {
        parent::updateObject();

        $this->processAttachment();

        if ($this->getObject()->attachment instanceof SiteAttachment) {
            $this->assignUiValuesToObject(
                $this->getObject()->attachment,
                [
                    'title',
                ]
            );
        }
    }

    protected function processAttachment()
    {
        $upload = $this->ui->getWidget('file_upload');
        if ($upload->isUploaded()) {
            $block = $this->getObject();

            $attachment = $this->getNewAttachmentInstance();

            $attachment->file_size = $upload->getSize();
            $attachment->mime_type = $upload->getMimeType();
            $attachment->original_filename = $upload->getFileName();
            $attachment->createdate = $this->getCurrentTime();

            $attachment->setFileBase($this->getFileBase());

            $this->assignUiValuesToObject(
                $attachment,
                [
                    'title',
                ]
            );

            $attachment->process($upload->getTempFileName());

            // Delete the old attachment. Prevents browser/CDN caching.
            if (!$this->isNew()) {
                $block->attachment->setFileBase($this->getFileBase());
                $block->attachment->delete();
            }

            $block->attachment = $attachment;
        }
    }

    protected function saveObject()
    {
        parent::saveObject();

        if ($this->getObject()->attachment instanceof SiteAttachment) {
            $this->getObject()->attachment->save();
        }
    }

    protected function getFileBase()
    {
        return '../attachments';
    }

    // build phase

    protected function loadObject()
    {
        parent::loadObject();

        $attachment = $this->getObject()->attachment;

        if ($attachment instanceof SiteAttachment) {
            $this->assignObjectValuesToUi(
                $attachment,
                [
                    'title',
                ]
            );
        }
    }

    protected function buildNavBar()
    {
        parent::buildNavBar();

        $this->navbar->popEntry();

        if ($this->isNew()) {
            $this->navbar->createEntry(Building::_('New File Content'));
        } else {
            $this->navbar->createEntry(Building::_('Edit File Content'));
        }
    }
}
