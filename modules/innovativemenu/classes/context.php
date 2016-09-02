<?php

class Innovative_Context extends ObjectModel
{
        /** @var string Object SQL table */
        protected $table = 'innovativemenu_context';
        
        /** @var string Object SQL identifier */
	protected $identifier = 'id_menu_context';
        
        /** @var Integer : can take the values of the constants 
         * Shop::CONTEXT_SHOP, Shop::CONTEXT_ALL or Shop::CONTEXT_GROUP
         */
        public $type_context;
        
        /** @var integer id_shop or id_shop_group or 0 */
        public $id_element;
        
        public function getFields() 
	{ 
                $fields = array();
		parent::validateFields();
		$fields['id_element'] = (int)$this->id_element;
                $fields['type_context'] = (int)$this->type_context;
		return $fields;	 
	}
        
        
        public static function load($type_context, $id_element = 0)
        {
                
                $id = Db::getInstance()->getValue('
                                                SELECT `id_menu_context` FROM `'._DB_PREFIX_.'innovativemenu_context`
                                                WHERE `id_element`='.(int)$id_element.' AND `type_context`='.(int)$type_context);
                
                $innovative_context = new self($id);
                if (empty($innovative_context->id))
                {
                        $innovative_context->type_context = $type_context;
                        $innovative_context->id_element = $id_element;
                        $innovative_context->save();
                }
                return $innovative_context;
        }
        
        
        public function add($autodate = true, $null_values = false)
        {
                $id = Db::getInstance()->getValue('
                                                SELECT `id_menu_context` FROM `'._DB_PREFIX_.'innovativemenu_context`
                                                WHERE `id_element`='.(int)$this->id_element.' AND `type_context`='.(int)$this->type_context);
                
                if (empty($id))
                        parent::add($autodate, $null_values);
        }

}