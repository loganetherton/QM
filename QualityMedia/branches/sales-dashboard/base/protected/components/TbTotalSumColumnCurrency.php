<?php
Yii::import('bootstrap.widgets.TbTotalSumColumn');

/**
 * Grid View column class to show Totals in currency format
 */
class TbTotalSumColumnCurrency extends TbTotalSumColumn
{
	protected function renderDataCellContent($row, $data)
	{
		ob_start();
		parent::renderDataCellContent($row, $data);
		$value = ob_get_clean();

		echo Yii::app()->getComponent('format')->formatMoney($value);
	}

	protected function renderFooterCellContent()
	{
		if(is_null($this->total))
			return parent::renderFooterCellContent();

		echo $this->totalValue? $this->evaluateExpression($this->totalValue, array('total'=>$this->total)) : Yii::app()->getComponent('format')->formatMoney($this->total, $this->type);
	}
}