<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService extends AbstractController
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(Environment $twig, RequestStack $request, $templatePath)
    {
        $this->twig = $twig;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function display()
    {
        $this->twig->display($this->templatePath,[
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->getRoute(),
        ]);
    }

    public function getPages()
    {
        if (empty($this->entityClass))
        {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer !");
        }

        // 1. Connaitre le total des enregistrements de la table
        $repo = $this->getDoctrine()->getManager()->getRepository($this->entityClass);
        $total = $repo->count([]);

        // 2. Faire la division, l'arrondi et le renvoyer
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getData()
    {
        if (empty($this->entityClass))
        {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer !");
        }
        
        // 1. Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2. Demander au repository de trouver les éléments
        $repo = $this->getDoctrine()->getManager()->getRepository($this->entityClass);
        $data = $repo->findBy([],[],$this->limit,$offset);

        // 3. Renvoyer les élements en question
        return $data;
        
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

}