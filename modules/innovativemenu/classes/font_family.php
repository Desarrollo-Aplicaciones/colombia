<?php
//require_once dirname(__FILE__).'/../../../config/config.inc.php';

class Innovative_Font_Family extends ObjectModel
{
        /** @var string Object SQL table */
        protected $table = 'innovativemenu_font_family';
        
        /** @var string Object SQL identifier */
	protected $identifier = 'id_font';
        
        public $with_file;
        
        /** @var integer id in SQL table */
        public $id_font;
        
        /** @var string name */
        public $name;
        
        /** @var string alternative name */
        public $alt_name1;
        
        /** @var string alternative name 2 */
        public $alt_name2;
        
        public function getFields() 
	{ 
                $fields = array();
		parent::validateFields();
		$fields['with_file'] = (bool)$this->with_file;
                $fields['name'] = pSQL($this->name);
                $fields['alt_name1'] = pSQL($this->alt_name1);
                $fields['alt_name2'] = pSQL($this->alt_name2);
                
		return $fields;	 
	}
        
        
        public static function get($names = false, $objects = false)
        {
                $results = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'innovativemenu_font_family`');
                
                if ($names AND is_array($results) AND !empty($results))
                {
                        $names = array ();
                        foreach ($results as $value)
                        {
                                if (!empty($value['alt_name1']))
                                        $value['name'] .= ', '.$value['alt_name1'];
                                if (!empty($value['alt_name2']))
                                        $value['name'] .= ', '.$value['alt_name2'];
                                $names[] = $value['name'];
                        }

                        return $names;
                }
                
                elseif ($objects)
                {
                        $objects = array ();
                        foreach ($results as $value)
                                $objects[] = new self($value['id_font']); 

                        return $objects;
                }
                
                return $results;
        }
        
        public function updateFile()
        {
                if (!$this->with_file)
                        return;

                $upload_dir = _PS_MODULE_DIR_.'innovativemenu/css/fonts/upload/';
                $files = scandir($upload_dir);
                foreach ($files AS $file)
                {
                        if (strrpos($file, 'zip') !== false)
                        {
                                $font_dir = _PS_MODULE_DIR_.'innovativemenu/css/fonts/'.$this->id;
                                if (!file_exists($font_dir) OR !is_dir($font_dir))
                                        mkdir($font_dir, 0777);

                                Tools::ZipExtract($upload_dir.$file, $font_dir);
                                unlink($upload_dir.$file);
                                break;
                        }
                }
        }
        
        
        public function css()
        {
                if (!$this->with_file)
                        return;
                
                $font_dir = _PS_MODULE_DIR_.'innovativemenu/css/fonts/'.$this->id;
                if (!file_exists($font_dir) OR !is_dir($font_dir))
                        return;
                
                        
                $files = scandir($font_dir);
                foreach ($files AS $file)
                {
                        if (strrpos($file, 'ttf') !== false)
                                $ttf_url = _MODULE_DIR_.'innovativemenu/css/fonts/'.$this->id.'/'.$file;
                        if (strrpos($file, 'eot') !== false)
                                $eot_url = _MODULE_DIR_.'innovativemenu/css/fonts/'.$this->id.'/'.$file;
                        if (strrpos($file, 'otf') !== false)
                                $otf_url = _MODULE_DIR_.'innovativemenu/css/fonts/'.$this->id.'/'.$file;
                        if (strrpos($file, 'woff') !== false)
                                $woff_url = _MODULE_DIR_.'innovativemenu/css/fonts/'.$this->id.'/'.$file;
                }
                
                if (empty($eot_url) AND empty($ttf_url) AND empty($otf_url) AND empty($woff_url))
                        return;

                $output = 
                        '@font-face {
                        font-family: '.Tools::htmlentitiesUTF8($this->name).';';
                if (!empty($eot_url))
                        $output .= 'src: url("'.$eot_file.'");';
                if (!empty($ttf_url) OR !empty($otf_url) OR !empty($woff_url))
                {
                        $output .= 'src: local("x")';
                        if (!empty($ttf_url))
                                $output .= ',url("'.$ttf_url.'") format("TrueType")';
                        if (!empty($otf_url))
                                $output .= ',url("'.$otf_url.'") format("openType")';
                        if (!empty($woff_url))
                                $output .= ',url("'.$woff_url.'") format("woff")';
                        $output .= ';';
                }

                return $output.'}';
        }
}