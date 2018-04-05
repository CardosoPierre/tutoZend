<?php
namespace Article\Controller;

use Article\Model\Article;
use Article\Model\ArticleTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Article\Form\ArticleForm;

class ArticleController extends AbstractActionController
{
    private $table;

    public function __construct(ArticleTable $table)
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
        $form = new ArticleForm();
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $article = new Article();
        $form->setInputFilter($article->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $article->exchangeArray($form->getData());
        $this->table->saveArticle($article);

        return $this->redirect()->toRoute('article');
    }
    public function editAction()
    {
        // recuperer l'id de la route:
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) { // s'il est nul retourner l'action add
            return $this->redirect()->toRoute('article', ['action' => 'add']);
        }

        // recupere l'article avec l'id.

        try {
            $article = $this->table->getArticle($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('article', ['action' => 'index']); // si impossible retourner au listing article
        }

        $form = new ArticleForm();
        $form->bind($article);
        $form->get('submit')->setAttribute('value', 'Modifiez');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($article->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveArticle($article);

        // Redirige vers la liste d'articles
        return $this->redirect()->toRoute('article', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('article');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $id = (int) $request->getPost('id');
                $this->table->deleteArticle($id);
            }

            return $this->redirect()->toRoute('article');
        }

        return [
            'id'    => $id,
            'article' => $this->table->getArticle($id),
        ];
    }
}