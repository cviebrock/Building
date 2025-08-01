<?php

/**
 * Container for package-wide static methods.
 *
 * @copyright 2014-2017 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class Building
{
    /**
     * The gettext domain for building.
     *
     * This is used to support multiple locales.
     */
    public const GETTEXT_DOMAIN = 'building';

    /**
     * Whether or not this package is initialized.
     *
     * @var bool
     */
    private static $is_initialized = false;

    /**
     * Translates a phrase.
     *
     * This is an alias for {@link self::gettext()}.
     *
     * @param string $message the phrase to be translated
     *
     * @return string the translated phrase
     */
    public static function _($message)
    {
        return self::gettext($message);
    }

    /**
     * Translates a phrase.
     *
     * This method relies on the php gettext extension and uses dgettext()
     * internally.
     *
     * @param string $message the phrase to be translated
     *
     * @return string the translated phrase
     */
    public static function gettext($message)
    {
        return dgettext(self::GETTEXT_DOMAIN, $message);
    }

    /**
     * Translates a plural phrase.
     *
     * This method should be used when a phrase depends on a number. For
     * example, use ngettext when translating a dynamic phrase like:
     *
     * - "There is 1 new item" for 1 item and
     * - "There are 2 new items" for 2 or more items.
     *
     * This method relies on the php gettext extension and uses dngettext()
     * internally.
     *
     * @param string $singular_message the message to use when the number the
     *                                 phrase depends on is one
     * @param string $plural_message   the message to use when the number the
     *                                 phrase depends on is more than one
     * @param int    $number           the number the phrase depends on
     *
     * @return string the translated phrase
     */
    public static function ngettext($singular_message, $plural_message, $number)
    {
        return dngettext(
            self::GETTEXT_DOMAIN,
            $singular_message,
            $plural_message,
            $number
        );
    }

    public static function setupGettext()
    {
        bindtextdomain(self::GETTEXT_DOMAIN, __DIR__ . '/../locale');
        bind_textdomain_codeset(self::GETTEXT_DOMAIN, 'UTF-8');
    }

    public static function init()
    {
        if (self::$is_initialized) {
            return;
        }

        Swat::init();
        Site::init();
        Admin::init();

        self::setupGettext();

        SiteViewFactory::addPath('Building/views');
        SiteViewFactory::registerView(
            'building-block-audio',
            BuildingBlockAudioView::class
        );
        SiteViewFactory::registerView(
            'building-block-video',
            BuildingBlockVideoView::class
        );
        SiteViewFactory::registerView(
            'building-block-image',
            BuildingBlockImageView::class
        );
        SiteViewFactory::registerView(
            'building-block-xhtml',
            BuildingBlockXHTMLView::class
        );
        SiteViewFactory::registerView(
            'building-block-attachment',
            BuildingBlockAttachmentView::class
        );
        SiteViewFactory::registerView(
            'building-block',
            BuildingBlockCompositeView::class
        );
        SiteViewFactory::registerView(
            'building-block-admin',
            BuildingBlockAdminCompositeView::class
        );

        SwatUI::mapClassPrefixToPath('Building', 'Building');

        self::$is_initialized = true;
    }

    /**
     * Don't allow instantiation of the Building object.
     *
     * This class contains only static methods and should not be instantiated.
     */
    private function __construct() {}
}
