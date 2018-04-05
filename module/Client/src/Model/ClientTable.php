<?php
namespace Client\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class ClientTable
{
    private $tableGateway ;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway ;
    }

    public function fetchPaginatedResults()
    {
        $select = new Select($this->tableGateway->getTable());
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Client());
        $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function fetchAll($pagingated = false)
    {
        if($pagingated) {
            return $this->fetchPaginatedResults();
        }
        return $this->tableGateway->select() ;
    }

    public function getClient($id)
    {
        $id = (int) $id ;
        $rowset = $this->tableGateway->select(['id' => $id]) ;
        $row = $rowset->current() ;
        if ( !$row) {
            throw new RuntimeException(sprintf("Impossible de trouver l'enregistrement %d",id)) ;
        }
        return $row;
    }

    public function saveClient(Client $client)
    {
        $data = [
            'nom' => $client->nom,
            'prenom' => $client->prenom,
        ];

        $id = (int)$client->id ;

        if ($id === 0) {
            $this->tableGateway->insert($data) ;
            return;
        }

        if (!$this->getClient($id)) {
            throw new RuntimeException(sprintf("Impossible de mettre � jour le client avec  l'enregistrement %d, il n'existe pas",$id));
        }

        $this->tableGateway->update($data, array('id' => $id)) ;
    }

    public function deleteClient($id)
    {
        $this->tableGateway->delete(array('id' => (int)$id)) ;
    }
}