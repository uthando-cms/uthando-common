<?php declare(strict_types=1);
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @author      Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link        https://github.com/uthando-cms for the canonical source repository
 * @copyright   Copyright (c) 28/09/17 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license     see LICENSE
 */

namespace UthandoCommon\Options;

class HtmlPurifierOptions
{
    protected $config = [
        'Cache.SerializerPath' => APPLICATION_PATH . '/data/cache',
        //'Cache.SerializerPath'  => null,
        'Attr.ID.HTML5'         => true,
        'HTML.DefinitionID'     => 'html5-definitions',
        'HTML.DefinitionRev'    => 1,
        'HTML.SafeIframe'       => true,
        'URI.SafeIframeRegexp'  =>  '%^(http:|https:)?//(www.youtube(?:-nocookie)?.com/embed/|player.vimeo.com/video/)%',
    ];

    protected $HtmlDefinition = [
        // allow html5 elements
        'elements'      => [
            // allow <a> to be block level
            ['a', 'Block', 'Flow', 'Common', []],
            // new elements
            ['section', 'Block', 'Flow', 'Common', []],
            ['nav',     'Block', 'Flow', 'Common', []],
            ['article', 'Block', 'Flow', 'Common', []],
            ['aside',   'Block', 'Flow', 'Common', []],
            ['header',  'Block', 'Flow', 'Common', []],
            ['footer',  'Block', 'Flow', 'Common', []],
            ['address', 'Block', 'Flow', 'Common', []],
            ['hgroup', 'Block', 'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common', []],
            ['figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common', []],
            ['video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', [
                'src' => 'URI',
                'type' => 'Text',
                'width' => 'Length',
                'height' => 'Length',
                'poster' => 'URI',
                'preload' => 'Enum#auto,metadata,none',
                'controls' => 'Bool',
            ]],
            ['source', 'Block', 'Flow', 'Common', [
                'src' => 'URI',
                'type' => 'Text',
            ]],
            ['s',    'Inline', 'Inline', 'Common', []],
            ['var',  'Inline', 'Inline', 'Common', []],
            ['sub',  'Inline', 'Inline', 'Common', []],
            ['sup',  'Inline', 'Inline', 'Common', []],
            ['mark', 'Inline', 'Inline', 'Common', []],
            ['wbr',  'Inline', 'Empty', 'Core', []],
            ['ins', 'Block', 'Flow', 'Common', [
                'cite' => 'URI',
                'datetime' => 'CDATA',
            ]],
            ['del', 'Block', 'Flow', 'Common', [
                'cite' => 'URI',
                'datetime' => 'CDATA',
            ]],
        ],
        'attributes'    => [
            ['iframe', 'allowfullscreen', 'Bool'],
            ['table', 'height', 'Text'],
            ['td', 'border', 'Text'],
            ['th', 'border', 'Text'],
            ['tr', 'width', 'Text'],
            ['tr', 'height', 'Text'],
            ['tr', 'border', 'Text'],
        ],
    ];

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): HtmlPurifierOptions
    {
        $this->config = $config;
        return $this;
    }

    public function getHtmlDefinition(): array
    {
        return $this->HtmlDefinition;
    }

    public function setHtmlDefinition(array $HtmlDefinition): HtmlPurifierOptions
    {
        $this->HtmlDefinition = $HtmlDefinition;
        return $this;
    }
}
