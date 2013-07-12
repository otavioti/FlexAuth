<?php
App::uses('AppHelper', 'View/Helper');

class TableTreeHelper extends AppHelper {
    
    public $tabela=array();
    public $pronta="";
    
    
    
    public function agrupa($nome,$code) {
        
        if(strpos($nome,".")!== false) {
            $groups=explode(".",$nome);
            $groupname=array_shift($groups);
            $restante=join(".",$groups);
             
            $this->tabela[$groupname][]=$this->agrupaElementos($restante, $nome, $code);
        } else {
            $this->tabela[$nome]=$code;
        }
        
    }
    
    public function agrupaElementos($nome,$nomecompleto,$code) {
         
        if(strpos($nome,".")!== false) {
            $groups=explode(".",$nome);
            $groupname=array_shift($groups);
            $restante=join(".",$groups);
             
            $tabela[$groupname][]=$this->agrupaElementos($restante, $nomecompleto, $code);
            return $tabela;
        } else {
            return array($nomecompleto=>$code);
        }
    }
    
    public function monta($tabela) {
            $h="";
            foreach($tabela as $k=>$t) {
                if(is_array($t)) {
                    $h.="<tr><td>".$k."</td><td><a href='#'onClick='showHide(\"".$k."ID"."\")'>mostrar/esconder</a></td></tr>\n";
                    $h.="<tr id='".$k."ID"."' ><td colpan=2 ><table >".$this->monta($t)."</table></td></tr>\n";
                } else {
                    $h.="<tr><td>".$k."</td><td>".$t."</td></tr>\n";
                }
            }
        return $h;
    }
    
    public function montaUl($tabela) {
        $h="";
        foreach($tabela as $k=>$t) {
            if(is_array($t)) {
                
                $h.="<li>".$k."<ul>".$this->monta($t)."</ul></li>\n";
            } else {
                $h.="<li>".$k." ".$t."</li>\n";
            }
        }
        return $h;
    }
    
    public function show() {
        return $this->monta($this->tabela);
    }
    
}


?>