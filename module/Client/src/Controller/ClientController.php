<?php
namespace Client\Controller;
use Client\Model\ClientTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Client\Form\ClientForm;
use Client\Model\Client;

class ClientController extends AbstractActionController
{
    private $table;

    public function __construct(ClientTable $table)
    {
        $this->table=$table;
    }

    public function indexAction()
    {
        $paginator = $this->table->fetchAll(true);

        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);

         return new ViewModel(['paginator' => $paginator]);
    }

    public function addAction()
    {
        $form = new ClientForm();
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $client = new Client();
        $form->setInputFilter($client->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $client->exchangeArray($form->getData());
        $this->table->saveClient($client);

        return $this->redirect()->toRoute('client');
    }
    public function editAction()
    {
        // recuperer l'id de la route:
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) { // s'il est nul retourner � l'action add
            return $this->redirect()->toRoute('client', ['action' => 'add']);
        }

        // recupere le client avec l'id.

        try {
            $client = $this->table->getClient($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('client', ['action' => 'index']); // si impossible retourner au listing client
        }

        $form = new ClientForm();
        $form->bind($client);
        $form->get('submit')->setAttribute('value', 'Modifiez');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($client->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveClient($client);

        // Redirige vers la liste de clients
        return $this->redirect()->toRoute('client', ['action' => 'index']);
    }

     public function deleteAction()
     {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('client');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $id = (int) $request->getPost('id');
                $this->table->deleteClient($id);
            }

            return $this->redirect()->toRoute('client');
        }

        return [
            'id'    => $id,
            'client' => $this->table->getClient($id),
        ];
     }
}