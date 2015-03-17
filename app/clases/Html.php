<?php
class Html {
	
	private static $_template = '';
	private $_db , $_menu = '';
	
	public function __construct () {
            $this->_db = Bdd::getInstance();
	}
	
	public static function vista ($html = array() , $array = array()) {

            foreach ($html as $archivo) {
                self::$_template .= file_get_contents ($archivo);
            }

            foreach ($array as $clave => $valor) {
                self::$_template = str_replace('{'.$clave.'}', $valor, self::$_template);
            }

            echo self::$_template;
	}
	
	public function armarMenu ($id_menu) {
            $params = array ($id_menu);
            $sql = "
                select m2.* 
                from 
                        sistema.modulos_menu m1 left join 
                        sistema.modulos m2 on m1.id_modulo = m2.id_modulo
                where 
                        id_menu = ?
                        and nivel_2 = 0
                order by nivel1 , nivel2";
            $data = $this->_db->query($sql , $params)->getResults();
		
            foreach ($data as $dato) {
                $this->_menu .= $dato['id_modulo'] == 5 ? '<li class="start active">' : '<li>' ;
                if (! strlen ($dato['modulo'])) {
                        $this->_menu .= '<a href="#"><i class="' . $dato['icono_metronic'] . '"></i><span class="title">' . $dato['nombre'] . '</span><span class="arrow "></span></a>';
                } else {
                        $this->_menu .= '<a href="app/controladores/' . $dato['modulo'] . '">';
                        $this->_menu .= '<i class="' . $dato['icono_metronic'] . '"></i><span class="title">' . $dato['nombre'] . '</span>';
                        $this->_menu .= $dato['id_modulo'] == 5 ? '<span class="selected"></span></a>' : '</a>' ;
                }
                $data_sub = $this->armarSubMenu($dato['nivel_1'],$_SESSION['id_menu']);
                if ($this->_db->getCount()){
                        $this->_menu .= '<ul class="sub-menu">';
                        foreach ($data_sub as $sub) {
                                $this->_menu .= '<li><a href="app/controladores/' . $sub['modulo'] . '">' . $sub['nombre'] . '</a></li>';
                        }
                        $this->_menu .= '</ul>';
                }
                $this->_menu .= '</li>';
            }	
            return $this->_menu;
	}
	
	private function ArmarSubMenu ($nivel_padre , $id_menu) {
            $params = array ($nivel_padre , $id_menu);
            $sql="
                select
                        m2.* 
                from 
                        sistema.modulos_menu m1
                        left join sistema.modulos m2 on m1.id_modulo = m2.id_modulo
                where 	
                        m2.nivel_1 = ?
                        and m2.nivel_2 <> 0 
                        and m1.id_menu = ?
                order by m2.nivel_2 asc";
            return $this->_db->query($sql , $params)->getResults();
	}
}
?>
