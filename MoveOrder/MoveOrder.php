<?php
namespace FW\MoveOrder;

/**
 * Move String:  Сортировка данных внутри Базы Данных
 * NOTE:         Requires PHP version 5 or later, MYSQL version 5 or later, intellect > then Monkey!
 */

class MoveOrder
{

    public $table         = "move_string";
    public $group         = [];
	public $row_id        = 'id';
    public $cell          = 'order';
    public $reorder       = 1000;
    public $id;

    public function __construct($id = false)
    {
		$this->id = $id;
    }

/**
 *    Метод query: Обработка и упрощение запросов для класса
 */

    private function query($query)
    {
		if(!is_array($query)) {
			return q($query);
		} else {
			foreach($query as $v) {
				q($v);
			}
			return true;
		}
    }


/**
 *    Метод getGroup: Получение группировки
 */

    private function getGroup($start = "WHERE",$table = NULL)
    {
        if(count($this->group))
        {
            $temp = [];
            foreach($this->group as $k=>$v)
                $temp[] = (!empty($table) ? $table."." : '')."`".$k."` = '".$v."'";
            return $start." ".implode(' AND ',$temp);
        }
        else
            return '';
    }


/**
 *    Метод mysqlChange: Перемещаем запись выше или ниже нынешней позиции
 */
		
    public function mysqlChange($move_do)
    {
        $group = $this->getGroup('AND','a');
        if(!empty($group))
            $group = $group.$this->getGroup(' AND','b');

        if($move_do == 'up')
        {
            $znak = '<';
            $sort = 'DESC';
        }
        else
        {
            $znak = '>';
            $sort = '';
        }

        $res = $this->query("SELECT a.`".$this->row_id."` AS `a_id`,a.`".$this->cell."` AS `a_order`,
                                    b.`".$this->row_id."` AS `b_id`,b.`".$this->cell."` AS `b_order`
                             FROM `".$this->table."` a
                             LEFT JOIN `".$this->table."` b ON b.`".$this->cell."` ".$znak." a.`".$this->cell."`
                             WHERE a.`".$this->row_id."` = ".(int)$this->id." 
                               AND b.`".$this->cell."` is NOT NULL
                             ".$group."
                             ORDER BY b.`".$this->cell."` ".$sort."
                             LIMIT 1
                            ");
        if($res->num_rows) {
            $row = $res->fetch_assoc();
            $query = [];
            $query[] = "UPDATE `".$this->table."` SET 
                            `".$this->cell."` = ".$row['b_order']." 
                            WHERE `".$this->row_id."` = ".$row['a_id'];
            $query[] = "UPDATE `".$this->table."` SET 
                            `".$this->cell."` = ".$row['a_order']." 
                            WHERE `".$this->row_id."` = ".$row['b_id'];
            $this->query($query);
            return 1;
        }
        else
            return 2;
    }
	
/**
 *    Метод mysqlReOrder: Востанавливает целостность группировки
 */
 
    public function mysqlReOrder()
    {
        $group = $this->getGroup();

        $res = $this->query("SELECT `".$this->row_id."`
                             FROM `".$this->table."`
                             ".$group."
                             ORDER BY `".$this->cell."`
                            ");
        $query = [];
        $i = $this->reorder;
        while($row = $res->fetch_assoc())
            $query[] = "UPDATE `".$this->table."` SET `".$this->cell."` = ".$i++." WHERE `".$this->row_id."`= ".$row[$this->row_id];

        return ($this->query($query)) ? 9 : 10;
    }

}