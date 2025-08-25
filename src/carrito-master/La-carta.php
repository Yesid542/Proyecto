<?php 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Cart {
    protected $cart_contents = array();
    
    public function __construct(){
        // Obtener el carrito de la sesión
        $this->cart_contents = !empty($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : NULL;
        if ($this->cart_contents === NULL){
            // Valores iniciales
            $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        }
    }
    
    public function contents(){
        $cart = array_reverse($this->cart_contents);
        unset($cart['total_items'], $cart['cart_total']);
        return $cart;
    }
    
    public function get_item($row_id){
        return (!isset($this->cart_contents[$row_id]) || in_array($row_id, array('total_items', 'cart_total'), TRUE)) 
            ? FALSE 
            : $this->cart_contents[$row_id];
    }
    
    public function total_items(){
        return $this->cart_contents['total_items'];
    }
    
    public function total(){
        return $this->cart_contents['cart_total'];
    }
    
    public function insert($item = array()){
        if(!is_array($item) || count($item) === 0){
            return FALSE;
        }else{
            if(!isset($item['IdCalzado'], $item['Descripcion'], $item['Precio'], $item['qty'])){
                return FALSE;
            }else{
                // Preparar cantidad y precio
                $item['qty'] = (int) $item['qty'];
                if($item['qty'] == 0){
                    return FALSE;
                }
                $item['Precio'] = (float) $item['Precio'];

                // Generar un identificador único
                $rowid = md5($item['IdCalzado']);
                $old_qty = isset($this->cart_contents[$rowid]['qty']) ? (int) $this->cart_contents[$rowid]['qty'] : 0;

                // Actualizar cantidad
                $item['rowid'] = $rowid;
                $item['qty'] += $old_qty;
                $this->cart_contents[$rowid] = $item;

                return $this->save_cart() ? $rowid : FALSE;
            }
        }
    }
    
    public function update($item = array()){
        if (!is_array($item) || count($item) === 0){
            return FALSE;
        }else{
            if (!isset($item['rowid'], $this->cart_contents[$item['rowid']])){
                return FALSE;
            }else{
                if(isset($item['qty'])){
                    $item['qty'] = (int) $item['qty'];
                    if ($item['qty'] == 0){
                        unset($this->cart_contents[$item['rowid']]);
                        return TRUE;
                    }
                }
                
                $keys = array_intersect(array_keys($this->cart_contents[$item['rowid']]), array_keys($item));
                if(isset($item['Precio'])){
                    $item['Precio'] = (float) $item['Precio'];
                }
                
                foreach(array_diff($keys, array('IdCalzado', 'Descripcion')) as $key){
                    $this->cart_contents[$item['rowid']][$key] = $item[$key];
                }

                return $this->save_cart();
            }
        }
    }
    
    protected function save_cart(){
        $this->cart_contents['total_items'] = $this->cart_contents['cart_total'] = 0;
        foreach ($this->cart_contents as $key => $val){
            if(!is_array($val) || !isset($val['Precio'], $val['qty'])){
                continue;
            }
            $this->cart_contents['cart_total'] += ($val['Precio'] * $val['qty']);
            $this->cart_contents['total_items'] += $val['qty'];
            $this->cart_contents[$key]['subtotal'] = ($this->cart_contents[$key]['Precio'] * $this->cart_contents[$key]['qty']);
        }
        
        if(count($this->cart_contents) <= 2){
            unset($_SESSION['cart_contents']);
            return FALSE;
        }else{
            $_SESSION['cart_contents'] = $this->cart_contents;
            return TRUE;
        }
    }
    
    public function remove($row_id){
        unset($this->cart_contents[$row_id]);
        return $this->save_cart();
    }
    
    public function destroy(){
        $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        unset($_SESSION['cart_contents']);
    }
}
?>
