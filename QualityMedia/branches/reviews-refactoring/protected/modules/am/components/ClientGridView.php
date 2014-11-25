<?php
/**
 * Client list view grid, adds an additional row on each render
 *
 * @property string $additionalRowTemplate
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class ClientGridView extends GridView
{
    /**
     * @var string $additionalRowTemplate A template used to generate an additional row
     */
    public $additionalRowTemplate;

    /**
     * Renders an individual row
     *
     * @access public
     * @param array $row
     * @return void
     */
    public function renderTableRow($row)
    {
        parent::renderTableRow($row);

        $viewFile = $this->getOwner()->getViewFile($this->additionalRowTemplate);
        $this->getOwner()->renderFile($viewFile, $this->dataProvider->data[$row]);
    }
}