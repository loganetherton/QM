<?php
/**
 * Custom changes in bootstrap TbMenu.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
Yii::import('bootstrap.widgets.TbMenu');

class CustomTbMenu extends TbMenu
{
    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please see {@link items} on what data might be in the item.
     * @return string the rendered item
     */
    protected function renderMenuItem($item)
    {
        $item['label'] = '<span class="text">'.$item['label'].'</span>';

        if(isset($item['icon'])) {
            if(strpos($item['icon'], 'icon') === false) {
                $pieces = explode(' ', $item['icon']);
                $item['icon'] = 'icon-'.implode(' icon-', $pieces);
            }

            $item['label'] = '<span class="icon"><i class="'.$item['icon'].'"></i></span> '.$item['label'];
        }

        if(!isset($item['linkOptions'])) {
            $item['linkOptions'] = array();
        }

        if(isset($item['items']) && !empty($item['items'])) {
            if(empty($item['url'])) {
                $item['url'] = '#';
            }

            if(isset($item['linkOptions']['class'])) {
                $item['linkOptions']['class'] .= ' dropdown-toggle';
            }
            else {
                $item['linkOptions']['class'] = 'dropdown-toggle';
            }

            $item['linkOptions']['data-toggle'] = 'dropdown';
            $item['label'] .= ' <span class="caret"></span>';
        }

        if(isset($item['url'])) {
            return CHtml::link($item['label'], $item['url'], $item['linkOptions']);
        }
        else {
            return $item['label'];
        }
    }
}