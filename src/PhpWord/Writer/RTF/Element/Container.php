<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF\Element;

/**
 * Container element RTF writer
 *
 * @since 0.11.0
 */
class Container extends \PhpOffice\PhpWord\Writer\HTML\Element\Container
{
    /**
     * Write container
     *
     * @return string
     */
    public function write()
    {
        $container = $this->element;
        if (!$container instanceof \PhpOffice\PhpWord\Element\AbstractContainer) {
            return;
        }
        $containerClass = substr(get_class($container), strrpos(get_class($container), '\\') + 1);
        $withoutP = in_array($containerClass, array('TextRun', 'Footnote', 'Endnote')) ? true : false;
        $content = '';

        $elements = $container->getElements();
        foreach ($elements as $element) {
            $writerClass = str_replace('\\Element', '\\Writer\\RTF\\Element', get_class($element));
            if (class_exists($writerClass)) {
                $writer = new $writerClass($this->parentWriter, $element, $withoutP);
                $content .= '{';
                $content .= $writer->write();
                $content .= '}' . PHP_EOL;
            }
        }

        return $content;
    }
}
