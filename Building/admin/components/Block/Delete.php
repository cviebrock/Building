<?php

/**
 * @copyright 2014-2016 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class BuildingBlockDelete extends AdminDBDelete
{
    // process phase


    protected function processDBData(): void
    {
        parent::processDBData();

        $sql = 'delete from Block where id in (%s)';

        $item_list = $this->getItemList('integer');
        $sql = sprintf($sql, $item_list);
        $num = SwatDB::exec($this->app->db, $sql);

        if ($num > 0) {
            $this->app->messages->add(
                new SwatMessage(Building::_('Content has been deleted.'))
            );
        }
    }



    // build phase


    protected function buildInternal()
    {
        parent::buildInternal();

        $item_list = $this->getItemList('integer');
        $blocks = SwatDB::query(
            $this->app->db,
            sprintf(
                'select * from Block where id in (%s)',
                $this->getItemList('integer')
            ),
            SwatDBClassMap::get('BuildingBlockWrapper')
        );

        $view = SiteViewFactory::get($this->app, 'building-block');
        $view->getImageView()->setImageDimensionShortname('thumb');

        ob_start();

        $header = new SwatHtmlTag('h1');
        $header->setContent('Delete the following content?');
        $header->display();

        foreach ($blocks as $block) {
            $view->display($block);
        }

        $message = $this->ui->getWidget('confirmation_message');
        $message->content = ob_get_clean();
        $message->content_type = 'text/xml';
    }


}
