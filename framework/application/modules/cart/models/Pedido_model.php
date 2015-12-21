<?php
class Pedido_model extends CI_Model
{

    public function get($prodId, $userId)
    {
        $this->db->where('productoId', $prodId);
        $this->db->where('usuarioId', $userId);
        $query = $this->db->get('pedidos');

        if(count($query->row()) != 0)
            return FALSE;
        else
            return $query->row();

    }

    public function getAll($userId)
    {
        $this->db->where('usuarioId', $userId);
        $query = $this->db->get('pedidos');

        $resultArr = array();
        $result = $query->result();

        foreach ($result as $key => $pedido) {
            $resultArr[$pedido->productoId] = $pedido->cantidad;
        }

        return $resultArr;

    }

    public function add($prodId, $cantidad, $userId)
    {

        $this->db->where('productoId', $prodId);
        $this->db->where('usuarioId', $userId);
        $query = $this->db->get('pedidos');

        if(count($query->result()) > 0)
        {
            $this->update($prodId, $cantidad, $userId);
        }

        else
        {

            $data = array(
                'usuarioId' => $userId,
                'productoId' => $prodId,
                'cantidad' => $cantidad
            );

            $this->db->insert('pedidos', $data);
        }

        return count($this->getAll($userId));

    }

    function addFromSession($pedidos, $user)
    {

        foreach ($pedidos as $key => $value) {

            //Revisamos si ya existe en la BD
            $this->db->where('usuarioId', $user->id);
            $this->db->where('productoId', $key);
            $query = $this->db->get('pedidos');

            $producto = $query->row();

            //Si existe el producto en el el tabla de pedidos la actualizamos
            if(count($producto) > 0)
            {
                $data = array(
                    'cantidad' => $producto->cantidad + $value
                );

                $this->db->where('usuarioId', $user->id);
                $this->db->where('productoId', $key);
                $this->db->update('pedidos', $data);

            }

            //Ni no existe la añadimos
            else
            {
                $data = array(
                    'cantidad' => $value,
                    'productoId' => $key,
                    'usuarioId' => $user->id
                );

                $this->db->insert('pedidos', $data);

            }

        }

    }

    public function update($prodId, $cantidad, $userId)
    {

        $data = array(
            'cantidad' => $cantidad
        );

        $this->db->where('productoId', $prodId);
        $this->db->where('usuarioId', $userId);
        $this->db->update('pedidos', $data);

    }

    public function delete($prodId, $userId)
    {
        $this->db->where('productoId', $prodId);
        $this->db->where('usuarioId', $userId);
        $this->db->delete('pedidos');

        return count($this->getAll($userId));
    }

    public function cleanPedidos($id='')
    {
        $this->db->where('usuarioId', $id);
        $this->db->delete('pedidos');
    }

}